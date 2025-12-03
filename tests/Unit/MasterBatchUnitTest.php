<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Master\Batch;
use App\Models\User\BatchAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterBatchUnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clear MongoDB collections before each test
        DB::connection('mongodb')->getCollection('batches')->deleteMany([]);
        DB::connection('mongodb')->getCollection('batch_assignments')->deleteMany([]);
    }

    protected function tearDown(): void
    {
        DB::connection('mongodb')->getCollection('batches')->deleteMany([]);
        DB::connection('mongodb')->getCollection('batch_assignments')->deleteMany([]);
        parent::tearDown();
    }

    /** @test */
    public function batch_can_be_created_with_required_fields()
    {
        $batch = Batch::create([
            'batch_id' => 'BT100',
            'course' => 'Anthesis 11th NEET',
            'class' => '11th (XI)',
            'course_type' => 'Pre-Medical',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-10-15',
            'status' => 'Active',
        ]);

        $this->assertInstanceOf(Batch::class, $batch);
        $this->assertEquals('BT100', $batch->batch_id);
        $this->assertEquals('Pre-Medical', $batch->course_type);
    }

    /** @test */
    public function batch_assignment_is_created_correctly()
    {
        $batch = Batch::create([
            'batch_id' => 'BT101',
            'course' => 'Impulse 11th IIT',
            'class' => '11th (XI)',
            'course_type' => 'Pre-Engineering',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-10-01',
            'status' => 'Active',
        ]);

        $assignment = BatchAssignment::create([
            'batch_id' => $batch->batch_id,
            'start_date' => $batch->start_date,
            'username' => null,
            'shift' => $batch->shift,
            'status' => 'Active'
        ]);

        $this->assertInstanceOf(BatchAssignment::class, $assignment);
        $this->assertEquals('BT101', $assignment->batch_id);
        $this->assertEquals('Morning', $assignment->shift);
    }

    /** @test */
    public function batch_status_can_be_toggled()
    {
        $batch = Batch::create([
            'batch_id' => 'BT102',
            'course' => 'Radicle 8th',
            'class' => '8th (VIII)',
            'course_type' => 'Pre-Foundation',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-09-01',
            'status' => 'Active',
        ]);

        // Toggle manually
        $batch->status = $batch->status === 'Active' ? 'Inactive' : 'Active';
        $batch->save();

        $this->assertEquals('Inactive', $batch->status);
    }

    /** @test */
    public function batch_fields_can_be_updated()
    {
        $batch = Batch::create([
            'batch_id' => 'BT103',
            'course' => 'Momentum 12th NEET',
            'class' => '12th (XII)',
            'course_type' => 'Pre-Medical',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-09-01',
            'status' => 'Active',
        ]);

        $batch->update([
            'medium' => 'Hindi',
            'mode' => 'Online',
            'shift' => 'Evening',
        ]);

        $this->assertEquals('Hindi', $batch->medium);
        $this->assertEquals('Online', $batch->mode);
        $this->assertEquals('Evening', $batch->shift);
    }
}
