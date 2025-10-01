<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\RefreshMongoDatabase;
use App\Models\Student\Inquiry;
use PHPUnit\Framework\Attributes\Test;

class InquiryModelTest extends TestCase
{
    use RefreshMongoDatabase;

    #[Test]
    public function it_can_create_an_inquiry(): void
    {
        $doc = Inquiry::factory()->create([
            'status'       => 'new',
            'student_name' => 'Chetan',
        ]);

        // _id is an ObjectId managed by the driver
        $this->assertNotNull($doc->_id);
        $this->assertEquals('new', $doc->status);
        $this->assertEquals('Chetan', $doc->student_name);
    }

    #[Test]
    public function it_can_update_an_inquiry(): void
    {
        $doc = Inquiry::factory()->create(['status' => 'new']);

        $doc->update(['status' => 'open']);
        $doc->refresh(); // reload using _id

        $this->assertEquals('open', $doc->status);
    }

    #[Test]
    public function it_can_delete_an_inquiry(): void
    {
        $doc = Inquiry::factory()->create();
        $before = Inquiry::query()->count();

        $doc->delete();

        $after = Inquiry::query()->count();
        $this->assertSame($before - 1, $after);
    }

    #[Test]
    public function fillable_fields_work_as_expected(): void
    {
        $doc = new Inquiry([
            'student_name'       => 'Chetan',
            'father_name'        => 'Ramesh',
            'father_contact'     => '9876543210',
            'student_contact'    => '9876543211',
            'category'           => 'General',
            'state'              => 'Rajasthan',
            'city'               => 'Bikaner',
            'address'            => 'Some address',
            'branch_name'        => 'CSE',
            'ews'                => true,
            'service_background' => false,
            'specially_abled'    => false,
            'status'             => 'new',
            'not_fillable_field' => 'X',
        ]);

        $doc->save();

        $this->assertNull($doc->getAttribute('not_fillable_field'));
        $this->assertEquals('Chetan', $doc->student_name);
    }
}
