<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use Faker\Factory as Faker;

class AppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Appointment::create([
                'patient_id' => $faker->numberBetween(1, 50),
                'doctor_id' => $faker->numberBetween(1, 20),
                'appointment_date' => $faker->dateTime,
                'notes' => $faker->sentence,
            ]);
        }
    }
}