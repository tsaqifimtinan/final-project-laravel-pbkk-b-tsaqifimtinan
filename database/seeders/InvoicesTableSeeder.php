<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use Faker\Factory as Faker;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            Invoice::create([
                'patient_id' => $faker->numberBetween(1, 50),
                'invoice_number' => $faker->unique()->numerify('INV-#####'),
                'description' => $faker->sentence,
                'total_amount' => $faker->randomFloat(2, 100, 10000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}