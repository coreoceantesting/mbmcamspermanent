<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()                // Todo: This command is not in use
    {
        $lastRecord = Punch::whereDate('punch_date', '2023-08-15')->latest()->first();
        // dd($lastRecord);
        Punch::whereDate('punch_date', '2023-08-15')->where('id', '<=', $lastRecord->id)->orderBy('id')->chunk(200, function($punches){

            foreach($punches as $punch)
            {
                    Punch::create([
                        'emp_code'=> $punch->emp_code,
                        'device_id'=> 0,
                        'check_in'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($punch->punch_date)->toDateString().' 10:00:00')->toDateTimeString() ,
                        'check_out'=> Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($punch->punch_date)->toDateString().' 19:00:00')->addSeconds(16200)->toDateTimeString() ,
                        'punch_date'=> Carbon::parse($punch->punch_date)->toDateString(),
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

        $this->info('successfully!');
    }
}
