<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'id' => 1,
                'name' => 'dashboard.view',
                'group' => 'dashboard',
            ],
            [
                'id' => 2,
                'name' => 'users.view',
                'group' => 'users',
            ],
            [
                'id' => 3,
                'name' => 'users.create',
                'group' => 'users',
            ],
            [
                'id' => 4,
                'name' => 'users.edit',
                'group' => 'users',
            ],
            [
                'id' => 5,
                'name' => 'users.delete',
                'group' => 'users',
            ],
            [
                'id' => 6,
                'name' => 'users.toggle_status',
                'group' => 'users',
            ],
            [
                'id' => 7,
                'name' => 'users.change_password',
                'group' => 'users',
            ],
            [
                'id' => 8,
                'name' => 'departments.view',
                'group' => 'departments',
            ],
            [
                'id' => 9,
                'name' => 'departments.create',
                'group' => 'departments',
            ],
            [
                'id' => 10,
                'name' => 'departments.edit',
                'group' => 'departments',
            ],
            [
                'id' => 11,
                'name' => 'departments.delete',
                'group' => 'departments',
            ],
            [
                'id' => 12,
                'name' => 'sub-departments.view',
                'group' => 'sub-departments',
            ],
            [
                'id' => 13,
                'name' => 'sub-departments.create',
                'group' => 'sub-departments',
            ],
            [
                'id' => 14,
                'name' => 'sub-departments.edit',
                'group' => 'sub-departments',
            ],
            [
                'id' => 15,
                'name' => 'sub-departments.delete',
                'group' => 'sub-departments',
            ],
            [
                'id' => 16,
                'name' => 'wards.view',
                'group' => 'wards',
            ],
            [
                'id' => 17,
                'name' => 'wards.create',
                'group' => 'wards',
            ],
            [
                'id' => 18,
                'name' => 'wards.edit',
                'group' => 'wards',
            ],
            [
                'id' => 19,
                'name' => 'wards.delete',
                'group' => 'wards',
            ],
            [
                'id' => 20,
                'name' => 'classes.view',
                'group' => 'classes',
            ],
            [
                'id' => 21,
                'name' => 'classes.create',
                'group' => 'classes',
            ],
            [
                'id' => 22,
                'name' => 'classes.edit',
                'group' => 'classes',
            ],
            [
                'id' => 23,
                'name' => 'classes.delete',
                'group' => 'classes',
            ],
            [
                'id' => 24,
                'name' => 'designations.view',
                'group' => 'designations',
            ],
            [
                'id' => 25,
                'name' => 'designations.create',
                'group' => 'designations',
            ],
            [
                'id' => 26,
                'name' => 'designations.edit',
                'group' => 'designations',
            ],
            [
                'id' => 27,
                'name' => 'designations.delete',
                'group' => 'designations',
            ],
            [
                'id' => 28,
                'name' => 'holidays.view',
                'group' => 'holidays',
            ],
            [
                'id' => 29,
                'name' => 'holidays.create',
                'group' => 'holidays',
            ],
            [
                'id' => 30,
                'name' => 'holidays.edit',
                'group' => 'holidays',
            ],
            [
                'id' => 31,
                'name' => 'holidays.delete',
                'group' => 'holidays',
            ],
            [
                'id' => 32,
                'name' => 'leave_types.view',
                'group' => 'leave_types',
            ],
            [
                'id' => 33,
                'name' => 'leave_types.create',
                'group' => 'leave_types',
            ],
            [
                'id' => 34,
                'name' => 'leave_types.edit',
                'group' => 'leave_types',
            ],
            [
                'id' => 35,
                'name' => 'leave_types.delete',
                'group' => 'leave_types',
            ],
            [
                'id' => 36,
                'name' => 'leaves.view',
                'group' => 'leaves',
            ],
            [
                'id' => 37,
                'name' => 'leaves.create',
                'group' => 'leaves',
            ],
            [
                'id' => 38,
                'name' => 'leaves.edit',
                'group' => 'leaves',
            ],
            [
                'id' => 39,
                'name' => 'leaves.delete',
                'group' => 'leaves',
            ],
            [
                'id' => 40,
                'name' => 'devices.view',
                'group' => 'devices',
            ],
            [
                'id' => 41,
                'name' => 'devices.create',
                'group' => 'devices',
            ],
            [
                'id' => 42,
                'name' => 'devices.edit',
                'group' => 'devices',
            ],
            [
                'id' => 43,
                'name' => 'devices.delete',
                'group' => 'devices',
            ],
            [
                'id' => 44,
                'name' => 'roles.view',
                'group' => 'roles',
            ],
            [
                'id' => 45,
                'name' => 'roles.create',
                'group' => 'roles',
            ],
            [
                'id' => 46,
                'name' => 'roles.edit',
                'group' => 'roles',
            ],
            [
                'id' => 47,
                'name' => 'roles.delete',
                'group' => 'roles',
            ],
            [
                'id' => 48,
                'name' => 'roles.assign',
                'group' => 'roles',
            ],
            [
                'id' => 49,
                'name' => 'manual-attendance.view',
                'group' => 'attendance',
            ],
            [
                'id' => 50,
                'name' => 'manual-attendance.create',
                'group' => 'attendance',
            ],
            [
                'id' => 51,
                'name' => 'manual-attendance.edit',
                'group' => 'attendance',
            ],
            [
                'id' => 52,
                'name' => 'manual-attendance.delete',
                'group' => 'attendance',
            ],
            [
                'id' => 53,
                'name' => 'employees.view',
                'group' => 'employees',
            ],
            [
                'id' => 54,
                'name' => 'employees.create',
                'group' => 'employees',
            ],
            [
                'id' => 55,
                'name' => 'employees.edit',
                'group' => 'employees',
            ],
            [
                'id' => 56,
                'name' => 'employees.delete',
                'group' => 'employees',
            ],
            [
                'id' => 57,
                'name' => 'apply-leaves.view',
                'group' => 'apply-leaves',
            ],
            [
                'id' => 58,
                'name' => 'apply-leaves.create',
                'group' => 'apply-leaves',
            ],
            [
                'id' => 59,
                'name' => 'apply-leaves.edit',
                'group' => 'apply-leaves',
            ],
            [
                'id' => 60,
                'name' => 'apply-leaves.delete',
                'group' => 'apply-leaves',
            ],
            [
                'id' => 61,
                'name' => 'apply-medical-leaves.view',
                'group' => 'apply-medical-leaves',
            ],
            [
                'id' => 62,
                'name' => 'apply-medical-leaves.create',
                'group' => 'apply-medical-leaves',
            ],
            [
                'id' => 63,
                'name' => 'apply-medical-leaves.edit',
                'group' => 'apply-medical-leaves',
            ],
            [
                'id' => 64,
                'name' => 'apply-medical-leaves.delete',
                'group' => 'apply-medical-leaves',
            ],
            [
                'id' => 65,
                'name' => 'leave-application.pending',
                'group' => 'leave-application',
            ],
            [
                'id' => 66,
                'name' => 'leave-application.approve',
                'group' => 'leave-application',
            ],
            [
                'id' => 67,
                'name' => 'leave-application.reject',
                'group' => 'leave-application',
            ],
            [
                'id' => 68,
                'name' => 'roster.view',
                'group' => 'roster',
            ],
            [
                'id' => 69,
                'name' => 'roster.create',
                'group' => 'roster',
            ],
            [
                'id' => 70,
                'name' => 'reports.month-wise',
                'group' => 'reports',
            ],
            [
                'id' => 71,
                'name' => 'reports.muster',
                'group' => 'reports',
            ],
            [
                'id' => 72,
                'name' => 'shifts.view',
                'group' => 'shifts',
            ],
            [
                'id' => 73,
                'name' => 'shifts.create',
                'group' => 'shifts',
            ],
            [
                'id' => 74,
                'name' => 'shifts.edit',
                'group' => 'shifts',
            ],
            [
                'id' => 75,
                'name' => 'shifts.delete',
                'group' => 'shifts',
            ],
            [
                'id' => 76,
                'name' => 'contractors.view',
                'group' => 'contractors',
            ],
            [
                'id' => 77,
                'name' => 'contractors.create',
                'group' => 'contractors',
            ],
            [
                'id' => 78,
                'name' => 'contractors.edit',
                'group' => 'contractors',
            ],
            [
                'id' => 79,
                'name' => 'contractors.delete',
                'group' => 'contractors',
            ],
            [
                'id' => 80,
                'name' => 'reports.leave-report',
                'group' => 'reports',
            ],
            [
                'id' => 81,
                'name' => 'reports.daily-attendance',
                'group' => 'reports',
            ],
            [
                'id' => 82,
                'name' => 'reports.today-present-report',
                'group' => 'reports',
            ],
            [
                'id' => 83,
                'name' => 'reports.today-absent-report',
                'group' => 'reports',
            ],
            [
                'id' => 84,
                'name' => 'reports.department-wise',
                'group' => 'reports',
            ],
            [
                'id' => 85,
                'name' => 'reports.emp-wise-report',
                'group' => 'reports',
            ],
            [
                'id' => 86,
                'name' => 'reports.leave-bifurcation',
                'group' => 'reports',
            ],
            [
                'id' => 87,
                'name' => 'leave-balance.index',
                'group' => 'leave-balance',
            ],
            [
                'id' => 88,
                'name' => 'leave-balance.create',
                'group' => 'leave-balance',
            ],
            [
                'id' => 89,
                'name' => 'leave-balance.edit',
                'group' => 'leave-balance',
            ],
            [
                'id' => 90,
                'name' => 'leave-balance.delete',
                'group' => 'leave-balance',
            ],
            [
                'id' => 91,
                'name' => 'reports.completed-year',
                'group' => 'reports'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'id' => $permission['id']
            ], [
                'id' => $permission['id'],
                'name' => $permission['name'],
                'group' => $permission['group']
            ]);
        }
    }
}
