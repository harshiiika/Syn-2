<?php

namespace Database\Factories;

use App\Models\FeesMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeesMasterFactory extends Factory
{
    protected $model = FeesMaster::class;

    public function definition(): array
    {
        return [
            'course'         => $this->faker->randomElement([
                'Impulse','Momentum','Intensity','Thrust',
                'Seedling 10th','Anthesis','Dynamic','Radical 8th',
                'Plumule 9th','Pre Radical 7th'
            ]),
            'gst_percent'    => 18,
            'classroom_fee'  => $this->faker->numberBetween(1000, 50000),
            'live_fee'       => $this->faker->numberBetween(1000, 50000),
            'recorded_fee'   => $this->faker->numberBetween(1000, 50000),
            'study_fee'      => $this->faker->numberBetween(500,  20000),
            'test_fee'       => $this->faker->numberBetween(500,  20000),
            'status'         => $this->faker->randomElement(['Active', 'Inactive']),
        ];
    }
}
