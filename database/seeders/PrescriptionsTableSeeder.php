<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescription;
use Faker\Factory as Faker;

class PrescriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Prescription::create([
                'patient_id' => $faker->numberBetween(1, 50),
                'prescription_name' => $faker->word,
                'description' => $faker->sentence,
                'prescription_date' => $faker->date,
            ]);
        }
    }
}
