<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateEmployeeTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-employee-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will transfer all data from employee to users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('app_employees')->orderByDesc('id')->chunk(100, function($employees){
            foreach($employees as $emp)
            {
                DB::table('app_users')->where('id', $emp->user_id)->update([
                    'shift_id'=> $emp->shift_id,
                    'clas_id'=> $emp->clas_id,
                    'designation_id'=> $emp->designation_id,
                    'doj'=> $emp->doj,
                    'is_ot'=> $emp->is_ot,
                    'is_divyang'=> $emp->is_divyang,
                    'permanent_address'=> $emp->permanent_address,
                    'present_address'=> $emp->present_address,
                    'is_employee'=> 1,
                ]);
            }
        });

        $this->info('Command completed successfully!');
    }
}
