<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Master\Test as Exam;
use Carbon\Carbon;

class TestModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Exam::truncate();
    }

    /** @test */
    public function it_can_create_a_test()
    {
        $exam = Exam::create([
            'date' => Carbon::parse('2025-10-25'),
            'description' => 'Midterm Exam',
            'test_name' => 'Midterm',
            'session_id' => '123',
            'status' => 'scheduled'
        ]);

        $this->assertNotNull($exam->_id);
        $this->assertEquals('Midterm Exam', $exam->description);
    }

    /** @test */
    public function it_can_update_a_test()
    {
        $exam = Exam::create([
            'date' => now(),
            'description' => 'Old Test',
            'test_name' => 'Old Name',
            'session_id' => '123',
            'status' => 'scheduled'
        ]);

        $exam->update(['description' => 'Updated Test']);
        $this->assertEquals('Updated Test', $exam->description);
    }

    /** @test */
    public function it_can_delete_a_test()
    {
        $exam = Exam::create([
            'date' => now(),
            'description' => 'To Delete',
            'test_name' => 'Delete Exam',
            'session_id' => '123',
            'status' => 'scheduled'
        ]);

        $id = $exam->_id;
        $exam->delete();

        $this->assertNull(Exam::find($id));
    }
}
