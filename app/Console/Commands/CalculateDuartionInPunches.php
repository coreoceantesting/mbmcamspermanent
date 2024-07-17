<?php

namespace App\Console\Commands;

use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateDuartionInPunches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'punches:calculate-duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will calculate duration in punches table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Punch::
            where('duration', '0')
            ->take(500)
                ->orderByDesc('id')->chunk(100, function($zeroDurationPunches){
                    foreach($zeroDurationPunches as $punch)
                    {
                        if( $punch->check_in != '0000-00-00 00:00:00' && $punch->check_out != null )
                            $punch->duration = Carbon::parse( $punch->check_in )->diffInSeconds( $punch->check_out );

                        $punch->is_duration_updated = 1;
                        $punch->save();
                    }
                });

        $this->info('The command executed successfully!');
    }
}
