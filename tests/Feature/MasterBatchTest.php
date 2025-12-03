<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Master\Batch;
use Illuminate\Support\Facades\DB;

class MasterBatchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clear MongoDB collections before each test
        DB::connection('mongodb')
            ->getCollection('batches')
            ->deleteMany([]);
            
        DB::connection('mongodb')
            ->getCollection('batch_assignments')
            ->deleteMany([]);
    }

    protected function tearDown(): void
    {
        // Clean up after tests
        DB::connection('mongodb')
            ->getCollection('batches')
            ->deleteMany([]);
            
        DB::connection('mongodb')
            ->getCollection('batch_assignments')
            ->deleteMany([]);
            
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_a_batch_and_auto_fill_fields()
    {
        $payload = [
            'batch_id' => 'BT101',
            'course' => 'Anthesis 11th NEET',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-10-15',
            'installment_date_2' => '2025-11-15',
            'installment_date_3' => '2025-12-15',
        ];

        $response = $this->post(route('batches.add'), $payload);

        $response->assertRedirect(route('batches.index'));
        
        $this->assertDatabaseHas('batches', [
            'batch_id' => 'BT101',
            'class' => '11th (XI)',
            'course_type' => 'Pre-Medical',
        ], 'mongodb');

        $this->assertDatabaseHas('batch_assignments', [
            'batch_id' => 'BT101',
            'shift' => 'Morning',
            'status' => 'Active',
        ], 'mongodb');
    }

    /** @test */
    public function it_fails_validation_when_required_fields_are_missing()
    {
        $response = $this->post(route('batches.add'), []);

        $response->assertSessionHasErrors(['batch_id', 'course', 'medium']);
    }

    /** @test */
    public function it_can_update_existing_batch()
    {
        // Create dummy batch
        $batch = Batch::create([
            'batch_id' => 'BT202',
            'course' => 'Impulse 11th IIT',
            'class' => '11th (XI)',
            'course_type' => 'Pre-Engineering',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-09-01',
            'status' => 'Active',
        ]);

        $updatePayload = [
            'batch_id' => 'BT202',
            'course' => 'Intensity 12th IIT',
            'medium' => 'Hindi',
            'mode' => 'Online',
            'shift' => 'Evening',
            'branch_name' => 'City Branch',
            'start_date' => '2025-10-01',
            'status' => 'Active',
        ];

        $response = $this->put(route('batches.update', $batch->_id), $updatePayload);

        $response->assertRedirect(route('batches.index'));

        $this->assertDatabaseHas('batches', [
            'batch_id' => 'BT202',
            'course' => 'Intensity 12th IIT',
            'medium' => 'Hindi',
            'shift' => 'Evening',
        ], 'mongodb');
    }

    /** @test */
    public function it_can_toggle_batch_status()
    {
        $batch = Batch::create([
            'batch_id' => 'BT303',
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

        $response = $this->post(route('batches.toggleStatus', $batch->_id));

        $response->assertRedirect(route('batches.index'));

        $updated = Batch::find($batch->_id);
        $this->assertEquals('Inactive', $updated->status);
    }

    /** @test */
    public function it_can_view_batch_index_page()
    {
        $response = $this->get(route('batches.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_validates_batch_id_is_required()
    {
        $payload = [
            'course' => 'Anthesis 11th NEET',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-10-15',
        ];

        $response = $this->post(route('batches.add'), $payload);

        $response->assertSessionHasErrors(['batch_id']);
    }

    /** @test */
    public function it_validates_course_is_required()
    {
        $payload = [
            'batch_id' => 'BT401',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-10-15',
        ];

        $response = $this->post(route('batches.add'), $payload);

        $response->assertSessionHasErrors(['course']);
    }

    /** @test */
    public function it_can_create_batch_with_different_shifts()
    {
        $morningBatch = [
            'batch_id' => 'BT501',
            'course' => 'Anthesis 11th NEET',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-10-15',
        ];

        $response = $this->post(route('batches.add'), $morningBatch);
        $response->assertRedirect(route('batches.index'));

        $this->assertDatabaseHas('batches', [
            'batch_id' => 'BT501',
            'shift' => 'Morning',
        ], 'mongodb');

        // Clear for next test
        DB::connection('mongodb')->getCollection('batches')->deleteMany([]);

        $eveningBatch = [
            'batch_id' => 'BT502',
            'course' => 'Impulse 12th IIT',
            'medium' => 'Hindi',
            'mode' => 'Online',
            'shift' => 'Evening',
            'branch_name' => 'City Branch',
            'start_date' => '2025-10-15',
        ];

        $response = $this->post(route('batches.add'), $eveningBatch);
        $response->assertRedirect(route('batches.index'));

        $this->assertDatabaseHas('batches', [
            'batch_id' => 'BT502',
            'shift' => 'Evening',
        ], 'mongodb');
    }

    /** @test */
    public function it_can_create_batch_with_different_modes()
    {
        $offlineBatch = [
            'batch_id' => 'BT601',
            'course' => 'Radicle 9th',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-10-15',
        ];

        $response = $this->post(route('batches.add'), $offlineBatch);
        $response->assertRedirect(route('batches.index'));

        $this->assertDatabaseHas('batches', [
            'batch_id' => 'BT601',
            'mode' => 'Offline',
        ], 'mongodb');
    }

    /** @test */
    public function it_can_update_batch_shift()
    {
        $batch = Batch::create([
            'batch_id' => 'BT701',
            'course' => 'Anthesis 11th NEET',
            'class' => '11th (XI)',
            'course_type' => 'Pre-Medical',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Morning',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-09-01',
            'status' => 'Active',
        ]);

        $updatePayload = [
            'batch_id' => 'BT701',
            'course' => 'Anthesis 11th NEET',
            'medium' => 'English',
            'mode' => 'Offline',
            'shift' => 'Evening',
            'branch_name' => 'Main Branch',
            'start_date' => '2025-09-01',
            'status' => 'Active',
        ];

        $response = $this->put(route('batches.update', $batch->_id), $updatePayload);

        $response->assertRedirect(route('batches.index'));

        $this->assertDatabaseHas('batches', [
            'batch_id' => 'BT701',
            'shift' => 'Evening',
        ], 'mongodb');
    }
}