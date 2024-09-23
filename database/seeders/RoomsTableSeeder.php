<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Faker\Factory as Faker;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Room::create([
                'patient_id' => $faker->numberBetween(1, 50),
            ]);
        }
    }
}