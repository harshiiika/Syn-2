<?php

namespace Database\Factories;

use App\Models\FeesMaster;
use App\Models\Course;
use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeesMasterFactory extends Factory
{
    protected $model = FeesMaster::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'session_id' => Session::factory(),
            'gst_percentage' => $this->faker->randomElement([5, 12, 18, 28]),
            'class_room_course' => $this->faker->randomFloat(2, 5000, 50000),
            'live_online_course' => $this->faker->randomFloat(2, 3000, 30000),
            'recorded_online_course' => $this->faker->randomFloat(2, 2000, 20000),
            'study_material_only' => $this->faker->randomFloat(2, 1000, 10000),
            'test_series_only' => $this->faker->randomFloat(2, 1000, 8000),
            'first_installment' => $this->faker->randomFloat(2, 2000, 15000),
            'second_installment' => $this->faker->randomFloat(2, 2000, 15000),
            'third_installment' => $this->faker->randomFloat(2, 1000, 10000),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    /**
     * Indicate that the fees master is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the fees master is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Set specific GST percentage
     */
    public function withGst(float $percentage): static
    {
        return $this->state(fn (array $attributes) => [
            'gst_percentage' => $percentage,
        ]);
    }
}