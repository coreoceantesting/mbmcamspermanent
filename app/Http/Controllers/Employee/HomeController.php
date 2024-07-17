<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\Punch;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = '';
        $toDate = '';

        if($request->month && $request->year)
        {
            $settings = Setting::getValues( auth()->user()->tenant_id )->pluck('value', 'key');
            $fromDate = Carbon::parse($request->year.'-'.$request->month.'-'.$settings['PAYROLL_DATE']);
            $toDate = clone($fromDate);
            $fromDate = (string) $fromDate->subMonth()->toDateString();
            $toDate = (string) $toDate->subDay()->toDateString();
        }
        else
        {
            $settings = Setting::getValues( auth()->user()->tenant_id )->pluck('value', 'key');
            $fromDate = Carbon::parse( Carbon::today()->format('Y-m-') .$settings['PAYROLL_DATE']);
            $toDate = clone($fromDate);
            $fromDate = (string) $fromDate->subMonth()->toDateString();
            $toDate = (string) $toDate->subDay()->toDateString();
        }

        $authUser = Auth::user();
        $punches = Punch::query()
                    ->select('id', 'emp_code', 'check_in', 'check_out', 'punch_date', 'is_latemark', 'type', 'leave_type_id', 'is_paid', 'duration', 'punch_by')
                    ->whereDate('punch_date', '>=', $fromDate)->whereDate('punch_date', '<=', $toDate)
                    ->where('emp_code', $authUser->emp_code)
                    ->where('punch_by', '0')
                    ->orderByDesc('punch_date')
                    ->get();

        $todaysData = Punch::whereDate('punch_date', Carbon::today()->toDateString())->where('emp_code',$authUser->emp_code)->where('punch_by', '0')->select('check_in', 'check_out', 'duration')->first();
        if($todaysData)
        {
            $data['todays_in_time'] = $todaysData->check_in ? Carbon::parse($todaysData->check_in)->format('h:i A') : '--';
            $data['todays_out_time'] = $todaysData->check_out ? ( Carbon::parse($todaysData->check_out)->gt(Carbon::parse($todaysData->check_in)->addMinutes(5)) ? Carbon::parse($todaysData->check_out)->format('h:i A') : '--' ) : '--';
        }
        else
        {
            $data['todays_in_time'] = '--';
            $data['todays_out_time'] = '--';
        }

        $absendDays = 0;
        $actualPresent = 0;
        $calendarData = [];
        $data['total_present'] = 0;
        $data['total_absent'] = 0;
            foreach ($punches as $punch)
            {
                if($punch->check_in)
                {
                    array_push($calendarData, array(
                        'date'=> Carbon::parse($punch->punch_date)->format('d-m-Y'),
                        'check_in'=> Carbon::parse($punch->check_in)->format('h:i A'),
                        // 'check_out'=> $punch->check_out ? Carbon::parse($punch->check_out)->format('h:i A') : '----',
                        'check_out'=> $punch->check_out ? ( Carbon::parse($punch->check_out)->gt(Carbon::parse($punch->check_in)->addMinutes(5)) ? Carbon::parse($punch->check_out)->format('h:i A') : '----' ) : '----',
                    ));
                    $actualPresent++;
                }
                else
                    $absendDays++;

                $data['total_present'] = $actualPresent;
                $data['total_absent'] = $absendDays;
            }
        $data['total_leave'] = $punches->where('punch_by', 2)->count();

        $data['attendance'] = $calendarData;


        return view('employee.home')->with(['data'=> $data]);
    }

    public function logout()
    {
        auth()->guard('employee')->logout();

        return redirect()->route('login', ['device_type'=> 'mobile']);
    }

    public function privacyPolicy()
    {
        return view('employee.privacy-policy');
    }

    public function showChangePassword()
    {
        return view('employee.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->passes())
        {
            $old_password = $request->old_password;
            $password = $request->password;

            try
            {
                $user = DB::table('app_users')->where('id', $request->user()->id)->first();

                if( Hash::check($old_password, $user->password) )
                {
                    DB::table('app_users')->where('id', $request->user()->id)->update(['password'=> Hash::make($password)]);

                    return response()->json(['success'=> 'Password changed successfully!']);
                }
                else
                {
                    return response()->json(['error2'=> 'Old password does not match']);
                }
            }
            catch(\Exception $e)
            {
                DB::rollBack();
                Log::info("password change error:". $e);
                return response()->json(['error2'=> 'Something went wrong while changing your password!']);
            }
        }
        else
        {
            return response()->json(['error'=>$validator->errors()]);
        }
    }

    public function deleteAccount(Request $request)
    {
        User::where('id', auth()->user()->id)->delete();

        auth()->guard('employee')->logout();

        return response()->json(['success'=> 'Account deleted successfully!']);
    }
}
