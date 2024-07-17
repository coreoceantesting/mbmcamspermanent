<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Punch;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AttendanceController extends ApiController
{

    public function index(Request $request)
    {
        $fromDate = '';
        $toDate = '';

        if($request->from_date && $request->to_date)
        {
            $fromDate = Carbon::parse($request->from_date)->toDateString();
            $toDate = Carbon::parse($request->to_date)->toDateString();
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
                    ->get();

        $todaysData = Punch::where('punch_date', Carbon::today()->toDateString())->select('check_in', 'check_out', 'duration')->first();
        $data['todays_in_time'] = $todaysData->check_in ?? '--';
        $data['todays_out_time'] = $todaysData->check_out ?? '--';

        $calendarData = [];

            $holidayCount = 0;
            $absendDays = 0;
            $actualPresent = 0;
            $holidaysArray = Holiday::whereBetween('date', [$fromDate, $toDate])->pluck('date')->toArray();
            $dateRanges = CarbonPeriod::create( Carbon::parse($fromDate), Carbon::parse($toDate) )->toArray();
            $leavesArray = ['0'=> 'HALFDAY', '1'=> 'TECH', '2'=> 'OUT', '3'=> 'COMP', '4'=> 'OL', '5'=> 'EL', '6'=> 'CL', '7'=> 'MEDI'];
            foreach ($dateRanges as $dateRange)
            {
                $isWeekOff = false; //TODO: need to check weekoff on shift basis
                $clonedDate = clone($dateRange);
                $hasPunch = 0;
                if ($dateRange->isWeekend())
                    $isWeekOff = true;
                else
                    $hasPunch = $punches->where('punch_date', '>=', $clonedDate->toDateString())->where('punch_date', '<', $clonedDate->addDay()->toDateString())->first();

                if ($isWeekOff)
                {
                    array_push($calendarData, array( 'date'=> $dateRange->format('d-m-Y'), 'text'=> 'WEEKOFF', 'in_time'=> '', 'out_time'=> ''));
                }
                else
                {
                    if ( in_array( $dateRange->format('Y-m-d'), $holidaysArray ) ){
                        $holidayCount++;
                        array_push($calendarData, array( 'date'=> $dateRange->format('d-m-Y'), 'text'=> 'HOLIDAY', 'in_time'=> '', 'out_time'=> ''));
                    }
                    elseif (!$hasPunch){
                        $absendDays++;
                        array_push($calendarData, array( 'date'=> $dateRange->format('d-m-Y'), 'text'=> 'ABSENT', 'in_time'=> '', 'out_time'=> ''));
                    }
                    elseif ( $hasPunch->punch_by == '2' ){
                        array_push($calendarData, array( 'date'=> $dateRange->format('d-m-Y'), 'text'=> $leavesArray[$hasPunch->leave_type_id], 'in_time'=> '', 'out_time'=> ''));
                    }
                    elseif( $hasPunch->check_in )
                    {
                        $actualPresent++;
                        array_push($calendarData, array( 'date'=> $dateRange->format('d-m-Y'), 'text'=> '', 'in_time'=> $hasPunch->check_in, 'out_time'=> $hasPunch->check_out));
                    }
                }

                $data['total_present'] = $actualPresent;
                $data['total_absent'] = $absendDays;
                $data['total_leave'] = $punches->where('punch_by', 2)->count();
            }

        $data['attendance'] = $calendarData;

        return $this->respondWith($data);
    }


    public function indexNew(Request $request)
    {
        $fromDate = '';
        $toDate = '';

        if($request->from_date && $request->to_date)
        {
            $fromDate = Carbon::parse($request->from_date)->toDateString();
            $toDate = Carbon::parse($request->to_date)->toDateString();
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
                    ->get();

        $todaysData = Punch::whereDate('punch_date', Carbon::today()->toDateString())->where('emp_code',$authUser->emp_code)->select('check_in', 'check_out', 'duration')->first();
        $data['todays_in_time'] = $todaysData->check_in ?? '--';
        $data['todays_out_time'] = $todaysData->check_out ?? '--';

        $absendDays = 0;
        $actualPresent = 0;
        $calendarData = [];
            foreach ($punches as $punch)
            {
                if($punch->check_in)
                {
                    array_push($calendarData, array(
                        'date'=> Carbon::parse($punch->punch_date)->format('d-m-Y'),
                        'check_in'=> Carbon::parse($punch->check_in)->format('h:i A'),
                        'check_out'=> Carbon::parse($punch->check_out)->format('h:i A'),
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

        return $this->respondWith($data);
    }
}
