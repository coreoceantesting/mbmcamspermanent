<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\SearchEmployeeCodeRequest;
use App\Models\Clas;
use App\Models\Department;
use App\Models\Designation;
// use App\Models\Employee;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showRegister()
    {
        $departments = Department::whereDepartmentId(null)->get();
        $wards = Ward::get();
        $designations = Designation::get();
        $classes = Clas::get();

        return view('employee.register')->with(['wards'=> $wards, 'departments'=> $departments, 'designations'=> $designations, 'classes'=> $classes]);
    }

    public function searchEmployeeCode(SearchEmployeeCodeRequest $request)
    {
        $user = User::where('emp_code', $request->emp_code)->first();
        if(!$user)
            return response()->json(['error2'=> 'Employee is not registered in our system, please register first and try again']);

        if($user->is_app_registered == 1)
            return response()->json(['error2'=> 'Employee id is already registered, try to reset your password']);

        $user->load('designation', 'clas');

        $departments = Department::get();
        $wards = Ward::get();
        $designations = Designation::get();
        $classes = Clas::get();

        $departmentHtml = '<span>
            <option value="">--Select Department--</option>';
            foreach($departments as $dep):
                $is_select = $dep->id == $user->department_id ? "selected" : "";
                $departmentHtml .= '<option value="'.$dep->id.'" '.$is_select.'>'.$dep->name.'</option>';
            endforeach;
        $departmentHtml .= '</span>';

        $wardHtml = '<span>
            <option value="">--Select Ward--</option>';
            foreach($wards as $ward):
                $is_select = $ward->id == $user->ward_id ? "selected" : "";
                $wardHtml .= '<option value="'.$ward->id.'" '.$is_select.'>'.$ward->name.'</option>';
            endforeach;
        $wardHtml .= '</span>';

        $designationHtml = '<span>
            <option value="">--Select Designation--</option>';
            foreach($designations as $designation):
                $is_select = $designation->id == $user->designation_id ? "selected" : "";
                $designationHtml .= '<option value="'.$designation->id.'" '.$is_select.'>'.$designation->name.'</option>';
            endforeach;
        $designationHtml .= '</span>';

        $classHtml = '<span>
            <option value="">--Select Designation--</option>';
            foreach($classes as $class):
                $is_select = $class->id == $user->clas_id ? "selected" : "";
                $classHtml .= '<option value="'.$class->id.'" '.$is_select.'>'.$class->name.'</option>';
            endforeach;
        $classHtml .= '</span>';

        $response = [
            'result' => 1,
            'user' => $user,
            'departmentHtml' => $departmentHtml,
            'wardHtml' => $wardHtml,
            'classHtml' => $classHtml,
            'designationHtml' => $designationHtml,
        ];

        return $response;
    }


    public function register(RegisterRequest $request)
    {
        $input = $request->validated();

        $user = User::where('emp_code', $input['emp_code'])->first();
        if( !$user )
            return response()->json(['error2'=> 'Employee is not registered in our system, please register first and try again']);

        $input['tenant_id']= '1';
        $input['in_time']= '10:00:00';
        $input['shift_id']= '1';
        $input['password']= Hash::make($input['password']);
        $input['is_app_registered']= 1;

        DB::beginTransaction();
        $user->update( Arr::only($input, $user->getFillable()) );
        DB::commit();

        Auth::guard('employee')->attempt(['emp_code' => $request->emp_code, 'password' => $request->password]);

        return response()->json(['success'=> 'Employee registered successfully']);
    }
}
