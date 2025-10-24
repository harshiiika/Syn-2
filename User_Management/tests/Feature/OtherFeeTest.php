<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Master\OtherFee;
use Illuminate\Support\Facades\DB;

class OtherFeeFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanDatabase();
    }

    protected function tearDown(): void
    {
        $this->cleanDatabase();
        parent::tearDown();
    }

    protected function cleanDatabase(): void
    {
        try {
            DB::connection('mongodb')->getCollection('other_fees')->deleteMany([]);
        } catch (\Exception $e) {
            // ignore
        }
    }

    /** @test */
    public function it_can_create_an_other_fee()
    {
        $data = [
            'fee_type' => 'Library Fee',
            'amount' => 500.00
        ];

        $response = $this->postJson('/master/other-fees', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Other fee created successfully'
                 ]);

        $this->assertTrue(OtherFee::where('fee_type', 'Library Fee')->exists());
    }

    /** @test */
    public function it_requires_fee_type()
    {
        $response = $this->postJson('/master/other-fees', [
            'fee_type' => '',
            'amount' => 100
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('fee_type');
    }

    /** @test */
    public function it_requires_amount()
    {
        $response = $this->postJson('/master/other-fees', [
            'fee_type' => 'Sports Fee',
            'amount' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('amount');
    }

    /** @test */
    public function it_requires_amount_to_be_numeric()
    {
        $response = $this->postJson('/master/other-fees', [
            'fee_type' => 'Sports Fee',
            'amount' => 'invalid'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('amount');
    }

    /** @test */
    public function it_can_list_all_other_fees()
    {
        OtherFee::create(['fee_type' => 'Library Fee', 'amount' => 500]);
        OtherFee::create(['fee_type' => 'Sports Fee', 'amount' => 300]);

        $response = $this->getJson('/master/other-fees/data');

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure(['success', 'data']);
    }

    /** @test */
    public function it_can_delete_an_other_fee()
    {
        $fee = OtherFee::create(['fee_type' => 'Test Fee', 'amount' => 200]);

        $response = $this->deleteJson("/master/other-fees/{$fee->_id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertFalse(OtherFee::where('_id', $fee->_id)->exists());
    }
}
