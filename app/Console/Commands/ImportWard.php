<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function App\Helpers\caseMatchTable;

class ImportWard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ward:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will import all the wards/offices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $wards = DB::table( caseMatchTable('ward_master') )->get();

        foreach($wards as $ward)
        {
            DB::table('wards')->updateOrInsert(
                ['id'=> $ward->ward_id],
                [
                    'name' => $ward->ward_name ?? '',
                    'initial' => preg_filter('/[^A-Z]/', '', ucwords($ward->ward_name)) ,
                ]
            );
        }


        $this->info('All Wards are Imported successfully!');
        return 0;
    }
}
