<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\RefreshMongoDatabase;
use App\Models\Student\Inquiry;
use PHPUnit\Framework\Attributes\Test;

class InquiryFeatureTest extends TestCase
{
    use RefreshMongoDatabase;

    #[Test]
    public function index_shows_paginated_inquiries(): void
    {
        Inquiry::factory()->count(3)->create();

        $response = $this->get(route('inquiries.index'));
        $response->assertStatus(200);
        $response->assertSee('Inquiry');
    }

    #[Test]
    public function it_validates_store_request_and_creates_document(): void
    {
        $payload = [
            'student_name'       => 'Chetan',
            'father_name'        => 'Ramesh',
            'father_contact'     => '9876543210',
            'father_whatsapp'    => '9876543210',
            'student_contact'    => '9876543211',
            'category'           => 'General',
            'course_name'        => 'Anthesis 11th NEET',
            'delivery_mode'      => 'Offline',
            'course_content'     => 'Class Room Course',
            'branch'             => 'Branch 1',
            'ews'                => 'No',
            'defense'            => 'No',
            'specially_abled'    => 'No',
            'status'             => 'Pending',
        ];

        $response = $this->postJson(route('inquiries.store'), $payload);
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertTrue(
            Inquiry::where('student_name', 'Chetan')->count() >= 1
        );
    }

    #[Test]
    public function store_fails_when_required_fields_missing(): void
    {
        $response = $this->postJson(route('inquiries.store'), []);
        $response->assertStatus(422);

        // Check that response has the expected structure
        $response->assertJsonStructure([
            'success',
            'message',
            'errors'
        ]);

        // Verify specific required fields are validated
        $response->assertJsonValidationErrors([
            'student_name',
            'father_name',
            'father_contact',
            'category',
            'branch',
            'ews',
            'defense',
            'specially_abled',
        ]);
    }

    #[Test]
    public function it_updates_an_inquiry(): void
    {
        // Create with all required fields as strings
        $doc = Inquiry::factory()->create([
            'status'          => 'Pending',
            'ews'             => 'No',
            'defense'         => 'No',
            'specially_abled' => 'No',
        ]);

        $updateData = [
            'status'             => 'Active',
            'student_name'       => $doc->student_name,
            'father_name'        => $doc->father_name,
            'father_contact'     => $doc->father_contact,
            'father_whatsapp'    => $doc->father_whatsapp ?? '',
            'student_contact'    => $doc->student_contact ?? '',
            'category'           => $doc->category,
            'course_name'        => $doc->course_name ?? 'Anthesis 11th NEET',
            'delivery_mode'      => $doc->delivery_mode ?? 'Offline',
            'course_content'     => $doc->course_content ?? 'Class Room Course',
            'branch'             => $doc->branch ?? 'Branch 1',
            'ews'                => 'No',
            'defense'            => 'No',
            'specially_abled'    => 'No',
        ];

        $response = $this->putJson(route('inquiries.update', $doc->_id), $updateData);
        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
        ]);

        $doc->refresh();
        $this->assertEquals('Active', $doc->status);
    }

    #[Test]
    public function it_deletes_an_inquiry(): void
    {
        $doc = Inquiry::factory()->create();

        $response = $this->deleteJson(route('inquiries.destroy', $doc->_id));
        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertSame(0, Inquiry::where('_id', $doc->_id)->count());
    }
}