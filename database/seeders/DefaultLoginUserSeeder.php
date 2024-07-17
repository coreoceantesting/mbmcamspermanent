<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultLoginUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tenant = Tenant::updateOrCreate([
            'name'=> 'Mbmc',
        ],[
            'name'=> 'Mbmc',
            'address'=> 'Thane - 401 107',
        ]);

        // Maker Seeder ##
        $makerRole = Role::updateOrCreate(['name'=> 'Maker', 'tenant_id'=> '1']);
        $permissions = Permission::pluck('id','id')->whereIn('id', [57,58,59,60,61,62,63,64,65])->all();
        $makerRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'mbmcmaker@gmail.com'
        ],[
            'department_id' => '1',
            'sub_department_id' => '3',
            'emp_code' => mt_rand(111111,999999),
            'dob' => Carbon::parse('1-1-2000'),
            'gender' => 'm',
            'name' => 'Maker',
            'email' => 'mbmcmaker@gmail.com',
            'mobile' => '9876598765',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'is_employee' => '1',
        ]);
        DB::table('model_has_roles')->updateOrInsert([
            'model_type'=> 'App\Models\User',
            'model_id'=> $user->id,
        ],[
            'role_id'=> $makerRole->id,
            'tenant_id'=> $tenant->id
        ]);



        // Super Admin Seeder ##
        $superAdminRole = Role::updateOrCreate(['name' => 'Admin','tenant_id' => 1]);
        $permissions = Permission::pluck('id','id')->all();
        $superAdminRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'corebio@gmail.com'
        ],[
            'department_id' => '1',
            'sub_department_id' => '3',
            'emp_code' => mt_rand(111111,999999),
            'dob' => Carbon::parse('1-1-2000'),
            'gender' => 'm',
            'name' => 'Core Ocean',
            'email' => 'corebio@gmail.com',
            'mobile' => '9999999999',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'is_employee' => '1',
        ]);
        // $user->assignRole([$adminRole->id]);
        DB::table('model_has_roles')->updateOrInsert([
            'model_type'=> 'App\Models\User',
            'model_id'=> $user->id,
        ],[
            'role_id'=> $superAdminRole->id,
            'tenant_id'=> $tenant->id
        ]);




        // Admin Seeder ##
        $adminRole = Role::updateOrCreate(['name' => 'Admin','tenant_id' => 1]);
        $permissions = Permission::whereNotBetween('id', [44, 47])->pluck('id','id')->all();
        $adminRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'mbmcadmin@gmail.com'
        ],[
            'department_id' => '1',
            'sub_department_id' => '3',
            'emp_code' => mt_rand(111111,999999),
            'dob' => Carbon::parse('1-1-2000'),
            'gender' => 'm',
            'name' => 'Mbmc Admin',
            'email' => 'mbmcadmin@gmail.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'is_employee' => '1',
        ]);
        DB::table('model_has_roles')->updateOrInsert([
            'model_type'=> 'App\Models\User',
            'model_id'=> $user->id,
        ],[
            'role_id'=> $adminRole->id,
            'tenant_id'=> $tenant->id
        ]);



        // Mbmc HOD Seeder ##
        $HodRole = Role::updateOrCreate(['name' => 'HOD/checker','tenant_id' => 1]);
        $permissions = Permission::pluck('id','id')->all();
        $HodRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'mbmchod@gmail.com'
        ],[
            'department_id' => '1',
            'sub_department_id' => '3',
            'emp_code' => mt_rand(111111,999999),
            'dob' => Carbon::parse('1-1-2000'),
            'gender' => 'm',
            'name' => 'Mbmc Hod',
            'email' => 'mbmchod@gmail.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'is_employee' => '1',
        ]);
        DB::table('model_has_roles')->updateOrInsert([
            'model_type'=> 'App\Models\User',
            'model_id'=> $user->id,
        ],[
            'role_id'=> $adminRole->id,
            'tenant_id'=> $tenant->id
        ]);
    }
}
