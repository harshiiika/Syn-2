<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Master\Courses;
use App\Models\Session\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursesFeatureTest extends TestCase
{
    // use RefreshDatabase; // Uncomment if your MongoDB testing setup allows

    protected function setUp(): void
    {
        parent::setUp();
        Courses::truncate();
        User::truncate();
    }

    /**
     * @test
     */
    // public function authenticated_user_can_view_courses_index()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->get(route('courses.index'));

    //     $response->assertStatus(200);
    //     $response->assertViewIs('courses.index');
    // }

    /**
     * @test
     */
    public function it_creates_a_course_successfully()
    {
        $user = User::factory()->create();

        $data = [
            'course_name' => 'Mathematics',
            'course_type' => 'Core',
            'class_name'  => '10A',
            'course_code' => 'MATH101',
            'subjects'    => ['Algebra', 'Geometry'],
            'status'      => 'active',
        ];

        $response = $this->actingAs($user)
                         ->post(route('courses.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Course created successfully!');

        $this->assertDatabaseHas('courses', [
            'course_name' => 'Mathematics',
            'course_code' => 'MATH101',
        ]);
    }

    /**
     * @test
     */
    public function it_validates_required_fields_on_create()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), []);

        $response->assertSessionHasErrors([
            'course_name', 'course_type', 'class_name', 'course_code', 'subjects', 'status'
        ]);
    }

    /**
     * @test
     */
    public function it_displays_edit_page_for_existing_course()
{
    $user = User::factory()->create();

    $course = Courses::create([
        'course_name' => 'Science',
        'course_type' => 'Elective',
        'class_name'  => '8B',
        'course_code' => 'SCI102',
        'subjects'    => ['Physics', 'Chemistry'],
        'status'      => 'active',
    ]);

    $response = $this->actingAs($user)->get(route('courses.edit', $course->_id));

    $response->assertStatus(200);

$response->assertViewIs('master.courses.index');

    $response->assertViewHas('course', function ($viewCourse) use ($course) {
        return $viewCourse->_id == $course->_id;
    });
}


    /**
     * @test
     */
    public function it_updates_a_course_successfully()
    {
        $user = User::factory()->create();

        $course = Courses::create([
            'course_name' => 'Biology',
            'course_type' => 'Core',
            'class_name'  => '9C',
            'course_code' => 'BIO103',
            'subjects'    => ['Botany', 'Zoology'],
            'status'      => 'active',
        ]);

        $updateData = [
            'course_name' => 'Advanced Biology',
            'course_type' => 'Core',
            'class_name'  => '9C',
            'course_code' => 'BIO103',
            'subjects'    => ['Botany', 'Anatomy'],
            'status'      => 'inactive',
        ];

        $response = $this->actingAs($user)
                 ->put(route('courses.update', $course->_id), $updateData);


        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Course updated successfully!');

        $this->assertDatabaseHas('courses', [
            'course_name' => 'Advanced Biology',
            'status'      => 'inactive',
        ]);
    }

    /**
     * @test
     */
    public function it_deletes_a_course_successfully()
    {
        $user = User::factory()->create();

        $course = Courses::create([
            'course_name' => 'History',
            'course_type' => 'Elective',
            'class_name'  => '7A',
            'course_code' => 'HIST104',
            'subjects'    => ['World History'],
            'status'      => 'active',
        ]);

        $response = $this->actingAs($user)
                         ->delete(route('courses.destroy', $course->_id));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Course deleted.');

        $this->assertDatabaseMissing('courses', [
            '_id' => $course->_id,
        ]);
    }
}
