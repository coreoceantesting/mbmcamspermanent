<?php

namespace App\Console\Commands;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Punch;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MedicalLeaveEntryToPunches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:medical-leave-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will add blank record of absent leave in punches table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $medicalLeaveRequests = LeaveRequest::with('user.latestWeekoff')->where(['leave_type_id'=> 7, 'to_date'=> null, 'is_approved'=> '1'])->get();

        $defaultShift = collect( config('default_data.shift_time') );
        foreach($medicalLeaveRequests as $mlRequest)
        {
            // if( $mlRequest->is_backdated == 1 )
            // {
                $firstPunchAfterLeave = DB::table('punches')
                            ->where(['emp_code'=> $mlRequest->user->emp_code, 'punch_by'=> '0'])
                            ->whereDate('punch_date', '>', Carbon::parse($mlRequest->from_date)->toDateString())
                            ->orderBy('punch_date')->first();

                if($firstPunchAfterLeave)
                {
                    $daysCount = Carbon::parse($mlRequest->from_date)->diffInDays( Carbon::parse($firstPunchAfterLeave->punch_date)->toDateString() );

                    // if( Punch::where(['emp_code'=> $mlRequest->user->emp_code, 'punch_date'=> Carbon::parse($mlRequest->from_date)->toDateString(), 'punch_by'=> '2'])->exists() )
                    //     Punch::where(['emp_code'=> $mlRequest->user->emp_code, 'punch_date'=> Carbon::parse($mlRequest->from_date)->toDateString(), 'punch_by'=> '2'])->delete();

                    $dateRanges = CarbonPeriod::create( Carbon::parse($mlRequest->from_date)->toDateString(), Carbon::parse($firstPunchAfterLeave->punch_date)->subDay()->toDateString() )->toArray();
                    foreach($dateRanges as $dateRange)
                    {
                        $allotedTime = '';
                        if($mlRequest->user->latestWeekoff)
                            $allotedTime = Carbon::parse($mlRequest->user->latestWeekoff->end_of_week)->gt($dateRange->toDateString()) ? $mlRequest->user->latestWeekoff->shift_in_time : $mlRequest->user->in_time;
                        else
                            $allotedTime = $mlRequest->user->in_time ?? $defaultShift['from_time'];
                        $allotedTime = $allotedTime ?? $defaultShift['from_time'];

                        if( !Punch::where('emp_code', $mlRequest->user->emp_code)->where('punch_date', Carbon::parse($dateRange)->toDateString())->exists() )
                        {
                            Punch::create([
                                'emp_code'=> $mlRequest->user->emp_code,
                                'device_id'=> '0',
                                'check_in'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($dateRange)->toDateString().' '.$allotedTime)->toDateTimeString(),
                                'check_out'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($dateRange)->toDateString().' '.$allotedTime)->addSeconds(32400)->toDateTimeString() ,
                                'duration'=> Carbon::parse($allotedTime)->diffInSeconds( Carbon::parse($allotedTime)->addHours(9) ),
                                'punch_date'=> Carbon::parse($dateRange)->toDateString(),
                                'is_latemark'=> '0',
                                'is_latemark_updated'=> '1',
                                'punch_by'=> '2',
                                'type'=> '1',
                                'leave_type_id'=> '7',
                                'is_paid'=> LeaveType::IS_MEDICAL_LEAVE_PAID,
                            ]);
                        }
                    }

                    $mlRequest->to_date = Carbon::parse($firstPunchAfterLeave->punch_date)->toDateString();
                    $mlRequest->no_of_days = $daysCount;
                    $mlRequest->save();

                    DB::table('punches as p1')->where('p1.emp_code', $mlRequest->user->emp_code)
                        ->join(DB::raw('
                            (SELECT punch_date, emp_code, MIN(id) AS min_id
                            FROM punches
                            GROUP BY punch_date, emp_code
                            HAVING COUNT(*) > 1
                            ) as p2'), function ($join) {
                                $join->on('p1.punch_date', '=', 'p2.punch_date')
                                    ->on('p1.emp_code', '=', 'p2.emp_code')
                                    ->where('p1.id', '>', DB::raw('p2.min_id'));
                        })->delete();
                }
                else
                {
                    $daysCount = Carbon::parse($mlRequest->from_date)->diffInDays( Carbon::today()->toDateString() );
                    $dateRanges = CarbonPeriod::create( Carbon::parse($mlRequest->from_date)->toDateString(), Carbon::today()->subDay()->toDateString() )->toArray();
                    foreach($dateRanges as $dateRange)
                    {
                        $allotedTime = '';
                        if($mlRequest->user->latestWeekoff)
                            $allotedTime = Carbon::parse($mlRequest->user->latestWeekoff->end_of_week)->gt($dateRange->toDateString()) ? $mlRequest->user->latestWeekoff->shift_in_time : $mlRequest->user->in_time;
                        else
                            $allotedTime = $mlRequest->user->in_time ?? $defaultShift['from_time'];
                        $allotedTime = $allotedTime ?? $defaultShift['from_time'];

                        if( !Punch::where('emp_code', $mlRequest->user->emp_code)->where('punch_date', Carbon::parse($dateRange)->toDateString())->exists() )
                        {
                            Punch::create([
                                'emp_code'=> $mlRequest->user->emp_code,
                                'device_id'=> '0',
                                'check_in'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($dateRange)->toDateString().' '.$allotedTime)->toDateTimeString() ,
                                'check_out'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($dateRange)->toDateString().' '.$allotedTime)->addSeconds(32400)->toDateTimeString() ,
                                'duration'=> Carbon::parse($allotedTime)->diffInSeconds( Carbon::parse($allotedTime)->addHours(9) ),
                                'punch_date'=> Carbon::parse($dateRange)->toDateString(),
                                'is_latemark'=> '0',
                                'is_latemark_updated'=> '1',
                                'punch_by'=> '2',
                                'type'=> '1',
                                'leave_type_id'=> '7',
                                'is_paid'=> LeaveType::IS_MEDICAL_LEAVE_PAID,
                            ]);
                        }
                    }
                }
            // }
            // else
            // {
            //     $hasPunchedToday = DB::table('punches')->where(['emp_code'=> $mlRequest->user->emp_code, 'punch_date'=> Carbon::today()->toDateString()])->first();

            //     if($hasPunchedToday)
            //     {
            //         $daysCount = Carbon::parse($mlRequest->from_date)->diffInDays( Carbon::parse($firstPunchAfterLeave->punch_date)->toDateString() );

            //         if( Punch::where(['emp_code'=> $mlRequest->user->emp_code, 'punch_date'=> Carbon::parse($mlRequest->from_date)->toDateString(), 'punch_by'=> '2'])->exists() )
            //             Punch::where(['emp_code'=> $mlRequest->user->emp_code, 'punch_date'=> Carbon::parse($mlRequest->from_date)->toDateString(), 'punch_by'=> '2'])->delete();

            //         $mlRequest->to_date = Carbon::parse($firstPunchAfterLeave->punch_date)->toDateString();
            //         $mlRequest->no_of_days = $daysCount;
            //         $mlRequest->save();
            //     }

            //     if($hasPunchedToday)
            //     {
            //         $daysCount = Carbon::parse($mlRequest->from_date)->diffInDays( Carbon::parse($hasPunchedToday->punch_date)->toDateString() );
            //         $dateRanges = CarbonPeriod::create( Carbon::parse($mlRequest->from_date)->toDateString(), Carbon::parse($hasPunchedToday->punch_date)->subDay()->toDateString() )->toArray();

            //         foreach($dateRanges as $dateRange)
            //         {
            //             $allotedTime = '';
            //             if($mlRequest->user->latestWeekoff)
            //                 $allotedTime = Carbon::parse($mlRequest->user->latestWeekoff->end_of_week)->gt($dateRange->toDateString()) ? $mlRequest->user->latestWeekoff->shift_in_time : $mlRequest->user->in_time;
            //             else
            //                 $allotedTime = $mlRequest->user->in_time ?? $defaultShift['from_time'];
            //             $allotedTime = $allotedTime ?? $defaultShift['from_time'];

            //             Punch::create([
            //                 'emp_code'=> $mlRequest->user->emp_code,
            //                 'device_id'=> '0',
            //                 'check_in'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($dateRange)->toDateString().' '.$allotedTime)->toDateTimeString() ,
            //                 'check_out'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($dateRange)->toDateString().' '.$allotedTime)->addSeconds(16200)->toDateTimeString() ,
            //                 'duration'=> Carbon::parse($allotedTime)->diffInSeconds( Carbon::parse($allotedTime)->addHours(9) ),
            //                 'punch_date'=> Carbon::parse($dateRange)->toDateString(),
            //                 'is_latemark'=> '0',
            //                 'is_latemark_updated'=> '1',
            //                 'punch_by'=> '2',
            //                 'type'=> '1',
            //                 'leave_type_id'=> '7',
            //                 'is_paid'=> LeaveType::IS_MEDICAL_LEAVE_PAID,
            //             ]);
            //         }
            //         $mlRequest->to_date = Carbon::parse($hasPunchedToday->punch_date)->toDateString();
            //         $mlRequest->no_of_days = $daysCount;
            //         $mlRequest->save();
            //     }
            // }

        }

        $this->info('Medical leave entry updated successfully!');
    }
}
