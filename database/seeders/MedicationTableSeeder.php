<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;
use Faker\Factory as Faker;


class MedicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Medication::create([
                'patient_id' => $faker->numberBetween(1, 50),
                'medication_name' => $faker->word,
                'description' => $faker->sentence,
                'medication_date' => $faker->date,
            ]);
        }
    }
}
