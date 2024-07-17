<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetComplimentaryLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:complimentary-leave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will add complimentry leave of punches marked on holiday';

    /**
     * Execute the console command.
     */
    public function handle()        // Todo: This command is not being used
    {
        $todaysDate = Carbon::today()->toDateString();
        $isHoliday = Holiday::whereDate('date', $todaysDate)->where('year', date('Y'))->first();

        if(!$isHoliday)
        {
            $this->info('Date updated successfully!');
            return true;
        }

        Punch::where('punch_date', $todaysDate)->orderByDesc('id')->chunk(50, function($punches) use ($todaysDate){

            foreach($punches as $punch)
            {
                    Punch::create([
                        'emp_code'=> $punch->emp_code,
                        'device_id'=> 0,
                        'check_in'=> Carbon::createFromFormat('Y-m-d H:i:s', $todaysDate.' 10:00:00')->toDateTimeString() ,
                        'check_out'=> Carbon::createFromFormat('Y-m-d H:i:s', $todaysDate.' 19:00:00')->addSeconds(16200)->toDateTimeString() ,
                        'punch_date'=> $todaysDate,
                        'duration'=> 32400,
                        'punch_by'=> Punch::PUNCH_BY_ADJUSTMENT,
                        'type'=> Punch::PUNCH_TYPE_LEAVE,
                        'leave_type_id'=> 3,
                        'is_latemark'=> '0',
                        'is_latemark_updated'=> '1',
                        'is_duration_updated'=> '1',
                        'is_paid'=> Punch::PUNCH_IS_PAID,
                        'created_at'=> $todaysDate,
                        'updated_at'=> $todaysDate,
                    ]);
            }
        });


        $this->info('Date updated!');
    }
}
