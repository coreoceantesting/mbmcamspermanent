<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Holiday;
use App\Models\Punch;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ward;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1 => Permanent  0 =>  Contractual 
        $employeeType = 1;

        $authUser = auth()->user()->load('designation');
        $is_admin = $authUser->hasRole(['Admin', 'Super Admin', 'Officer']) ? true : false;
        $department = $request->ward; 
$is_higher_authority = $authUser->designation?->name === "Commissioner";

        $totalEmployees = User::when(!$is_higher_authority && !$is_admin , function ($query) use ($department, $authUser) {
            // Only apply department filter if not Commissioner
            $query->when($department, function ($q) use ($department) {
                return $q->where('department_id', $department);
            }, function ($q) use ($authUser) {
                return $q->where('department_id', $authUser->department_id);
            });
        })
       ->where('employee_type', $employeeType)
        
        ->count();

        $departments = '';
        if ($is_admin || $is_higher_authority) {
            $departments = Department::query()
                ->withCount([
                    'users' => fn($q) => $q
                        ->when($department, fn($qr) => $qr->where('department_id', $department))
                        ->where('employee_type', $employeeType)
                ])
                ->whereHas('users', function ($q) use ($employeeType) {
                    $q->where('employee_type', $employeeType);
                })
                ->whereDepartmentId(null)
                ->orderBy('orderno', 'ASC')
                ->get();

            $totalDepartments = $departments->count();
        } else {
            $totalDepartments = 1;
        }
    


        $totalHolidays = Holiday::where('year', date('Y'))->count();
        $totalWards = Ward::withCount(['users' => fn($q) => $q->where('employee_type', $employeeType)])->get();
        // $totalContractors = Contractor::withCount(['users' => fn($q) => $q->where('employee_type', $employeeType) $q->where('department_id', $request->department)])->get();
        $departmentId = $request->department;
        $totalContractors = Contractor::whereHas('users', function ($query) use ($employeeType, $departmentId) {
            $query->where('employee_type', $employeeType)
                ->where('department_id', $departmentId);
        })
            ->withCount(['users as users_count' => function ($query) use ($employeeType, $departmentId) {
                $query->where('employee_type', $employeeType)
                    ->where('department_id', $departmentId);
            }])
            ->get();

        $todaysDate = Carbon::today()->toDateString();
        $backDate = Carbon::today()->subDay()->toDateString();

        // $punchData = Punch::whereIn('punch_date', [$todaysDate, $backDate])
        //     ->select('id', 'emp_code', 'check_in', 'check_out', 'duration', 'punch_date', 'is_latemark', 'is_latemark_updated', 'punch_by', 'type', 'leave_type_id')
        //     ->withWhereHas(
        //         'user',
        //         fn($q) => $q->with('department')
        //             ->when(!$is_admin, fn($qr) => $qr->where('department_id', $authUser->department_id))
        //             ->when($is_admin && $department, fn($qr) => $qr->where('department_id', $department))
        //             ->where('employee_type', $employeeType)
        //     )
        //     ->latest()->get();
        
        $punchData = Punch::whereIn('punch_date', [$todaysDate, $backDate])
    ->select('id', 'emp_code', 'check_in', 'check_out', 'duration', 'punch_date', 'is_latemark', 'is_latemark_updated', 'punch_by', 'type', 'leave_type_id')
    ->withWhereHas('user', function ($query) use ($is_admin, $is_higher_authority, $department, $authUser, $employeeType) {
        $query->with('department')
            ->when(!$is_higher_authority, function ($q) use ($is_admin, $department, $authUser) {
                $q->when(!$is_admin, fn($qr) => $qr->where('department_id', $authUser->department_id))
                  ->when($is_admin && $department, fn($qr) => $qr->where('department_id', $department));
            })
            ->where('employee_type', $employeeType);
    })
    ->latest()
    ->get();


        $todayPunchData = $punchData->where('punch_date', '>=', Carbon::parse($todaysDate)->toDateString());
        $designations = Designation::select('id', 'name')->get();

       $leaveTypes = LeaveType::withSum([
    'userLeaves' => function ($query) {
        $query->whereHas('user', function ($q) {
            $q->whereIn('clas_id', [1, 2]);
        });
    }
], 'leave_days')
->withSum([
    'leaveRequests' => function ($query) {
        $query->where('is_approved', 1)
              ->whereHas('user', function ($q) {
                  $q->whereIn('clas_id', [1, 2]);
              });
    }
], 'no_of_days')
->get(); 
        return view('admin.dashboard.index')->with([
            'is_admin' => $is_admin,
            'totalEmployees' => $totalEmployees,
            'totalDepartments' => $totalDepartments,
            'totalHolidays' => $totalHolidays,
            'totalWards' => $totalWards,
            'todaysDate' => $todaysDate,
            'backDate' => $backDate,
            'punchData' => $punchData,
            'todayPunchData' => $todayPunchData,
            'departments' => $departments,
            'employeeType' => $employeeType,
            'totalContractors'  => $totalContractors,
            'designations'  => $designations,
            'leaveTypes'=>$leaveTypes
            'isHigherAuthority '=>$is_higher_authority ,
            // 'shiftWiseData'=> $shiftWiseData,
        ]);
    }


    public function tabularViewStatistics()
    {
        $departmentwise = Department::withCount(['users' => fn($q) => $q->where('employee_type', 1)])
            ->withCount(['users as present_count' => fn($q) => $q->withWhereHas('punches', fn($qr) => $qr->where('punch_date', Carbon::today()->toDateString()))->where('employee_type', 1)])->get();

        return view('admin.dashboard.tabular-view-statistics')->with([
            'departmentwise' => $departmentwise,
        ]);
    }

    public function fetchContractor($departmentid)
    {
        $employeeType = session('EMPLOYEE_TYPE') == 1 ? 0 : 1;
        // Assuming 'app_users' is the table where user details are stored
        // and 'contractors' is the table where contractor details are stored.

        // Joining 'app_users' with 'contractors' based on 'contractor_id' and 'id'.
        $contractors =  Contractor::whereHas('users', function ($query) use ($employeeType, $departmentid) {
            $query->where('employee_type', $employeeType)
                ->where('department_id', $departmentid);
        })->get();

        // Check if any contractors were found
        if ($contractors->isEmpty()) {
            return response()->json(['error' => 'No contractors found for the specified department.'], 404);
        }

        // If contractors were found, return them as JSON response
        return response()->json(['contractors' => $contractors]);
    }

    public function fetchdesignation(Request $request, $contractorid)
    {
        $departmentId = $request->departmentid;
        $employeeType = 1;

        // Query to fetch designations along with user counts and present counts
        $designations = DB::table('designations')
            ->join('app_users', 'app_users.designation_id', '=', 'designations.id')
            ->leftJoin('punches', function ($join) {
                $join->on('app_users.emp_code', '=', 'punches.emp_code')
                    ->where('punches.punch_date', Carbon::today()->toDateString());
            })
            ->where('app_users.contractor_id', $contractorid)
            ->where('app_users.department_id', $departmentId)
            ->where('app_users.employee_type', $employeeType)
            ->select(
                'designations.name',
                'designations.id',
                DB::raw('COUNT(app_users.id) as users_count'),
                DB::raw('COUNT(punches.id) as present_count')
            )
            ->groupBy('designations.name', 'designations.id')
            ->get();

        // dd($designations);

        if ($designations->isEmpty()) {
            return response()->json(['error' => 'No contractors found for the specified department.'], 404);
        }

        return response()->json(['designations' => $designations]);
    }

    public function fetchemployee(Request $request, $designationId)
    {
        $departmentId = $request->departmentid;
        $contractorId = $request->contractorId;
        $employeeType = 1;

        // Query to fetch designations along with user counts and present counts
        $employee_list = DB::table('designations')
            ->leftJoin('punches', function ($join) {
                $join->on('app_users.emp_code', '=', 'punches.emp_code')
                    ->where('punches.punch_date', Carbon::today()->toDateString());
            })
            ->where('app_users.contractor_id', $contractorId)
            ->where('app_users.department_id', $departmentId)
            ->where('app_users.designation_id', $designationId)
            ->where('app_users.employee_type', $employeeType)
            ->select(
                'designations.name',
                'designations.id',
                DB::raw('COUNT(app_users.id) as users_count'),
                DB::raw('COUNT(punches.id) as present_count')
            )
            ->groupBy('designations.name', 'designations.id')
            ->get();

        // dd($designations);

        if ($employee_list->isEmpty()) {
            return response()->json(['error' => 'No contractors found for the specified department.'], 404);
        }

        return response()->json(['employees' => $employee_list]);
    }

    public function list(Request $request)
    {
        $contractorId = $request->contractor_id;
        $departmentId = $request->department_id;
        $designation = $request->designation;
        $employeeType = $request->employeeType;

        // Query to fetch employee list with designations and counts
        $employee_list = DB::table('app_users')
            ->leftJoin('punches', function ($join) {
                $join->on('app_users.emp_code', '=', 'punches.emp_code')
                    ->where('punches.punch_date', Carbon::today()->toDateString());
            })
            ->where('app_users.contractor_id', $contractorId)
            ->where('app_users.department_id', $departmentId)
            ->where('app_users.designation_id', $designation)
            ->where('app_users.employee_type', $employeeType)
            ->select(
                DB::raw('COUNT(app_users.id) as users_count'),
                DB::raw('COUNT(punches.id) as present_count')
            )->get();


        // Check if any data is retrieved
        if ($employee_list->isEmpty()) {
            return response()->json(['error' => 'No employees found for the specified criteria.'], 404);
        }

        return view('admin.employee-list', compact('employee_list', 'contractorId', 'departmentId', 'designation', 'employeeType'));
    }
}
