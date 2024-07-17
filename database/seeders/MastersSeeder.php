<?php

namespace Database\Seeders;

use App\Models\Clas;
use App\Models\Designation;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Shift;
use App\Models\Ward;
use App\Models\WeekDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MastersSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Shift Seeder
        $shifts = [
            [
                'id' => 1,
                'name' => 'General Shift',
                'from_time' => '09:45:00',
                'to_time' => '18:30:00',
            ],
            [
                'id' => 2,
                'name' => 'First Shift',
                'from_time' => '06:00:00',
                'to_time' => '14:00:00',
            ],
            [
                'id' => 3,
                'name' => 'Second Shift',
                'from_time' => '14:00:00',
                'to_time' => '22:00:00',
            ],
            [
                'id' => 4,
                'name' => 'Night Shift',
                'from_time' => '22:00:00',
                'to_time' => '06:00:00',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::updateOrCreate([
                'id' => $shift['id']
            ], [
                'id' => $shift['id'],
                'name' => $shift['name'],
                'from_time' => $shift['from_time'],
                'to_time' => $shift['to_time']
            ]);
        }


        // Class Seeder
        $classes = [
            [
                'id' => 1,
                'name' => 'CLASS 1',
                'initial' => 'CL 1',
            ],
            [
                'id' => 2,
                'name' => 'CLASS 2',
                'initial' => 'CL 2',
            ],
            [
                'id' => 3,
                'name' => 'CLASS 3',
                'initial' => 'CL 3',
            ],
            [
                'id' => 4,
                'name' => 'CLASS 4',
                'initial' => 'CL 4',
            ],
        ];

        foreach ($classes as $class) {
            Clas::updateOrCreate([
                'id' => $class['id']
            ], [
                'id' => $class['id'],
                'name' => $class['name'],
                'initial' => $class['initial']
            ]);
        }


        // Ward Seeder
        $wards = [
            [
                'id' => 1,
                'name' => 'AD.COM',
                'initial' => 'ADC',
            ],
            [
                'id' => 2,
                'name' => 'COMMISIONER',
                'initial' => 'CO',
            ],
            [
                'id' => 3,
                'name' => 'General Administration Department',
                'initial' => 'GAD',
            ],
            [
                'id' => 4,
                'name' => 'HEAD OFFICE',
                'initial' => 'HO',
            ],
            [
                'id' => 5,
                'name' => 'T.D.O.',
                'initial' => 'TO',
            ],
            [
                'id' => 6,
                'name' => 'TOWN PLANNING',
                'initial' => 'TP',
            ]
        ];

        foreach ($wards as $ward) {
            Ward::updateOrCreate([
                'id' => $ward['id']
            ], [
                'id' => $ward['id'],
                'name' => $ward['name'],
                'initial' => $ward['initial']
            ]);
        }


        // Designation Seeder
        $designation = [
            [
                'id' => 1,
                'name' => 'Assistant Teacher',
                'initial' => 'AT',
            ],
            [
                'id' => 2,
                'name' => 'Clerk',
                'initial' => 'CK',
            ],
            [
                'id' => 3,
                'name' => 'Education Extension Officer',
                'initial' => 'EEO',
            ],
            [
                'id' => 4,
                'name' => 'Head Master',
                'initial' => 'HM',
            ],
            [
                'id' => 5,
                'name' => 'Peon',
                'initial' => 'PN',
            ],
            [
                'id' => 6,
                'name' => 'Aaya',
                'initial' => 'AY',
            ],
            [
                'id' => 7,
                'name' => 'Account Clerk',
                'initial' => 'AC',
            ],
            [
                'id' => 8,
                'name' => 'Accounts Officer',
                'initial' => 'AO',
            ],
        ];

        foreach ($designation as $des) {
            Designation::updateOrCreate([
                'id' => $des['id']
            ], [
                'id' => $des['id'],
                'name' => $des['name'],
                'initial' => $des['initial']
            ]);
        }



        // Leave Type Seeder
        $leaveTypes = [
            [
                'id' => 1,
                'name' => 'Technical',
                'initial' => 'TL',
                'is_paid' => 'yes',
            ],
            [
                'id' => 2,
                'name' => 'Outpost',
                'initial' => 'OT',
                'is_paid' => 'yes',
            ],
            [
                'id' => 3,
                'name' => 'Complimentry',
                'initial' => 'CT',
                'is_paid' => 'yes',
            ],
            [
                'id' => 4,
                'name' => 'Other Leave',
                'initial' => 'OL',
                'is_paid' => 'no',
            ],
            [
                'id' => 5,
                'name' => 'EL',
                'initial' => 'EL',
                'is_paid' => 'no',
            ],
            [
                'id' => 6,
                'name' => 'CL',
                'initial' => 'CL',
                'is_paid' => 'yes',
            ],
            [
                'id' => 7,
                'name' => 'Medical Leave',
                'initial' => 'MEL',
                'is_paid' => 'no',
            ],
        ];

        foreach ($leaveTypes as $lt) {
            LeaveType::updateOrCreate([
                'id' => $lt['id']
            ], [
                'id' => $lt['id'],
                'name' => $lt['name'],
                'initial' => $lt['initial'],
                'is_paid' => $lt['is_paid']
            ]);
        }


        // Leave Seeder
        $leaves = [
            [
                'id' => 1,
                'leave_type_id' => 5,
                'days' => 30,
                'type' => '0',
            ],
            [
                'id' => 2,
                'leave_type_id' => 6,
                'days' => 20,
                'type' => '0',
            ],
            [
                'id' => 3,
                'leave_type_id' => 7,
                'days' => 10,
                'type' => '0',
            ],
        ];

        foreach ($leaves as $l) {
            Leave::updateOrCreate([
                'id' => $l['id']
            ], [
                'id' => $l['id'],
                'leave_type_id' => $l['leave_type_id'],
                'days' => $l['days'],
                'type' => $l['type']
            ]);
        }



        // Weekday Seeder
        $weekdays = [
            [
                'id' => 1,
                'name' => 'monday',
            ],
            [
                'id' => 2,
                'name' => 'tuesday',
            ],
            [
                'id' => 3,
                'name' => 'wednesday',
            ],
            [
                'id' => 4,
                'name' => 'thursday',
            ],
            [
                'id' => 5,
                'name' => 'friday',
            ],
            [
                'id' => 6,
                'name' => 'saturday',
            ],
            [
                'id' => 7,
                'name' => 'sunday',
            ],
        ];

        foreach ($weekdays as $weekday) {
            WeekDay::updateOrCreate([
                'id' => $weekday['id']
            ], [
                'id' => $weekday['id'],
                'name' => $weekday['name']
            ]);
        }
    }
}
