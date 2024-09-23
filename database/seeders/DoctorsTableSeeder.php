<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Faker\Factory as Faker;

class DoctorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            Doctor::create([
                'name' => $faker->name,
                'specialization' => $faker->word,
                'department_id' => $faker->numberBetween(1, 5),
            ]);
        }
    }
}