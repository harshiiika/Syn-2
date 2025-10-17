<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Master\Branch;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BranchModelTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Instead of RefreshDatabase (which triggers SQL transactions),
     * we'll manually clear the collection before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        Branch::truncate(); // MongoDB-safe cleanup
    }

    /** @test */
    public function branch_can_be_created()
    {
        // Instead of create() which may open a session,
        // we use insert() to avoid transaction handling
        $branch = Branch::raw()->insertOne([
            'name' => 'Main Branch',
            'city' => 'Jaipur',
            'status' => 'active',
        ]);

        $this->assertNotNull($branch->getInsertedId());
        $found = Branch::where('name', 'Main Branch')->first();
        $this->assertEquals('Jaipur', $found->city);
        $this->assertEquals('active', $found->status);
    }

    /** @test */
    public function branch_has_correct_fillable_attributes()
    {
        $branch = new Branch();

        $this->assertEquals(
            ['name', 'city', 'status'],
            $branch->getFillable()
        );
    }

    /** @test */
    public function branch_uses_mongodb_connection()
    {
        $branch = new Branch();
        $this->assertEquals('mongodb', $branch->getConnectionName());
    }

    /** @test */
    public function branch_uses_correct_collection_name()
    {
        $branch = new Branch();

        $reflection = new \ReflectionClass($branch);
        $property = $reflection->getProperty('collection');
        $property->setAccessible(true);

        $this->assertEquals('branches', $property->getValue($branch));
    }

    /** @test */
    public function branch_can_be_updated()
    {
        $branch = Branch::create([
            'name' => 'Old Branch',
            'city' => 'Delhi',
            'status' => 'active',
        ]);

        $branch->update(['city' => 'Mumbai']);

        $this->assertEquals('Mumbai', $branch->city);
    }

    /** @test */
    public function branch_can_be_deleted()
    {
        $branch = Branch::create([
            'name' => 'Temp Branch',
            'city' => 'Goa',
            'status' => 'inactive',
        ]);

        $id = $branch->_id;
        $branch->delete();

        $this->assertNull(Branch::find($id));
    }

    /** @test */
    public function branch_name_attribute_exists()
    {
        $branch = new Branch();
        $this->assertContains('name', $branch->getFillable());
    }

    /** @test */
    public function branch_city_attribute_exists()
    {
        $branch = new Branch();
        $this->assertContains('city', $branch->getFillable());
    }

    /** @test */
    public function branch_status_attribute_exists()
    {
        $branch = new Branch();
        $this->assertContains('status', $branch->getFillable());
    }

    /** @test */
    public function branch_can_have_active_status()
    {
        $branch = Branch::create([
            'name' => 'Active Branch',
            'city' => 'Delhi',
            'status' => 'active',
        ]);

        $this->assertEquals('active', $branch->status);
    }

    /** @test */
    public function branch_can_have_deactivated_status()
    {
        $branch = Branch::create([
            'name' => 'Inactive Branch',
            'city' => 'Pune',
            'status' => 'inactive',
        ]);

        $this->assertEquals('inactive', $branch->status);
    }

    /** @test */
    public function branch_status_can_be_toggled()
    {
        $branch = Branch::create([
            'name' => 'Toggle Branch',
            'city' => 'Kolkata',
            'status' => 'active',
        ]);

        $branch->status = $branch->status === 'active' ? 'inactive' : 'active';
        $branch->save();

        $this->assertEquals('inactive', $branch->status);
    }

    /** @test */
    public function multiple_branches_can_be_created()
    {
        Branch::insert([
            ['name' => 'Branch 1', 'city' => 'Chennai', 'status' => 'active'],
            ['name' => 'Branch 2', 'city' => 'Hyderabad', 'status' => 'inactive'],
        ]);

        $this->assertCount(2, Branch::all());
    }

    /** @test */
    public function branch_can_be_retrieved_by_name()
    {
        Branch::create(['name' => 'Special Branch', 'city' => 'Lucknow', 'status' => 'active']);
        $branch = Branch::where('name', 'Special Branch')->first();

        $this->assertNotNull($branch);
        $this->assertEquals('Special Branch', $branch->name);
    }

    /** @test */
    public function branch_can_be_retrieved_by_city()
    {
        Branch::create(['name' => 'City Branch', 'city' => 'Indore', 'status' => 'active']);
        $branch = Branch::where('city', 'Indore')->first();

        $this->assertNotNull($branch);
        $this->assertEquals('Indore', $branch->city);
    }

    /** @test */
    public function branch_can_be_retrieved_by_status()
    {
        Branch::create(['name' => 'Status Branch', 'city' => 'Bhopal', 'status' => 'inactive']);
        $branch = Branch::where('status', 'inactive')->first();

        $this->assertNotNull($branch);
        $this->assertEquals('inactive', $branch->status);
    }

    /** @test */
    public function all_branches_can_be_retrieved()
    {
        Branch::insert([
            ['name' => 'Branch A', 'city' => 'Nagpur', 'status' => 'active'],
            ['name' => 'Branch B', 'city' => 'Surat', 'status' => 'inactive'],
        ]);

        $branches = Branch::all();
        $this->assertTrue($branches->count() >= 2);
    }

    /** @test */
    public function branch_extends_authenticatable()
    {
        $this->assertTrue(is_subclass_of(Branch::class, \Illuminate\Foundation\Auth\User::class));
    }

    /** @test */
    public function branch_has_factory_trait()
    {
        $traits = class_uses(Branch::class);
        $this->assertContains(\Illuminate\Database\Eloquent\Factories\HasFactory::class, $traits);
    }

    /** @test */
    public function branch_has_notifiable_trait()
    {
        $traits = class_uses(Branch::class);
        $this->assertContains(\Illuminate\Notifications\Notifiable::class, $traits);
    }

    /** @test */
    public function branch_data_persists()
    {
        Branch::create(['name' => 'Persist Branch', 'city' => 'Agra', 'status' => 'active']);
        $branch = Branch::where('name', 'Persist Branch')->first();

        $this->assertNotNull($branch);
        $this->assertEquals('Agra', $branch->city);
    }
}
