<?php

namespace Database\Seeders;

use App\Models\BlockNumber;
use App\Models\ParkingFloor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $capacityPerFloor = 50;

        $numberOfFloors = 5;

        for ($i = 1; $i <= $numberOfFloors; $i++) {
            ParkingFloor::create([
                'floor_number' => $i,
                'capacity' => $capacityPerFloor,
            ]);

            $alphabets = range('A', 'E');
            foreach ($alphabets as $alphabet) {
                BlockNumber::create([
                    'floor_id' => $i,
                    'block' => 'Blok ' . $alphabet,
                ]);
            }
        }
    }
}
