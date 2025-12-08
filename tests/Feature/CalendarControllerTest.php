<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Master\Holiday;
use App\Models\Master\Test;
use Carbon\Carbon;

class CalendarControllerTest extends TestCase
{
    /** @test */
    public function it_loads_calendar_index_page()
    {
        $response = $this->get(route('calendar.index'));
        $response->assertStatus(200);
        $response->assertViewIs('master.calendar.calendar');
    }

    /** @test */
    public function it_stores_a_new_holiday()
    {
        $payload = [
            'date' => '2025-10-16',
            'description' => 'National Day',
            'session_id' => '2025'
        ];

        $response = $this->postJson(route('calendar.holidays.store'), $payload);


        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Holiday added successfully',
                 ]);

        $this->assertDatabaseHas('holidays', [
            'description' => 'National Day'
        ]);
    }

    /** @test */
    public function it_stores_a_new_test()
    {
        $payload = [
            'date' => '2025-10-16',
            'description' => 'Science Test',
            'test_name' => 'Midterm Test',
            'session_id' => '2025'
        ];

        $response = $this->postJson(route('calendar.tests.store'), $payload);


        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Test added successfully',
                 ]);

        $this->assertDatabaseHas('tests', [
            'description' => 'Science Test'
        ]);
    }

    /** @test */
    public function it_marks_sundays_as_holidays()
    {
        $payload = ['year' => 2025, 'month' => 10];

        $response = $this->postJson(route('calendar.mark.sundays'), $payload);


        $response->assertSuccessful();
        $this->assertTrue($response->json('success'));
    }

    /** @test */
    public function it_fetches_events()
    {
        Holiday::create([
            'date' => Carbon::parse('2025-10-16'),
            'description' => 'Test Holiday',
            'type' => 'holiday',
        ]);

        Test::create([
            'date' => Carbon::parse('2025-10-20'),
            'description' => 'Unit Test Exam',
            'test_name' => 'Finals'
        ]);

        $response = $this->getJson(route('calendar.events'));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);
    }
}
