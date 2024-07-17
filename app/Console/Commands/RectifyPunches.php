<?php

namespace App\Console\Commands;

use App\Models\Punch;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RectifyPunches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rectify-punches {date?} {is_cron?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will swap check-in n check-out punches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $settings = Setting::getValues( 1 )->pluck('value', 'key');
        // $defaultShift = collect( config('default_data.shift_time') );

        if($this->argument('is_cron'))
        {
            $date = Carbon::parse($this->argument('date'))->subDays(15)->toDateString();
            Punch::with('user')
                ->where('punch_by', '0')
                ->whereDate('updated_at', '>', $date)
                ->orderBy('id')->chunk(100, function($punches){
                    $this->rectify($punches);
            });
        }
        elseif($this->argument('date'))
        {
            $date = Carbon::parse($this->argument('date'))->subDays(15)->toDateString();
            Punch::with('user')
                ->where('punch_by', '0')
                ->whereDate('punch_date', '>', $date)
                ->orderBy('id')->chunk(100, function($punches){
                    $this->rectify($punches);
            });
        }
        else
        {
            Punch::with('user')
                ->where('punch_by', '0')
                ->orderBy('id')->chunk(100, function($punches){
                    $this->rectify($punches);
            });
        }


        $this->info('Command executed successfully!');
    }


    private function rectify($punches)
    {
        $timestamp = Carbon::now()->toDateTimeString();
        foreach($punches as $punch)
        {
            if($punch->check_out)
            {
                if(Carbon::parse($punch->check_out)->lt( Carbon::parse($punch->check_in) ) )
                {
                    $temp = $punch->check_out;
                    $punch->check_out = $punch->check_in;
                    $punch->check_in = $temp;
                    $punch->duration = Carbon::parse($punch->check_in)->diffInSeconds( Carbon::parse($punch->check_out) );
                    $punch->updated_at = $timestamp;
                    $punch->save();
                }
            }
        }
    }
}
