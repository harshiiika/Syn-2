<?php

namespace Database\Seeders;

use App\Models\Student\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        $shifts = [
            ['name' => 'Morning', 'start_time' => '08:00', 'end_time' => '14:00', 'is_active' => true],
            ['name' => 'Afternoon', 'start_time' => '14:00', 'end_time' => '18:00', 'is_active' => true],
            ['name' => 'Evening', 'start_time' => '18:00', 'end_time' => '22:00', 'is_active' => true],
        ];

        foreach ($shifts as $shift) {
            Shift::updateOrCreate(
                ['name' => $shift['name']],
                $shift
            );
        }
    }
}