<?php

namespace App\Console\Commands;

use App\Models\EmployeeShift;
use App\Models\Punch;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetLatemarkInPunches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:set-latemark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update employee late mark based on their shift time and grace period allowed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = Setting::getValues( 1 )->pluck('value', 'key');
        $defaultShift = collect( config('default_data.shift_time') );

        $startOfDay = Carbon::today()->startOfDay();

        // Update latemark for rotational eployees if shift/roster time is changed
        EmployeeShift::query()
                        ->whereDate('updated_at', '>', Carbon::now()->subMinutes(40))
                        ->with('user:id,emp_code,is_divyang')
                        ->orderByDesc('id')
                        ->chunk(200, function($empShifts) use ($startOfDay, $settings){
                            foreach($empShifts as $empShift)
                            {
                                $punch = $empShift->punch;
                                if($punch && !ctype_alpha($empShift->in_time))
                                {
                                    $latemarkGraceTime = $empShift->user?->is_divyang == 'y' ? $settings['LATE_MARK_TIMING_DIVYANG'] : $settings['LATE_MARK_TIMING'];
                                    if(
                                        $startOfDay->diffInMinutes(substr($punch->check_in, 11)) >
                                        $startOfDay->diffInMinutes( Carbon::parse($empShift->in_time)->addMinutes($latemarkGraceTime) )
                                    )
                                    {
                                        $punch->is_latemark = 1;
                                        $punch->save();
                                    }
                                }
                            }
                        });




        // Update latemark for rotational eployees if punch time is changed
        Punch::query()
                ->whereDate('updated_at', Carbon::now()->subMinutes(40))
                ->orWhereDate('created_at', Carbon::now()->subMinutes(40))
                ->withWhereHas('user', fn ($q) => $q->select('id', 'emp_code', 'is_divyang')->where('is_rotational', 1))
                ->orderByDesc('id')
                ->chunk(200, function($punches) use ($startOfDay, $settings){
                    foreach($punches as $punch)
                    {
                        $empShift = $punch->empShift;
                        if($empShift && !ctype_alpha($empShift->in_time))
                        {
                            $latemarkGraceTime = $punch->user?->is_divyang == 'y' ? $settings['LATE_MARK_TIMING_DIVYANG'] : $settings['LATE_MARK_TIMING'];
                            if(
                                $startOfDay->diffInMinutes(substr($punch->check_in, 11)) >
                                $startOfDay->diffInMinutes( Carbon::parse($empShift->in_time)->addMinutes($latemarkGraceTime) )
                            )
                            {
                                $punch->is_latemark = 1;
                                $punch->save();
                            }
                        }
                    }
                });



        // Update latemark for fixed shift employee
        Punch::query()
                ->whereDate('updated_at', Carbon::now()->subMinutes(40))
                ->orWhereDate('created_at', Carbon::now()->subMinutes(40))
                ->withWhereHas('user', fn ($q) => $q->with('shift')->select('id', 'emp_code', 'is_divyang')->where('is_rotational', 0))
                ->orderByDesc('id')
                ->chunk(200, function($punches) use ($settings, $defaultShift, $startOfDay){

                        foreach($punches as $punch)
                        {
                            $allotedTime = $punch->user->shift->from_time ?? $defaultShift['from_time'];
                            $latemarkGraceTime = $punch->user?->is_divyang == 'y' ? $settings['LATE_MARK_TIMING_DIVYANG'] : $settings['LATE_MARK_TIMING'];
                            if(
                                $startOfDay->diffInMinutes(substr($punch->check_in, 11)) >
                                $startOfDay->diffInMinutes( Carbon::parse($allotedTime)->addMinutes($latemarkGraceTime) )
                            )
                            {
                                $punch->is_latemark = '1';
                                $punch->save();
                            }
                        }

                });


        $this->info('Latemark is updated on all employees successfully!');
    }
}
