<?php

namespace Database\Seeders;

use App\Models\LeaveRequestHierarchy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveRequestHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $hierarchies = [
            [
                'id' => 1,
                'clas_id' => 1,
                'requester_designation_id' => '48',
                '1_approver_designation_id' => '146',
                '2_approver_designation_id' => '45',
                '3_approver_designation_id' => '149',
                '4_approver_designation_id' => null,
            ],
            [
                'id' => 2,
                'clas_id' => 1,
                'requester_designation_id' => '146',
                '1_approver_designation_id' => '45',
                '2_approver_designation_id' => '149',
                '3_approver_designation_id' => null,
                '4_approver_designation_id' => null,
            ],
            [
                'id' => 3,
                'clas_id' => 1,
                'requester_designation_id' => '45',
                '1_approver_designation_id' => '149',
                '2_approver_designation_id' => null,
                '3_approver_designation_id' => null,
                '4_approver_designation_id' => null,
            ],
            [
                'id' => 4,
                'clas_id' => 2,
                'requester_designation_id' => '48',
                '1_approver_designation_id' => '146',
                '2_approver_designation_id' => '45',
                '3_approver_designation_id' => '149',
                '4_approver_designation_id' => null,
            ],
            [
                'id' => 5,
                'clas_id' => 2,
                'requester_designation_id' => '146',
                '1_approver_designation_id' => '45',
                '2_approver_designation_id' => '149',
                '3_approver_designation_id' => null,
                '4_approver_designation_id' => null,
            ],
            [
                'id' => 6,
                'clas_id' => 2,
                'requester_designation_id' => '45',
                '1_approver_designation_id' => '149',
                '2_approver_designation_id' => null,
                '3_approver_designation_id' => null,
                '4_approver_designation_id' => null,
            ],
        ];

        foreach ($hierarchies as $hierarchy) {
            LeaveRequestHierarchy::updateOrCreate([
                'id' => $hierarchy['id']
            ], [
                'clas_id' => $hierarchy['clas_id'],
                'requester_designation_id' => $hierarchy['requester_designation_id'],
                '1_approver_designation_id' => $hierarchy['1_approver_designation_id'],
                '2_approver_designation_id' => $hierarchy['2_approver_designation_id'],
                '3_approver_designation_id' => $hierarchy['3_approver_designation_id'],
                '4_approver_designation_id' => $hierarchy['4_approver_designation_id'],
            ]);
        }


    }
}
