<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Contractor;
use App\Models\Department;
use App\Models\Designation;
use App\Models\DeviceLogsProcessed;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Punch;
use App\Models\Setting;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\CarbonPeriod;
use Crypt;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $departments = Department::whereDepartmentId(null)
            ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn ($qr) => $qr->where('id', $authUser->department_id))
            ->orderBy('name')->get();

        $wards = Ward::orderBy('name')->get();
        $class = Clas::orderBy('name')->get();
        $contractors = Contractor::get();
        $designations = Designation::get();

        $empList = [];
        $leaveTypes = LeaveType::get();
        $totalDays = '';
        $holidays = 0;
        $dateRanges = [];

        $settings = Setting::getValues($authUser->tenant_id)->pluck('value', 'key');
        $fromDate = Carbon::parse($request->from_date)->toDateString();
        $toDate = Carbon::parse($request->to_date)->toDateString();
        $leavesArray = ['0' => 'HALFDAY', '1' => 'TECH', '2' => 'OUT', '3' => 'COMP', '4' => 'OL', '5' => 'EL', '6' => 'CL', '7' => 'MEDI'];
        $otherLeavesArray = ['no' => 'NIGHTOFF', 'co' => 'COMPENS', 'ph' => 'PUBLIC', 'so' => 'SATOFF', 'do' => 'DAYOFF'];

        if ($request->month) {
            $departmentId = $authUser->hasRole(['Admin', 'Super Admin']) ? $request->department : $authUser->department_id;
            $empList = User::whereNot('id', $authUser->id)
                ->with(['department', 'shift', 'empShifts' => fn ($q) => $q->whereBetween('from_date', [$fromDate, $toDate])])
                ->where('department_id', $departmentId)
                ->where('is_employee', 1)
                ->orderBy('emp_code')
                ->with('punches', fn ($q) => $q->whereBetween('punch_date', [$fromDate, $toDate]))
                ->when($request->contractor, fn ($qr) => $qr->where('contractor_id', $request->contractor))
                ->when($request->designation, fn ($qr) => $qr->where('designation_id', $request->designation))
                ->get();

            $holidays = Holiday::whereBetween('date', [$fromDate, $toDate])->get();
            $totalDays = Carbon::parse($fromDate)->diffInDays($toDate) + 1;
            $dateRanges = CarbonPeriod::create(Carbon::parse($fromDate), Carbon::parse($toDate))->toArray();
        }
        return view('admin.reports.month-wise-report')->with([
            'dateRanges' => $dateRanges,
            'leavesArray' => $leavesArray,
            'otherLeavesArray' => $otherLeavesArray,
            'empList' => $empList,
            'departments' => $departments,
            'holidays' => $holidays,
            'settings' => $settings,
            'leaveTypes' => $leaveTypes,
            'wards' => $wards,
            'class' => $class,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalDays' => $totalDays,
            'contractors' => $contractors,
            'designations'  => $designations
        ]);
    }


    public function musterReport(Request $request)
    {
        // designation
        $authUser = Auth::user();
        $departments = Department::whereDepartmentId(null)
            ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn ($qr) => $qr->where('id', $authUser->department_id))
            ->orderBy('name')->get();

        $wards = Ward::latest()->get();
        $class = Clas::latest()->get();
        // $designations = Designation::latest()->get();
        // $contractors = Contractor::latest()->get();
        $empList = [];
        $weekDays = '';
        $leaveTypes = LeaveType::get();
        $totalDays = '';
        $holidays = 0;
        $errorMessage = '';

        $settings = Setting::getValues($authUser->tenant_id)->pluck('value', 'key');
        $defaultShift = collect(config('default_data.shift_time'));
        $fromDate = Carbon::parse($request->from_date)->toDateString();
        $toDate = Carbon::parse($request->to_date)->toDateString();

        if ($request->month) {
            $department = $authUser->hasRole(['Admin', 'Super Admin']) ? $request->department : $authUser->department_id;

            $empList = User::whereNot('id', $authUser->id)
                ->select('id', 'clas_id', 'shift_id', 'designation_id', 'contractor_id', 'ward_id', 'department_id', 'sub_department_id', 'emp_code', 'in_time', 'name', 'is_rotational', 'is_ot', 'work_duration', 'sa_duration', 'is_divyang')
                ->with(['contractor', 'ward', 'department', 'shift', 'designation', 'clas', 'empShifts' => fn ($q) => $q->whereBetween('from_date', [$fromDate, $toDate])])
                ->with(
                    'punches',
                    fn ($q) => $q
                        ->whereBetween('punch_date', [$fromDate, $toDate])
                        ->select('id', 'emp_code', 'check_in', 'check_out', 'punch_date', 'is_latemark', 'type', 'leave_type_id', 'is_paid', 'duration', 'punch_by')
                )
                // ->where('ward_id', $request->ward)
                ->where('is_employee', 1)
                ->where('department_id', $department)
                ->when(!$request->emp_code && $request->sub_department, fn ($qr) => $qr->where('sub_department_id', $request->sub_department))
                ->when(!$request->emp_code && $request->class, fn ($qr) => $qr->where('clas_id', $request->class))
                // ->when(!$request->emp_code && $request->designation, fn ($qr) => $qr->where('designation_id', $request->designation))
                // ->when(!$request->emp_code && $request->contractor, fn ($qr) => $qr->where('contractor_id', $request->contractor))
                ->when($request->emp_code, fn ($qr) => $qr->where('emp_code', $request->emp_code))
                ->orderBy('emp_code')
                ->get();

            $holidays = Holiday::whereBetween('date', [$fromDate, $toDate])->get();
            $totalDays = Carbon::parse($fromDate)->diffInDays($toDate) + 1;
            $dateRanges = CarbonPeriod::create(Carbon::parse($fromDate), Carbon::parse($toDate))->toArray();
            try {
                $data['empList'] = $empList;
                $data['holidays'] = $holidays;
                $data['settings'] = $settings;
                $data['leaveTypes'] = $leaveTypes;
                $data['fromDate'] = $fromDate;
                $data['toDate'] = $toDate;
                $data['totalDays'] = $totalDays;
                $data['dateRanges'] = $dateRanges;
                $data['defaultShift'] = $defaultShift;
                $data['leavesArray'] = ['0' => 'HALFDAY', '1' => 'TECH', '2' => 'OUT', '3' => 'COMP', '4' => 'OL', '5' => 'EL', '6' => 'CL', '7' => 'MEDI'];
                $data['otherLeavesArray'] = ['no' => 'NIGHTOFF', 'co' => 'COMPENS', 'ph' => 'PUBLIC', 'so' => 'SATOFF', 'do' => 'DAYOFF'];

                $filename = str_replace('/', '_', 'MUSTER_' . $fromDate . '_' . $toDate);
                $filename = str_replace('-', '_', $filename . '.pdf');

                $pdf = SnappyPdf::loadView('admin.pdf.muster', $data)
                    ->setPaper('a4')
                    ->setOrientation('landscape')
                    ->setOption('margin-bottom', 0)
                    ->setOption('margin-top', 3)
                    ->setOption('margin-left', 0)
                    ->setOption('margin-right', 0);

                return $pdf->inline($filename);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                Log::info("error: " . $e);
            }
        }

        return view('admin.reports.muster-report')->with([
            'errorMessage' => $errorMessage,
            'empList' => $empList,
            'weekDays' => $weekDays,
            'holidays' => $holidays,
            'settings' => $settings,
            'leaveTypes' => $leaveTypes,
            'departments' => $departments,
            'wards' => $wards,
            'class' => $class,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalDays' => $totalDays,
            // 'designations' => $designations,
            // 'contractors' => $contractors,
        ]);
    }


    public function getContractorsNames($id)
    {
        $authUser = Auth::user();
        $departmentId = $id;
        $contractors = Contractor::whereHas('users', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId)
                ->where('employee_type', 0);
        })
            ->get();
        if ($contractors) {
            $ContractorHtml = '<span>
                <option value="">--Select Department--</option>';
            // $ContractorHtml .= '<option value="'.$cont->id.'" >'.$cont->name.'</option>';
            foreach ($contractors as $cont) :
                $ContractorHtml .= '<option value="' . $cont->id . '" >' . $cont->name . '</option>';
            endforeach;
            $ContractorHtml .= '</span>';

            $response = [
                'result' => 1,
                'ContractorHtml' => $ContractorHtml,
            ];
        } else {
            $response = ['result' => 0];
        }
        return $response;
    }

    public function todaysInTime(Request $request)
    {
        // do something about it
    }

    public function deviceLogReport(Request $request)
    {
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);
        $departments = Department::whereDepartmentId(null)
            ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn ($qr) => $qr->where('id', $authUser->department_id))
            ->orderBy('name')->get();

        $wards = Ward::orderBy('name')->get();
        $fromTime = $request->time_slot . ':00';
        $toTime = Carbon::parse($request->time_slot)->addMinutes('30')->toTimeString();
        $ranges = CarbonPeriod::create(Carbon::parse('00:00'), '0.5 hour', Carbon::parse('23:30'))->toArray();
        $timeSlots = [];
        foreach ($ranges as $range) {
            $timeSlots[] = $range->format('H:i');
        }

        $selectedDepartmentId = $isAdmin ? $request->department : $authUser->department_id;
        $selectedDate = $request->date ? Carbon::parse($request->date)->toDateString() : Carbon::today()->subDays(2)->format('Y-m-d');


        $datas = DeviceLogsProcessed::withWhereHas(
            'user',
            fn ($qr) => $qr->select('id', 'device_id', 'ward_id', 'clas_id', 'department_id', 'emp_code', 'name')
                ->with('device:DeviceId,DeviceLocation', 'ward', 'clas', 'department')
                ->when($selectedDepartmentId, fn ($q) => $q->where('department_id', $selectedDepartmentId))
                ->when($request->ward, fn ($q) => $q->where('ward_id', $request->ward))
                ->where('employee_type', 1)
            // ->when($request->sub_department, fn ($qr) => $qr->where('sub_department_id', $request->sub_department))
        )
            ->when($request->time_slot, fn ($qr) => $qr->whereTime('LogDate', '>=', $fromTime)->whereTime('LogDate', '<=', $toTime))
            ->whereDate('LogDate', $selectedDate)
            ->orderByDesc('DeviceLogId')
            // ->take(600)
            ->get();


        return view('admin.dashboard.device-log-report')->with(['datas' => $datas, 'timeSlots' => $timeSlots, 'isAdmin' => $isAdmin, 'departments' => $departments, 'wards' => $wards]);
    }

    public function departmentWiseReport(Request $request)
    {
        $data = Department::withCount(['users' => fn ($q) => $q->where('employee_type', 1)])
            ->withCount(['users as present_count' => fn ($q) => $q->withWhereHas('punches', fn ($qr) => $qr->where('punch_date', Carbon::today()->toDateString()))->where('employee_type', 1)])->get();

        return view('admin.dashboard.department-wise-attendance-report')->with(['data' => $data]);
    }

    // Same as device log report
    public function todaysPresentReport(Request $request)
    {
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);
        $departments = Department::whereDepartmentId(null)
            ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn ($qr) => $qr->where('id', $authUser->department_id))
            ->orderBy('name')->get();

        $wards = Ward::orderBy('name')->get();
        $selectedDepartmentId = $isAdmin ? $request->department : $authUser->department_id;

        $data = Punch::withWhereHas(
            'user',
            fn ($qr) => $qr->select('id', 'device_id', 'ward_id', 'department_id', 'emp_code', 'name', 'contractor_id', 'designation_id')
                ->with('device:DeviceId,DeviceLocation', 'ward', 'clas', 'contractor', 'designation')
                ->when($selectedDepartmentId, fn ($q) => $q->where('department_id', $selectedDepartmentId))
                ->when($request->ward, fn ($q) => $q->where('ward_id', $request->ward))
                ->where('employee_type', 1)
            // ->when($request->sub_department, fn ($qr) => $qr->where('sub_department_id', $request->sub_department))
        )
            ->whereDate('punch_date', Carbon::parse($request->date)->toDateString() ?? Carbon::today()->toDateString())
            ->orderByDesc('id')->get();

        return view('admin.dashboard.todays-present-report')->with(['data' => $data, 'wards' => $wards, 'isAdmin' => $isAdmin, 'departments' => $departments]);
    }

    public function todaysAbsentReport(Request $request)
    {
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);
        $departments = Department::whereDepartmentId(null)
            ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn ($qr) => $qr->where('id', $authUser->department_id))
            ->orderBy('name')->get();

        $selectedDepartmentId = $isAdmin ? $request->department : $authUser->department_id;
        $wards = Ward::orderBy('name')->get();

        $data = [];
        $date = $request->date ? Carbon::parse($request->date)->toDateString() : Carbon::today()->toDateString();
        if ($request->date) {
            $data = User::whereDoesntHave('punches', fn ($q) => $q->whereDate('punch_date', $date))
                ->select('id', 'device_id', 'ward_id', 'department_id', 'emp_code', 'name')
                ->whereIsEmployee('1')
                ->with('device:DeviceId,DeviceLocation', 'ward', 'shift', 'clas')
                ->when($selectedDepartmentId, fn ($q) => $q->where('department_id', $selectedDepartmentId))
                // ->when($request->sub_department, fn ($qr) => $qr->where('sub_department_id', $request->sub_department))
                ->when($request->ward, fn ($q) => $q->where('ward_id', $request->ward))
                ->where('employee_type', 1)
                ->get();
        }

        return view('admin.dashboard.todays-absent-report')->with(['data' => $data, 'wards' => $wards, 'isAdmin' => $isAdmin, 'departments' => $departments]);
    }

    public function shiftWiseEmployees(Request $request, $shiftId)
    {
        $shiftId = Crypt::decrypt($shiftId) ?? '1';
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);
        $departments = $isAdmin ? Department::whereDepartmentId(null)->orderBy('name')->get() : [];
        $wards = Ward::orderBy('name')->get();
        $selectedDepartmentId = $isAdmin ? $request->department : $authUser->department_id;

        $data = User::select('id', 'device_id', 'ward_id', 'department_id', 'emp_code', 'name')
            ->with('device:DeviceId,DeviceLocation', 'ward', 'department', 'shift', 'clas')
            ->where('shift_id', $shiftId)
            ->whereIsEmployee('1')
            ->when($selectedDepartmentId, fn ($q) => $q->where('department_id', $selectedDepartmentId))
            ->when($request->ward, fn ($q) => $q->where('ward_id', $request->ward))
            ->where('employee_type', 1)
            ->get();

        return view('admin.dashboard.shift-wise-employee')->with(['data' => $data, 'isAdmin' => $isAdmin, 'departments' => $departments, 'wards' => $wards]);
    }


    public function todaysLeaveBifurcation(Request $request)
    {
        $leave_type_id = $request->leave_type_id;
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole(['Admin', 'Super Admin']);
        $departments = $isAdmin ? Department::whereDepartmentId(null)->orderBy('name')->get() : [];
        $wards = Ward::orderBy('name')->get();
        $selectedDepartmentId = $isAdmin ? $request->department : $authUser->department_id;
        $date = $request->date ?? Carbon::today()->toDateString();

        $data = LeaveRequest::when($leave_type_id, fn ($qr) => $qr->where('leave_type_id', $leave_type_id))
            ->whereDate('from_date', $date)
            ->with([
                'leaveType',
                'user' => fn ($q) => $q->with('department', 'ward', 'device:DeviceId,DeviceLocation')
                    ->when($request->ward, fn ($qr) => $qr->where('ward_id', $request->ward))
                    ->when($selectedDepartmentId, fn ($qr) => $qr->where('department_id', $selectedDepartmentId))
                    ->where('employee_type', 1)
            ])
            ->get();

        return view('admin.dashboard.leave-bifurcation-report')->with(['data' => $data, 'isAdmin' => $isAdmin, 'departments' => $departments, 'wards' => $wards]);
    }


    public function monthWiseLatemark(Request $request)
    {
        $authUser = Auth::user();
        $departments = Department::whereDepartmentId(null)->orderBy('name')->get();
        $wards = Ward::orderBy('name')->get();
        $class = Clas::orderBy('name')->get();

        $departmentId = $authUser->hasRole(['Admin', 'Super Admin']) ? $request->department : $authUser->department_id;
        $ward = $authUser->hasRole(['Admin', 'Super Admin']) ? $request->department : $authUser->ward_id;
        $fromDate = $request->from_date ??  Carbon::today()->endOfMonth()->toDateString();
        $toDate = $request->to_date ?? Carbon::today()->startOfMOnth()->toDateString();

        $data = User::with('punches', fn ($q) => $q->where('is_latemark', '1')->whereDate('punch_date', '>=', $fromDate)->whereDate('punch_date', '<=', $toDate))
            ->with('ward', 'department')
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->when($ward, fn ($q) => $q->where('ward_id', $ward))
            ->where('employee_type', 1)
            ->get();

        // dd($data);
        // TODO: complete this report code

        return view('admin.dashboard.month-wise-latemark')->with(['data' => $data, 'departments' => $departments, 'wards' => $wards, 'class' => $class]);
    }


    public function employeeWiseReport(Request $request)
    {
        $fromDate = $request->from_date ??  Carbon::today()->endOfMonth()->toDateString();
        $toDate = $request->to_date ?? Carbon::today()->startOfMOnth()->toDateString();

        $data = [];
        if ($request->emp_code) {
            $data = Punch::withWhereHas(['user' => fn ($q) => $q->where('employee_type', 1)])->with('device')
                ->when($request->from_date, fn ($qr) => $qr->whereDate('punch_date', '>=', $fromDate))
                ->when($request->to_date, fn ($qr) => $qr->whereDate('punch_date', '<=', $toDate))
                ->where('emp_code', $request->emp_code)
                ->get();
        }
        return view('admin.dashboard.employee-wise-report')->with(['data' => $data]);
    }


    public function empLeaveCounts(Request $request)
    {
        $authUser = Auth::user();
        $departments = Department::whereDepartmentId(null)
            ->when(!$authUser->hasRole(['Admin', 'Super Admin']), fn ($qr) => $qr->where('id', $authUser->department_id))
            ->orderBy('name')->get();

        $wards = Ward::orderBy('name')->get();
        $class = Clas::orderBy('name')->get();
        $empList = [];
        $leaveTypes = LeaveType::get();
        $totalDays = '';
        $holidays = 0;
        $dateRanges = [];

        $settings = Setting::getValues($authUser->tenant_id)->pluck('value', 'key');
        $fromDate = Carbon::parse($request->from_date)->toDateString();
        $toDate = Carbon::parse($request->to_date)->toDateString();
        $leavesArray = ['0' => 'HALFDAY', '1' => 'TECH', '2' => 'OUT', '3' => 'COMP', '4' => 'OL', '5' => 'EL', '6' => 'CL', '7' => 'MEDI'];
        $otherLeavesArray = ['no' => 'NIGHTOFF', 'co' => 'COMPENS', 'ph' => 'PUBLIC', 'so' => 'SATOFF', 'do' => 'DAYOFF'];

        if ($request->month) {
            $departmentId = $authUser->hasRole(['Admin', 'Super Admin']) ? $request->department : $authUser->department_id;
            $empList = User::whereNot('id', $authUser->id)
                ->with([
                    'department',
                    'leaveRequests' => fn ($q) => $q->whereBetween('from_date', [$fromDate, $toDate]),
                    'punches' => fn ($q) => $q->whereBetween('punch_date', [$fromDate, $toDate])
                ])
                ->where('department_id', $departmentId)
                ->where('is_employee', 1)
                ->when($request->ward, fn ($qr) => $qr->where('ward_id', $request->ward))
                ->when($request->class, fn ($qr) => $qr->where('clas_id', $request->class))
                ->when($request->sub_department, fn ($qr) => $qr->where('sub_department_id', $request->sub_department))
                ->where('employee_type', 1)
                ->get();

            $dateRanges = CarbonPeriod::create(Carbon::parse($fromDate), Carbon::parse($toDate))->toArray();
            $holidays = Holiday::whereBetween('date', [$fromDate, $toDate])->get();
        }
        return view('admin.reports.emp-leaves-count')->with(['dateRanges' => $dateRanges, 'leavesArray' => $leavesArray, 'otherLeavesArray' => $otherLeavesArray, 'empList' => $empList, 'departments' => $departments, 'holidays' => $holidays, 'settings' => $settings, 'leaveTypes' => $leaveTypes, 'wards' => $wards, 'class' => $class, 'fromDate' => $fromDate, 'toDate' => $toDate, 'totalDays' => $totalDays]);
    }








    public function monthWiseDate(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? 1;


        $settings = Setting::getValues(auth()->user()->tenant_id)->pluck('value', 'key');
        $fromDate = Carbon::parse($year . '-' . ($month) . '-' . $settings['PAYROLL_DATE']);
        $toDate = clone ($fromDate);
        $fromDate = (string) $fromDate->subMonth()->toDateString();
        $toDate = (string) $toDate->subDay()->toDateString();

        return response()->json([
            'success' => true,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
}
