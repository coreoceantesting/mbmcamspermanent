<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\SearchEmployeeCodeRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{

    public function searchEmployeeCode(SearchEmployeeCodeRequest $request)
    {
        $user = User::where('emp_code', $request->emp_code)->first();
        if(!$user)
            return $this->respondWith( [], 'Employee is not registered in our system, please register first and try again', 200, false);

        if($user->is_app_registered == 1)
            return $this->respondWith( [], 'Employee id is already registered, try to reset your password', 200, false);

        $user->load('shift', 'designation', 'clas', 'department', 'ward');
        return $this->respondWith( new UserResource($user) );
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->validated();

        $user = User::where('emp_code', $input['emp_code'])->first();
        if( !$user )
            return $this->respondWith( [] , 'Employee is not registered in our system, please register first and try again', 200, false);

        $input['tenant_id']= '1';
        $input['in_time']= '10:00:00';
        $input['shift_id']= '1';
        $input['clas_id']= $input['class_id'];
        $input['password']= Hash::make($input['password']);
        $input['is_app_registered']= 1;

        DB::beginTransaction();
        $user->update( Arr::only($input, $user->getFillable()) );
        DB::commit();

        $token = explode('|', $user->createToken('auth_token')->plainTextToken, 2);
        $user->refresh();
        $user['token'] = $token[1];

        return $this->respondWith( new UserResource($user) , 'Employee registered successfully');
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('emp_code', $request->emp_code)->first();
        if (!$user || !Hash::check($request->password, $user->password))
            return $this->respondWith([], "Credentials does not match", 401, false);

        $token = explode('|', $user->createToken('auth_token')->plainTextToken, 2);
        $user->refresh();
        $user['token'] = $token[1];
        $user->load('shift', 'designation', 'clas', 'department', 'ward');

        return $this->respondWith(new UserResource($user));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->respondWith([]);
    }


    public function getProfile()
    {
        return $this->respondWith( new UserResource(User::where('id', Auth::id())->first()) );
    }
}
