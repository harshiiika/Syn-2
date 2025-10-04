<?php

namespace Database\Factories\Student;

use App\Models\Student\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition(): array
    {
        return [
            'student_name'       => $this->faker->name(),
            'father_name'        => $this->faker->name(),
            'father_contact'     => $this->faker->numerify('9#########'),
            'father_whatsapp'    => $this->faker->numerify('9#########'),
            'student_contact'    => $this->faker->numerify('9#########'),
            'category'           => $this->faker->randomElement(['General', 'OBC', 'SC', 'ST']),
            'state'              => $this->faker->state(),
            'city'               => $this->faker->city(),
            'address'            => $this->faker->address(),
            'branch_name'        => $this->faker->randomElement(['CSE', 'ECE', 'ME', 'CE', 'EE']),
            'ews'                => $this->faker->boolean(),
            'service_background' => $this->faker->boolean(),
            'specially_abled'    => $this->faker->boolean(),
            'status'             => $this->faker->randomElement(['new', 'open', 'closed']),
        ];
    }
}
