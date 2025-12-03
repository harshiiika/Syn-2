<?php

namespace Database\Factories;

use App\Models\Master\Scholarship;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipFactory extends Factory
{
    protected $model = Scholarship::class;

    public function definition()
    {
        return [
            'scholarship_type' => $this->faker->randomElement(Scholarship::getTypes()),
            'scholarship_name' => $this->faker->words(2, true),
            'short_name' => strtoupper($this->faker->lexify('??')),
            'category' => $this->faker->randomElement(Scholarship::getCategories()),
            'applicable_for' => $this->faker->randomElement(Scholarship::getApplicableFor()),
            'description' => $this->faker->sentence(),
            'status' => 'active',
        ];
    }
}
