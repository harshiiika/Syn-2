<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User\BatchAssignment;
use Illuminate\Support\Facades\DB;

class BatchFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware and exception handling for simpler testing
        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        // Disconnect from default DB to avoid Mongo transactions
        DB::disconnect();
    }

    /** @test */
    public function user_can_assign_batch_successfully()
    {
        // Simulate form submission
        $response = $this->post('/batches/add', [
            'username' => 'Priyanshi Acharya',
            'batch_id' => 'L3',
        ]);

        // Expect redirect (since Laravel usually redirects after form success)
        $response->assertStatus(302);

        // Assert the record exists in MongoDB
        $exists = BatchAssignment::where('username', 'Priyanshi Acharya')
            ->where('batch_id', 'L3')
            ->exists();

        $this->assertTrue($exists, 'BatchAssignment was not saved in MongoDB.');
    }
}
