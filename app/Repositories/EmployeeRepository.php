<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Clas;
use App\Models\User;
use App\Models\Ward;
use App\Models\Shift;
use App\Models\Device;
use App\Models\Employee;
use App\Models\UserLeave;
use App\Models\Contractor;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeRepository
{

    public function store($input)
    {
        DB::beginTransaction();
        $input['tenant_id'] = Auth::user()->tenant_id;
        $input['password'] = Hash::make('password');
        $input['emp_code'] = strtoupper($input['emp_code']);
        $input['is_employee'] = '1';
        $input['shift_id'] = $input['shift_id'] ?? '1';
        $input['work_duration'] = $input['work_duration'] ? (($input['work_duration'] * 60) * 60) : null;
        $input['sa_duration'] = $input['sa_duration'] ? (($input['sa_duration'] * 60) * 60) : null;
        $user = User::create(Arr::only($input, Auth::user()->getFillable()));

        if (!empty($input['leave_durations']) && is_array($input['leave_durations']))
        {
            foreach ($input['leave_durations'] as $leaveTypeId => $leaveDays)
            {
                UserLeave::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveTypeId,
                    'leave_days' => $leaveDays,
                ]);
            }
        }

        DB::commit();
    }


    public function editEmployee($user)
    {
        $user->load('department', 'subDepartment');

        $departments = Department::whereNull('department_id')->get();
        $subDepartments = Department::whereNotNull('department_id')->get();
        $wards = Ward::latest()->get();
        $devices = Device::orderByDesc('DeviceId')->get();
        $class = Clas::latest()->get();
        $designations = Designation::latest()->get();
        $shifts = Shift::latest()->get();
        $contractors = Contractor::get();

        if ($user) {
            $user->work_duration = $user->work_duration ? (($user->work_duration / 60) / 60) : null;
            $user->sa_duration = $user->sa_duration ? (($user->sa_duration / 60) / 60) : null;

            $departmentHtml = '
                <option value="">--Select Sub Department--</option>';
            foreach ($departments as $dep) :
                $is_select = $dep->id == $user->department_id ? "selected" : "";
                $departmentHtml .= '<option value="' . $dep->id . '" ' . $is_select . '>' . $dep->name . '</option>';
            endforeach;

            $subDepartmentHtml = '
                <option value="">--Select Sub Department--</option>';
            foreach ($subDepartments as $dep) :
                $is_select = $dep->id == $user->sub_department_id ? "selected" : "";
                $subDepartmentHtml .= '<option value="' . $dep->id . '" ' . $is_select . '>' . $dep->name . '</option>';
            endforeach;

            $deviceHtml = '
                <option value="">--Select Machine --</option>';
            foreach ($devices as $device) :
                $is_select = $device->DeviceId == $user->device_id ? "selected" : "";
                $deviceHtml .= '<option value="' . $device->DeviceId . '" ' . $is_select . '>' . $device->DeviceLocation . '</option>';
            endforeach;

            $wardHtml = '
                <option value="">--Select Office --</option>';
            foreach ($wards as $ward) :
                $is_select = $ward->id == $user->ward_id ? "selected" : "";
                $wardHtml .= '<option value="' . $ward->id . '" ' . $is_select . '>' . $ward->name . '</option>';
            endforeach;

            $clasHtml = '
                <option value="">--Select Class --</option>';
            foreach ($class as $clas) :
                $is_select = $clas->id == $user->clas_id ? "selected" : "";
                $clasHtml .= '<option value="' . $clas->id . '" ' . $is_select . '>' . $clas->name . '</option>';
            endforeach;

            $designationHtml = '
                <option value="">--Select Designation --</option>';
            foreach ($designations as $designation) :
                $is_select = $designation->id == $user->designation_id ? "selected" : "";
                $designationHtml .= '<option value="' . $designation->id . '" ' . $is_select . '>' . $designation->name . '</option>';
            endforeach;

            $shiftHtml = '
                <option value="">--Select Shift --</option>';
            foreach ($shifts as $shift) :
                $is_select = $shift->id == $user->shift_id ? "selected" : "";
                $shiftHtml .= '<option value="' . $shift->id . '" ' . $is_select . '>' . Carbon::parse($shift->from_time)->format('h:i A') . ' - ' . Carbon::parse($shift->to_time)->format('h:i A') . '</option>';
            endforeach;

            $contractorHtml = '
            <option value="">--Select Contractor --</option>';
            foreach ($contractors as $contractor) :
                $is_select = $contractor->id == $user->contractor_id ? "selected" : "";
                $contractorHtml .= '<option value="' . $contractor->id . '" ' . $is_select . '>' . $contractor->name . '</option>';
            endforeach;

            $response = [
                'result' => 1,
                'user' => $user,
                'departmentHtml' => $departmentHtml,
                'subDepartmentHtml' => $subDepartmentHtml,
                'deviceHtml' => $deviceHtml,
                'wardHtml' => $wardHtml,
                'clasHtml' => $clasHtml,
                'designationHtml' => $designationHtml,
                'shiftHtml' => $shiftHtml,
                'contractorHtml' => $contractorHtml,
                'user_leaves' => $user->userLeaves,
            ];
        } else {
            $response = ['result' => 0];
        }
        return $response;
    }


    public function updateEmployee($input, $emp)
    {
        if (gettype($emp) === 'string' || gettype($emp) ===  'integer')
            $emp = User::findOrFail($emp);

        DB::beginTransaction();
        $input['work_duration'] = $input['work_duration'] ? (($input['work_duration'] * 60) * 60) : $emp->work_duration;
        $input['sa_duration'] = $input['sa_duration'] ? (($input['sa_duration'] * 60) * 60) : $emp->sa_duration;
        $emp->update(Arr::only($input, Auth::user()->getFillable()));
        foreach ($input['leave_durations'] as $leaveTypeId => $leaveDays) {
            UserLeave::updateOrCreate(
                ['user_id' => $emp->id, 'leave_type_id' => $leaveTypeId],
                ['leave_days' => $leaveDays]
            );
        }
        DB::commit();
    }

    public function showEmployee($emp)
    {
        if (gettype($emp) === 'string' || gettype($emp) ===  'integer')
            $emp = User::findOrFail($emp);

        $emp->load(['department', 'subDepartment', 'designation', 'ward', 'clas', 'shift']);

        $html = '
                <div class="row">
                    <div class="col-4 mt-2"> <strong >Emp Code : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->emp_code . ' </div>

                    <div class="col-4 mt-2"> <strong >Full Name : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->name . ' </div>

                    <div class="col-4 mt-2"> <strong >Email : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->email . ' </div>

                    <div class="col-4 mt-2"> <strong >Mobile : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->mobile . ' </div>

                    <div class="col-4 mt-2"> <strong >Date of Birth : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->dob . ' </div>

                    <div class="col-4 mt-2"> <strong >Date of Joining : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->doj . ' </div>

                    <div class="col-4 mt-2"> <strong >Gender : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->gender_text . ' </div>

                    <div class="col-4 mt-2"> <strong >Department : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->department->name . ' </div>

                    <div class="col-4 mt-2"> <strong >Designation : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->designation?->name . ' </div>

                    <div class="col-4 mt-2"> <strong >Office : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->ward->name . ' </div>

                    <div class="col-4 mt-2"> <strong >Class : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->clas?->name . ' </div>

                    <div class="col-4 mt-2"> <strong >Shift : </strong> </div>
                    <div class="col-8 mt-2"> ' . Carbon::parse($emp->shift?->from_time)->format('h:i A') . ' - ' . Carbon::parse($emp->shift->to_time)->format('h:i A') . ' </div>

                    <div class="col-4 mt-2"> <strong >Is OT Allowed : </strong> </div>
                    <div class="col-8 mt-2"> ' . ($emp->is_ot == "y" ? "Yes" : "No") . ' </div>

                    <div class="col-4 mt-2"> <strong >Is Divyang : </strong> </div>
                    <div class="col-8 mt-2"> ' . ($emp->is_divyang == "y" ? "Yes" : "No") . ' </div>

                    <div class="col-4 mt-2"> <strong >Present Add : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->present_address . ' </div>

                    <div class="col-4 mt-2"> <strong >Permanent Add : </strong> </div>
                    <div class="col-8 mt-2"> ' . $emp->permanent_address . ' </div>

                </div>
            ';
        $html .= '</span>';

        return [
            'result' => 1,
            'html' => $html,
        ];
    }
}
