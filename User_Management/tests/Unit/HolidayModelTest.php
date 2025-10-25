<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Master\Holiday;
use Carbon\Carbon;

class HolidayModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Holiday::truncate(); // clear collection before each test
    }

    /** @test */
    public function it_can_create_a_holiday()
    {
        $holiday = Holiday::create([
            'date' => Carbon::parse('2025-10-20'),
            'description' => 'Test Holiday',
            'type' => 'holiday',
            'session_id' => '123'
        ]);

        $this->assertNotNull($holiday->_id);
        $this->assertEquals('Test Holiday', $holiday->description);
        $this->assertEquals('2025-10-20', $holiday->date->format('Y-m-d'));
    }

    /** @test */
    public function it_can_update_a_holiday()
    {
        $holiday = Holiday::create([
            'date' => now(),
            'description' => 'Old Description',
            'type' => 'holiday',
            'session_id' => '123'
        ]);

        $holiday->update(['description' => 'Updated Description']);
        $this->assertEquals('Updated Description', $holiday->description);
    }

    /** @test */
    public function it_can_delete_a_holiday()
    {
        $holiday = Holiday::create([
            'date' => now(),
            'description' => 'To Delete',
            'type' => 'holiday',
            'session_id' => '123'
        ]);

        $id = $holiday->_id;
        $holiday->delete();

        $this->assertNull(Holiday::find($id));
    }
}
