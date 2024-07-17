<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function App\Helpers\caseMatchTable;

class ImportHoliday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holiday:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will import all holidays';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $holidays = DB::table( caseMatchTable('mst_holiday') )->get();

        foreach($holidays as $holiday)
        {
            DB::table('holidays')->updateOrInsert(
                ['id'=> $holiday->h_id],
                [
                    'year' => Carbon::parse($holiday->holiday_date ?? '2023')->format('Y'),
                    'date' => $holiday->holiday_date ?? '',
                    'remark' => $holiday->remark ,
                    'created_at' => Carbon::now()->toDateTimeString() ,
                ]
            );
        }


        $this->info('All holidays are Imported successfully!');
        return 0;
    }
}
