<?php

namespace Database\Factories;

use App\Models\Master\OtherFee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OtherFeeFactory extends Factory
{
    protected $model = OtherFee::class;

    public function definition()
    {
        $feeTypes = [
            'Library Fee',
            'Sports Fee',
            'Laboratory Fee',
            'Computer Lab Fee',
            'Cultural Activity Fee',
            'Magazine Fee',
            'Identity Card Fee',
            'Examination Fee',
            'Transport Fee',
            'Development Fee'
        ];

        return [
            'fee_type' => $this->faker->randomElement($feeTypes),
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'created_by' => User::factory()
        ];
    }
}