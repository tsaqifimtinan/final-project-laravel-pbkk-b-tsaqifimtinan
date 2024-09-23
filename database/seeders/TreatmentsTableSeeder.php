<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Treatment;
use Faker\Factory as Faker;

class TreatmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Treatment::create([
                'patient_id' => $faker->numberBetween(1, 50),
                'treatment_name' => $faker->word,
                'description' => $faker->sentence,
                'treatment_date' => $faker->date,
            ]);
        }
    }
}