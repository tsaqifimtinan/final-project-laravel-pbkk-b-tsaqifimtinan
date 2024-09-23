<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $departments = [
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Pediatrics',
            'Radiology'
        ];

        foreach ($departments as $department) {
            Department::create([
                'name' => $department,
                'description' => 'Description for ' . $department,
            ]);
        }
    }
}