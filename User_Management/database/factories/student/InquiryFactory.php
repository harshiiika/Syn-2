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
            'course_name'        => $this->faker->randomElement(['Anthesis 11th NEET', 'Anthesis 12th NEET', 'JEE Mains']),
            'delivery_mode'      => $this->faker->randomElement(['Online', 'Offline', 'Hybrid']),
            'course_content'     => $this->faker->randomElement(['Class Room Course', 'Online Course', 'Crash Course']),
            'branch'             => $this->faker->randomElement(['Branch 1', 'Branch 2', 'Branch 3']),
            'state'              => $this->faker->state(),
            'city'               => $this->faker->city(),
            'address'            => $this->faker->address(),
            'ews'                => $this->faker->randomElement(['Yes', 'No']),
            'defense'            => $this->faker->randomElement(['Yes', 'No']),
            'specially_abled'    => $this->faker->randomElement(['Yes', 'No']),
            'status'             => $this->faker->randomElement(['Pending', 'Active', 'Closed', 'Converted']),
        ];
    }
}