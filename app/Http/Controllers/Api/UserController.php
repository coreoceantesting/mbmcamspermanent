<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\GetEmailForResetPasswordRequest;
use App\Http\Requests\Api\SetRandomPasswordRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    public function getEmail(GetEmailForResetPasswordRequest $request)
    {
        $user = User::where('emp_code', $request->emp_code)->first();
        if(!$user)
            return $this->respondWith([], 'No details found with this employee id', 200, false);

        if($user->email == NULL || $user->email == '')
            return $this->respondWith([], 'Employee does not have any email id registered', 200, false);

        return $this->respondWith(['email'=> $user->email]);
    }


    public function setRandomPassword(SetRandomPasswordRequest $request)
    {
        $newPassword = mt_rand(10000000, 99999999);

        if(User::where(['emp_code'=> $request->emp_code, 'email'=> $request->email])->update(['password'=> Hash::make($newPassword)]))
            return $this->respondWith(['message'=> 'Your new password is '.$newPassword]);


        return $this->respondWith([], 'Something went wrong while updating password');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        if(!Hash::check($request->old_password, $user->password))
            return $this->respondWith([], 'Old password does not match');

        if( $user->update(['password'=> Hash::make($request->password)]) )
            return $this->respondWith([], 'Password updated successfully');
    }

    public function getProfile(Request $request)
    {
        $user = Auth::user();

        $user->load('shift', 'designation', 'clas', 'department', 'ward');
        return $this->respondWith( new UserResource($user) );
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $input = $request->validated();

        $user->update( Arr::only($input, $user->getFillable()) );
        $user->load('shift', 'designation', 'clas', 'department', 'ward');

        return $this->respondWith( new UserResource($user) , 'Profile updated successfully');
    }
}
