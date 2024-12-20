<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\AssignUserRoleRequest;
use App\Http\Requests\Admin\ChangeUserPasswordRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['department'])->where('is_employee', '!=', '1')->whereNot('id', Auth::user()->id)->latest()->get();
        $departments = Department::whereDepartmentId(null)->latest()->get();
        $roles = Role::orderBy('id', 'DESC')->where('tenant_id', Auth::user()->tenant_id)->whereNot('name', 'like', '%super%')->get();
        $wards = Ward::whereNull('deleted_at')->select('id', 'name', 'initial')->get();
        return view('admin.users')->with(['users' => $users, 'roles' => $roles, 'departments' => $departments, 'wards' => $wards]);
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
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $input['tenant_id'] = Auth::user()->tenant_id;
            $input['password'] = Hash::make($input['password']);
            $input['is_employee'] = '0';
            $user = User::create(Arr::only($input, Auth::user()->getFillable()));
            DB::table('model_has_roles')->insert(['role_id' => $input['role'], 'model_type' => 'App\Models\User', 'model_id' => $user->id, 'tenant_id' => $user->tenant_id]);
            DB::commit();
            return response()->json(['success' => 'User created successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'creating', 'User');
        }
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
    public function edit(User $user)
    {
        $departments = Department::whereNull('department_id')->get();
        $subDepartments = Department::whereNotNull('department_id')->get();
        $wards = Ward::whereNull('deleted_at')->select('id', 'name')->get();
        $roles = Role::whereNot('name', 'like', '%super%')->get();
        $user->loadMissing('roles');

        if ($user) {
            $departmentHtml = '<span>
                <option value="">--Select Sub Department--</option>';
            foreach ($departments as $dep):
                $is_select = $dep->id == $user->department_id ? "selected" : "";
                $departmentHtml .= '<option value="' . $dep->id . '" ' . $is_select . '>' . $dep->name . '</option>';
            endforeach;
            $departmentHtml .= '</span>';

            $subDepartmentHtml = '<span>
                <option value="">--Select Sub Department--</option>';
            foreach ($subDepartments as $dep):
                $is_select = $dep->id == $user->sub_department_id ? "selected" : "";
                $subDepartmentHtml .= '<option value="' . $dep->id . '" ' . $is_select . '>' . $dep->name . '</option>';
            endforeach;
            $subDepartmentHtml .= '</span>';

            $roleHtml = '<span>
                <option value="">--Select Role --</option>';
            foreach ($roles as $role):
                $is_select = $role->id == $user->roles[0]->id ? "selected" : "";
                $roleHtml .= '<option value="' . $role->id . '" ' . $is_select . '>' . $role->name . '</option>';
            endforeach;
            $roleHtml .= '</span>';

            $wardHtml = '<span>
                <option value="">--Select Office --</option>';
            foreach ($wards as $ward):
                $is_select = $user->ward_id == $ward->id ? "selected" : "";
                $wardHtml .= '<option value="' . $ward->id . '" ' . $is_select . '>' . $ward->name . '</option>';
            endforeach;
            $wardHtml .= '</span>';

            $response = [
                'result' => 1,
                'user' => $user,
                'roleHtml' => $roleHtml,
                'departmentHtml' => $departmentHtml,
                'subDepartmentHtml' => $subDepartmentHtml,
                'wardHtml' => $wardHtml,
            ];
        } else {
            $response = ['result' => 0];
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $user->update(Arr::only($input, Auth::user()->getFillable()));
            $user->roles()->detach();
            DB::table('model_has_roles')->insert(['role_id' => $input['role'], 'model_type' => 'App\Models\User', 'model_id' => $user->id, 'tenant_id' => $user->tenant_id]);
            DB::commit();

            return response()->json(['success' => 'User updated successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'User');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function toggle(Request $request, User $user)
    {
        $current_status = DB::table('app_users')->where('id', $user->id)->value('active_status');
        try {
            DB::beginTransaction();
            if ($current_status == '1') {
                User::where('id', $user->id)->update(['active_status' => '0']);
            } else {
                User::where('id', $user->id)->update(['active_status' => '1']);
            }
            DB::commit();
            return response()->json(['success' => 'User status updated successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'changing', 'User\'s status');
        }
    }

    public function retire(Request $request, User $user)
    {
        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return response()->json(['success' => 'Employee retired successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'changing', 'Employee\'s retirement status');
        }
    }

    public function changePassword(ChangeUserPasswordRequest $request, User $user)
    {
        $input = $request->validated();
        try {
            DB::beginTransaction();
            $user->update(['password' => Hash::make($input['new_password'])]);
            DB::commit();
            return response()->json(['success' => 'Password updated successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'changing', 'User\'s password');
        }
    }


    public function getRole(User $user)
    {
        $user->load('roles');
        if ($user) {
            $roles = Role::orderBy('id', 'DESC')->where('tenant_id', Auth::user()->tenant_id)->get();
            $roleHtml = '<span>
                <option value="">--Select Role--</option>';
            foreach ($roles as $role):
                $is_select = $role->id == $user->roles[0]->id ? "selected" : "";
                $roleHtml .= '<option value="' . $role->id . '" ' . $is_select . '>' . $role->name . '</option>';
            endforeach;
            $roleHtml .= '</span>';

            $response = [
                'result' => 1,
                'user' => $user,
                'roleHtml' => $roleHtml,
            ];
        } else {
            $response = ['result' => 0];
        }
        return $response;
    }


    public function assignRole(User $user, AssignUserRoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $user->roles()->detach();
            DB::table('model_has_roles')->insert(['role_id' => $request->edit_role, 'model_type' => 'App\Models\User', 'model_id' => $user->id, 'tenant_id' => $user->tenant_id]);
            DB::commit();
            return response()->json(['success' => 'Role updated successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'changing', 'User\'s role');
        }
    }
}
