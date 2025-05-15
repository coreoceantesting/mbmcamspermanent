<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function showLogin()
    {
        // return bcrypt("Admin@123");
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Please enter username',
                'password.required' => 'Please enter password',
            ]
        );

        if ($validator->passes()) {
            $username = $request->username;
            $password = $request->password;
            $remember_me = $request->has('remember_me') ? true : false;

            try {
                if ($request->device_type == 'mobile') {
                    $user = User::where('emp_code', $username)->first();
                    if (!$user)
                        return response()->json(['error2' => 'No employee found with this employee id']);

                    if ($user->is_app_registered == '0')
                        return response()->json(['error2' => 'You are not registered, please register first']);

                    if (!auth()->guard('employee')->attempt(['emp_code' => $username, 'password' => $password], true))
                        return response()->json(['error2' => 'Your entered credentials are invalid']);

                    return response()->json(['success' => 'login successful', 'user_type' => 'employee']);
                }
                $user = User::where('email', $username)->orWhere('mobile', $username)->first();

                if (!$user)
                    return response()->json(['error2' => 'No user found with this username']);

                if ($user->active_status == '0' && !$user->roles)
                    return response()->json(['error2' => 'You are not authorized to login, contact HOD']);

                if (!auth()->attempt(['email' => $username, 'password' => $password], $remember_me) && !auth()->attempt(['mobile' => $username, 'password' => $password], $remember_me))
                    return response()->json(['error2' => 'Your entered credentials are invalid']);

                $userType = '';
                if ($user->hasRole(['Maker']))
                    $userType = 'maker';

                session()->put('EMPLOYEE_TYPE', 1);

                return response()->json(['success' => 'login successful', 'user_type' => $userType]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::info("login error:" . $e);
                return response()->json(['error2' => 'Something went wrong while validating your credentials!']);
            }
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }


    public function changeEmployeeType(Request $request, $type = null)
    {
        session()->put('EMPLOYEE_TYPE', $type == 1 ? $type : 2);

        return redirect()->back();
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }


    public function showChangePassword()
    {
        return view('admin.change-password');
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->passes()) {
            $old_password = $request->old_password;
            $password = $request->password;

            try {
                $user = DB::table('app_users')->where('id', $request->user()->id)->first();

                if (Hash::check($old_password, $user->password)) {
                    DB::table('app_users')->where('id', $request->user()->id)->update(['password' => Hash::make($password)]);

                    return response()->json(['success' => 'Password changed successfully!']);
                } else {
                    return response()->json(['error2' => 'Old password does not match']);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::info("password change error:" . $e);
                return response()->json(['error2' => 'Something went wrong while changing your password!']);
            }
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }
}
