<?php

namespace App\Console\Commands;

use App\Models\Department;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function App\Helpers\caseMatchTable;

class ImportDepartment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'department:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will import all the departments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $departments = DB::table( caseMatchTable('department_t') )->get();

        foreach($departments as $department)
        {
            DB::table('departments')->updateOrInsert(
                ['id'=> $department->Department_Id],
                [
                    'department_id' => null,
                    'ward_id' => $department->ward_id,
                    'name' => $department->Department_Name ?? '',
                    'initial' => preg_filter('/[^A-Z]/', '', ucwords($department->Department_Name)) ,
                    'level' => '1',
                ]
            );
        }


        $this->info('The Department is Imported successfully!');
        return 0;
    }
}
