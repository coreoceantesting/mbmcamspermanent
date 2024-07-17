<?php

namespace App\Imports;

use App\Models\Designation;
use Illuminate\Support\Collection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportExcelUser implements ToModel, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }


    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // DB::beginTransaction();

        $designation = Designation::where('name', 'like', '%'.$row[2].'%')->first();
        if(!$designation)
            $designation = Designation::create(['name'=> $row[2]]);

        $user = User::whereEmpCode($row[0])->first();
        if($user)
        {
            $user->update([
                'ward_id'=> '7',
                'tenant_id'=> '1',
                'department_id'=> '55',
                'shift_id'=> '1',
                'clas_id'=> '1',
                'designation_id'=> $designation->id,
                'in_time'=> '09:45:00',
                'name'=> $row[1],
                'dob'=> '1990-01-01',
                'password'=> Hash::make('123456'),
            ]);
        }
        else
        {
            User::create([
                'ward_id'=> '7',
                'tenant_id'=> '1',
                'department_id'=> '55',
                'shift_id'=> '1',
                'clas_id'=> '1',
                'emp_code'=> $row[0],
                'designation_id'=> $designation->id,
                'in_time'=> '09:45:00',
                'name'=> $row[1],
                'dob'=> '1990-01-01',
                'password'=> Hash::make('123456'),
            ]);
        }
        // DB::commit();
    }
}
