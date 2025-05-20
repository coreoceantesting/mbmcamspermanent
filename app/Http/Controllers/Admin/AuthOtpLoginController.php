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
use Illuminate\Support\Facades\Auth;
use App\Models\GenerateOtp;
use Illuminate\Support\Facades\Http;

class AuthOtpLoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.otp-login');
    }

    public function generateOtp(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'mobile' => 'required|digits:10',
            ],
            [
                'mobile.required' => 'Please enter mobile',
                'mobile.digits' => 'Please enter 10 digit mobile no',
            ]
        );

        if ($validator->passes()) {
            $user = User::where('mobile', $request->mobile)->where('is_employee', 0)->first();

            if($user){
                GenerateOtp::where('mobile', $request->mobile)->delete();

                $otp = rand(1000, 9999);
                $mobile = $request->mobile;
                $text = "OTP for MBMC LOGIN is $otp for Mobile No $mobile. Please do not share OTP with anyone. Regards, Mira Bhaindar Municipal Corporation.";
                $url = "https://japi.instaalerts.zone/httpapi/QueryStringReceiver";

                $response = Http::get($url, [
                    'ver' => '1.0',
                    'key' => 'Isc5fvdX8tT3JF6X9aT9sA==',
                    'encrpt' => '0',
                    'dest' => '91' . $mobile,
                    'send' => 'MBMCPT',
                    'text' => $text,
                    'dlt_entity_id' => '1001158085062848906',
                    'dlt_template_id' => '1007530691210273647',
                ]);



                if ($response->successful()) {
                    GenerateOtp::create([
                        'mobile' => $request->mobile,
                        'otp' => $otp,
                        'ip' => request()->ip()
                    ]);
                    return response()->json(['success' => 'otp generated successfully']);
                } else {
                    return response()->json(['error' => 'Failed to send OTP', 'error' => $response->body()], 500);
                }


            }else{
                return response()->json(['error' => "No user found"]);
            }
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'mobile' => 'required',
                'otp' => 'required',
            ],
            [
                'mobile.required' => 'Please enter mobile',
                'otp.required' => 'Please enter otp',
            ]
        );

        if ($validator->passes()) {
            $check = GenerateOtp::where([
                'mobile' => $request->mobile,
                'otp' => $request->otp,
                'ip' => request()->ip(),
            ])->first();

            if($check){
                $user = User::where('mobile', $request->mobile)->where('is_employee', 0)->first();

                Auth::login($user);

                //  session()->put('EMPLOYEE_TYPE', 1);

                 return response()->json(['success' => 'login successfully']);
            }else{
                return response()->json(['error' => 'Please enter valid otp']);
            }

        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }
}
