<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            MastersSeeder::class,
            // DepartmentSeeder::class,
            PermissionTableSeeder::class,
            DefaultLoginUserSeeder::class,
            // FactoryDataSeeder::class,
        ]);

        // Artisan::call('department:import');
        // Artisan::call('punches:import');
        // Artisan::call('employees:import');
    }
}
