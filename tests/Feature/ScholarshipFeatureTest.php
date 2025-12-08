<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Master\Scholarship;

class ScholarshipFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Scholarship::truncate(); // clear collection
    }

    /** @test */
    public function it_can_list_all_scholarships()
    {
        Scholarship::create([
            'scholarship_type' => 'Merit Based',
            'scholarship_name' => 'National Scholarship',
            'short_name' => 'NSP',
            'category' => 'OBC',
            'applicable_for' => 'All',
            'description' => 'For meritorious students',
            'status' => 'active'
        ]);

        $response = $this->getJson('/master/scholarship/data');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success', 'data', 'current_page', 'last_page', 'per_page', 'total'
                 ])
                 ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_create_a_new_scholarship()
    {
        $data = [
            'scholarship_type' => 'Need Based',
            'scholarship_name' => 'Scholarship A',
            'short_name' => 'SA',
            'category' => 'General',
            'applicable_for' => 'All',
            'description' => 'Test scholarship'
        ];

        $response = $this->postJson('/master/scholarship', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Scholarship created successfully'
                 ]);

        $this->assertTrue(Scholarship::where('scholarship_name', 'Scholarship A')->exists());
    }

    /** @test */
    public function it_validates_required_fields_when_creating_scholarship()
    {
        $response = $this->postJson('/master/scholarship', []);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors']);
    }

    /** @test */
    public function it_can_update_existing_scholarship()
    {
        $scholarship = Scholarship::create([
            'scholarship_type' => 'Merit Based',
            'scholarship_name' => 'Old Name',
            'short_name' => 'ON',
            'category' => 'SC',
            'applicable_for' => 'All'
        ]);

        $updateData = [
            'scholarship_type' => 'Board Examination Scholarship',
            'scholarship_name' => 'Updated Scholarship',
            'short_name' => 'US',
            'category' => 'EWS',
            'applicable_for' => 'EWS'
        ];

        $response = $this->putJson("/master/scholarship/{$scholarship->_id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertTrue(Scholarship::where('scholarship_name', 'Updated Scholarship')->exists());
    }

    /** @test */
    public function it_can_toggle_scholarship_status()
    {
        $scholarship = Scholarship::create([
            'scholarship_type' => 'Merit',
            'scholarship_name' => 'Toggle Test',
            'short_name' => 'TT',
            'category' => 'General',
            'applicable_for' => 'All',
            'status' => 'active'
        ]);

        $response = $this->patchJson("/master/scholarship/{$scholarship->_id}/toggle-status");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $scholarship->refresh();
        $this->assertEquals('inactive', $scholarship->status);
    }

    /** @test */
    public function it_can_delete_a_scholarship()
    {
        $scholarship = Scholarship::create([
            'scholarship_type' => 'Merit',
            'scholarship_name' => 'Delete Test',
            'short_name' => 'DT',
            'category' => 'SC',
            'applicable_for' => 'All'
        ]);

        $response = $this->deleteJson("/master/scholarship/{$scholarship->_id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertFalse(Scholarship::where('_id', $scholarship->_id)->exists());
    }
}