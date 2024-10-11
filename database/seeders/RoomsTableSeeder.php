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
        $usedRoomNumbers = [];
        $usedPatientIds = [];

        foreach (range(1, 50) as $index) {
            do {
                $roomNumber = $faker->unique()->numberBetween(100, 199);
            } while (in_array($roomNumber, $usedRoomNumbers));

            do {
                $patientId = $faker->numberBetween(1, 50);
            } while (in_array($patientId, $usedPatientIds));

            $usedRoomNumbers[] = $roomNumber;
            $usedPatientIds[] = $patientId;

            Room::create([
                'patient_id' => $patientId,
                'room_type' => $faker->randomElement(['Single', 'Double', 'Suite']),
                'room_number' => $roomNumber,
            ]);
        }
    }
}