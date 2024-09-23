<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            DepartmentsTableSeeder::class,
            PatientsTableSeeder::class,
            DoctorsTableSeeder::class,
            TreatmentsTableSeeder::class,
            AppointmentsTableSeeder::class,
            PrescriptionsTableSeeder::class,
            MedicationTableSeeder::class,
            InvoicesTableSeeder::class,
            PaymentsTableSeeder::class,
            RoomsTableSeeder::class,
        ]);
    }
}