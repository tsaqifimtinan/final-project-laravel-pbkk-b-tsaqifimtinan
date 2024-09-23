<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Faker\Factory as Faker;


class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Payment::create([
                'patient_id' => $faker->numberBetween(1, 50),
                'payment_method' => $faker->word,
                'description' => $faker->sentence,
                'payment_date' => $faker->date,
            ]);
        }
    }
}
