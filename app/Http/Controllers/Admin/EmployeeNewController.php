<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Punch;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeNewController extends Controller
{
    public $column = 'app_users.created_at';
    public $order = 'DESC';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();

        $employees = User::where('is_employee', '1')
            ->withTrashed()
            ->with([
                'subDepartment',
                'department',
                'ward',
                'designation',
                'clas',
                'shift',
                'device',
                'deletedBy',
                'contractor'
            ])
            ->leftJoin('wards', 'app_users.ward_id', '=', 'wards.id')
            ->leftJoin('designations', 'app_users.designation_id', '=', 'designations.id')
            ->leftJoin('departments', 'app_users.department_id', '=', 'departments.id')
            ->leftJoin('Devices', 'app_users.device_id', '=', 'Devices.DeviceId')
            ->select('app_users.*', 'wards.name as ward_name', 'departments.name as department_name', 'Devices.DeviceLocation as location_name', 'designations.name as designations_name')
            ->where('app_users.id', '!=', $authUser->id)
            ->when(
                !$authUser->hasRole(['Admin', 'Super Admin']),
                fn($q) => $q->where('app_users.department_id', $authUser->department_id)
            )
            ->orderBy($this->column, $this->order)
            ->get();

        return view('admin.employee-new-list', compact('employees'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function list(Request $request)
    {
        $contractorId = $request->contractor_id;
        $departmentId = $request->department_id;
        $designation = $request->designation;
        $employeeType   = $request->employeeType;
        // $ward = $request->ward;
        $authUser = auth()->user();
        $is_admin = $authUser->hasRole(['Admin', 'Super Admin']);

        $todaysDate = Carbon::today()->toDateString();
        $backDate = Carbon::today()->subDay()->toDateString();

        $punchData = Punch::whereIn('punch_date', [$todaysDate, $backDate])
        ->select('id', 'emp_code', 'check_in', 'check_out', 'duration', 'punch_date', 'is_latemark', 'is_latemark_updated', 'punch_by', 'type', 'leave_type_id')
        ->withWhereHas('user', fn($q) => $q->with('department')
                                ->when(!$is_admin, fn($qr) => $qr->where('department_id', $authUser->department_id) )
                                ->when($is_admin && $departmentId, fn($qr) => $qr->where('department_id', $departmentId))
                                // ->when($is_admin && $ward, fn($qr) => $qr->where('ward_id', $ward))
                                ->when($is_admin && $contractorId, fn($qr) => $qr->where('contractor_id', $contractorId))
                                ->when($is_admin && $designation, fn($qr) => $qr->where('designation_id', $designation))
                                ->where('employee_type', $employeeType)
        )
        ->latest()->get();

        // Get user IDs for present and absent employees
        $presentEmployees = $punchData->where('punch_date', '>=', Carbon::parse($todaysDate)->toDateString())->pluck('emp_code')->toArray();;

        // Fetch all employees based on contractor, department, and employee type
        $employeesQuery = User::where('contractor_id', $contractorId)
            ->with(['designation', 'department','punches'])
            ->where('department_id', $departmentId)
            // ->where('ward_id', $ward)
            ->where('designation_id', $designation);

            $status = $request->status;
            switch ($status) {
                case 'present':
                    $employeesQuery->whereIn('emp_code', $presentEmployees);
                    break;
                case 'absent':
                    $employeesQuery->whereNotIn('emp_code', $presentEmployees);
                    break;
                default:
                    break;
            }


        $employees = $employeesQuery->get();
        if ($status === 'present') {
            $employees->each(function ($employee) use ($todaysDate) {
                $punch = $employee->punches()->where('punch_date', $todaysDate)->first();
                $employee->check_in = $punch ? $punch->check_in : null;
                $employee->check_out = $punch ? $punch->check_out : null;
            });
        }

        return view('admin.dashboard.detailed-contractor-employee-list', compact('employees', 'contractorId', 'departmentId','status'));
    }
}
