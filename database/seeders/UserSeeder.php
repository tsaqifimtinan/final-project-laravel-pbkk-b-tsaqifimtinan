<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $doctorRole = Role::where('name', 'doctor')->first();
        $patientRole = Role::where('name', 'patient')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole($adminRole);

        User::create([
            'name' => 'Doctor',
            'email' => 'doctor@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole($doctorRole);

        User::create([
            'name' => 'Patient',
            'email' => 'patient@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole($patientRole);
    }
}
