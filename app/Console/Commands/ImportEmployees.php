<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function App\Helpers\caseMatchTable;

class ImportEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will import all employees data';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        DB::table(caseMatchTable('employee_management_tbl'))->orderByDesc('id')->chunk(300, function($employees){
            foreach($employees as $employee)
            {
                $user = User::firstOrCreate(
                        ['emp_code'=> $employee->emp_id],
                        [
                            'tenant_id'=> '1',
                            'ward_id'=> is_numeric($employee->emp_ward_id) ? $employee->emp_ward_id : '2',
                            'department_id'=> is_numeric($employee->emp_dept_id) ? $employee->emp_dept_id : '91',
                            'sub_department_id'=> is_numeric($employee->emp_dept_id) ? $employee->emp_dept_id : '91',
                            'emp_code'=> $employee->emp_id,
                            'in_time'=> '10:00:00',
                            'name'=> $employee->emp_fname,
                            'email'=> $employee->emp_email,
                            'mobile'=> $employee->emp_mobile_no,
                            'dob'=> $employee->emp_dob != '' ? $employee->emp_dob : null,
                            'gender'=> $employee->emp_sex == 1 ? 'm' : 'f',
                            'password'=> Hash::make('password'),
                        ]
                    );

                $user->employee()->updateOrCreate([
                    'shift_id'=> 1,
                    'ward_id'=> is_numeric($employee->emp_ward_id) ? $employee->emp_ward_id : '2',
                    'clas_id'=> $employee->emp_class,
                    'designation_id'=> $employee->emp_class,
                    'doj'=> $employee->emp_doj != '' ? $employee->emp_doj : null,
                    'is_ot'=> $employee->is_ot_allow == '2' ? 'n' : 'y',
                    'is_divyang'=> $employee->is_emp_divyang == '2' ? 'n' : 'y',
                ]);
            }
        });


        $this->info('All employees imported successfully!');
        return 0;
    }
}
