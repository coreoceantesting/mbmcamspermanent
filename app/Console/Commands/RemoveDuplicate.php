<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will remove old entry of same same date from punches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('punches as p1')
        ->where('punch_by', '2')
        ->join(DB::raw('
            (SELECT punch_date, emp_code, MAX(id) AS min_id
            FROM punches
            GROUP BY punch_date, emp_code
            HAVING COUNT(*) > 1
            ) as p2'), function ($join) {
                $join->on('p1.punch_date', '=', 'p2.punch_date')
                    ->on('p1.emp_code', '=', 'p2.emp_code')
                    ->where('p1.id', '<', DB::raw('p2.min_id'));
        })
        ->delete();

        $this->info('Command executed successfully!');
    }
}
