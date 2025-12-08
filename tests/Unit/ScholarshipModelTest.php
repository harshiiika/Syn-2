<?php   

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Master\Scholarship;

class ScholarshipModelTest extends TestCase
{
    /** @test */
    public function it_returns_defined_scholarship_types()
    {
        $types = Scholarship::getTypes();

        $this->assertContains('Continuing Education Scholarship', $types);
        $this->assertContains('Board Examination Scholarship', $types);
    }

    /** @test */
    public function it_returns_defined_categories()
    {
        $categories = Scholarship::getCategories();

        $this->assertContains('OBC', $categories);
        $this->assertContains('SC', $categories);
    }

    /** @test */
    public function it_returns_defined_applicable_for()
    {
        $list = Scholarship::getApplicableFor();

        $this->assertContains('EWS', $list);
        $this->assertContains('All', $list);
    }

    /** @test */
    public function scope_active_filters_active_scholarships()
    {
        Scholarship::factory()->create(['status' => 'active']);
        Scholarship::factory()->create(['status' => 'inactive']);

        $active = Scholarship::active()->get();

        $this->assertCount(1, $active);
        $this->assertEquals('active', $active->first()->status);
    }
}
