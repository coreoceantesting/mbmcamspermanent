<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Models\Clas;
use App\Models\Contractor;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Device;
use App\Models\Punch;
use App\Models\Shift;
use App\Models\User;
use App\Models\Ward;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    protected $employeeRepository;
    public function __construct()
    {
        $this->employeeRepository = new EmployeeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.employees');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wards = Ward::latest()->get();
        $departments = Department::whereDepartmentId(null)->orderBy('name')->get();
        $designations = Designation::latest()->get();
        $clas = Clas::latest()->get();
        $shifts = Shift::latest()->get();
        $devices = Device::orderByDesc('DeviceId')->get();
        $contractors = Contractor::get();


        return view('admin.add-employees')->with(['wards' => $wards, 'contractors' => $contractors, 'departments' => $departments, 'designations' => $designations, 'clas' => $clas, 'shifts' => $shifts, 'devices' => $devices]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            $this->employeeRepository->store($request->validated());
            return response()->json(['success' => 'Employee created successfully!']);
        } catch (Exception $e) {
            return $this->respondWithAjax($e, 'adding', 'Employee');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        return $this->employeeRepository->showEmployee($employee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        return $this->employeeRepository->editEmployee($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        try {
            $this->employeeRepository->updateEmployee($request->validated(), $employee);
            return response()->json(['success' => 'Employee updated successfully!']);
        } catch (Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'Employee');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function fetchInfo(User $employee)
    {
        $authUser = auth()->user();

        if ($authUser->hasRole(['Admin', 'Super Admin']))
            return $employee->load(['ward', 'clas', 'department']);

        if ($authUser->sub_department_id != $employee->sub_department_id)
            return response()->json(['error2' => 'Employee does not belongs to your department']);

        return $employee->load(['ward', 'clas', 'department']);
    }

    public function list(Request $request)
    {
        $contractorId = $request->contractor_id;
        $departmentId = $request->department_id;
        $employeeType = $request->employee_type;
        $authUser = auth()->user();
        $is_admin = $authUser->hasRole(['Admin', 'Super Admin']);

        $todaysDate = Carbon::today()->toDateString();
        $backDate = Carbon::today()->subDay()->toDateString();

        $punchData = Punch::whereIn('punch_date', [$todaysDate, $backDate])
            ->select('id', 'emp_code', 'check_in', 'check_out', 'duration', 'punch_date', 'is_latemark', 'is_latemark_updated', 'punch_by', 'type', 'leave_type_id')
            ->withWhereHas(
                'user',
                fn ($q) => $q->with('department')
                    ->when(!$is_admin, fn ($qr) => $qr->where('department_id', $authUser->department_id))
                    ->when($is_admin && $departmentId, fn ($qr) => $qr->where('department_id', $departmentId))
                    ->where('employee_type', $employeeType)
            )
            ->latest()->get();

        // Get user IDs for present and absent employees
        $presentEmployees = $punchData->where('punch_date', '>=', Carbon::parse($todaysDate)->toDateString())->pluck('emp_code')->toArray();;

        // Fetch all employees based on contractor, department, and employee type
        $employeesQuery = User::where('contractor_id', $contractorId)
            ->with(['designation', 'department'])
            ->where('department_id', $departmentId);
        // ->where('employee_type', $employeeType);
        // Check request status and filter employees accordingly
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
        // return $employees;
        $designations = Designation::select('id', 'name')->get();

        $wards = Ward::get();

        return view('admin.dashboard.contractor-employee-list', compact('employees', 'status', 'designations', 'wards', 'contractorId', 'departmentId', 'employeeType'));
    }
}
