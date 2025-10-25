<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Master\Courses;

class CourseTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_attributes()
    {
        $course = new Courses([
            'course_name' => 'Mathematics',
            'course_type' => 'Core',
            'class_name'  => '10A',
            'course_code' => 'MATH101',
            'subjects'    => ['Algebra', 'Geometry'],
            'status'      => 'active',
        ]);

        $this->assertInstanceOf(Courses::class, $course);
        $this->assertEquals('Mathematics', $course->course_name);
    }

    /** @test */
    public function it_has_correct_fillable_fields()
    {
        $expected = [
            'course_name',
            'course_type',
            'class_name',
            'course_code',
            'subjects',
            'status',
        ];

        $course = new Courses();

        $this->assertEqualsCanonicalizing($expected, $course->getFillable());
    }

    /** @test */
    public function it_casts_subjects_to_array()
    {
        $course = new Courses([
            'subjects' => ['Physics', 'Chemistry'],
        ]);

        $this->assertIsArray($course->subjects);
        $this->assertContains('Physics', $course->subjects);
    }

    /** @test */
    public function it_uses_the_mongodb_connection_and_courses_collection()
    {
        $course = new Courses();

        $this->assertEquals('mongodb', $course->getConnectionName());
        $this->assertEquals('courses', $course->getTable());
    }
}
