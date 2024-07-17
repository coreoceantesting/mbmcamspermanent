<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $departments = [
            [
                'id' => 1,
                'department_id' => null,
                'name' => 'Computer Department',
                'initial' => 'CD',
                'level' => '1',
            ],
            [
                'id' => 2,
                'department_id' => null,
                'name' => 'Accounts Department',
                'initial' => 'AD',
                'level' => '1',
            ],
            [
                'id' => 3,
                'department_id' => 1,
                'name' => 'Support Department',
                'initial' => 'SD',
                'level' => '2',
            ],
            [
                'id' => 4,
                'department_id' => 1,
                'name' => 'Technical Department',
                'initial' => 'TD',
                'level' => '2',
            ]
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate([
                'id' => $department['id']
            ], [
                'id' => $department['id'],
                'department_id' => $department['department_id'],
                'name' => $department['name'],
                'initial' => $department['initial'],
                'level' => $department['level'],
            ]);
        }


    }
}
