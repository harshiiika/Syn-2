<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Master\Branch;
use Illuminate\Foundation\Testing\WithFaker;

class BranchControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Clean the Mongo collection before each test
        Branch::truncate();
    }

    /**
     * Test branch index page loads successfully
     */
    public function test_branch_index_page_loads_successfully()
    {
        $response = $this->get(route('branches.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.branch.branch');
        $response->assertViewHas('branches');
    }

    /**
     * Test branch index displays all branches
     */
    public function test_branch_index_displays_all_branches()
    {
        Branch::create(['name' => 'Bikaner Branch', 'city' => 'Bikaner', 'status' => 'Active']);
        Branch::create(['name' => 'Jaipur Branch', 'city' => 'Jaipur', 'status' => 'Active']);

        $response = $this->get(route('branches.index'));

        $response->assertStatus(200);
        $response->assertSee('Bikaner Branch');
        $response->assertSee('Jaipur Branch');
    }

    /**
     * Test creating a new branch successfully
     */
    public function test_can_create_new_branch()
    {
        $branchData = [
            'name' => 'Jodhpur Branch',
            'city' => 'Jodhpur',
        ];

        $response = $this->post(route('branches.add'), $branchData);

        $response->assertRedirect(route('branches.index'));
        $response->assertSessionHas('success', 'Branch added successfully!');

        $this->assertTrue(
            Branch::where('name', 'Jodhpur Branch')
                ->where('city', 'Jodhpur')
                ->where('status', 'Active')
                ->exists()
        );
    }

    /**
     * Test branch creation requires name
     */
    public function test_branch_creation_requires_name()
    {
        $response = $this->post(route('branches.add'), ['city' => 'Jodhpur']);
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test branch creation requires city
     */
    public function test_branch_creation_requires_city()
    {
        $response = $this->post(route('branches.add'), ['name' => 'Jodhpur Branch']);
        $response->assertSessionHasErrors(['city']);
    }

    /**
     * Test branch name max length
     */
    public function test_branch_name_cannot_exceed_255_characters()
    {
        $response = $this->post(route('branches.add'), [
            'name' => str_repeat('a', 256),
            'city' => 'Jodhpur',
        ]);
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test updating an existing branch
     */
    public function test_can_update_existing_branch()
    {
        $branch = Branch::create([
            'name' => 'Old Branch Name',
            'city' => 'Old City',
            'status' => 'Active',
        ]);

        $updatedData = [
            'name' => 'Updated Branch Name',
            'city' => 'Updated City',
        ];

        $response = $this->put(route('branches.update', $branch->_id), $updatedData);

        $response->assertRedirect(route('branches.index'));
        $response->assertSessionHas('success', 'Branch updated successfully!');

        $this->assertTrue(
            Branch::where('name', 'Updated Branch Name')
                ->where('city', 'Updated City')
                ->exists()
        );
    }

    /**
     * Test update requires name
     */
    public function test_branch_update_requires_name()
    {
        $branch = Branch::create(['name' => 'Test Branch', 'city' => 'Test City', 'status' => 'Active']);

        $response = $this->put(route('branches.update', $branch->_id), ['city' => 'Updated City']);
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test update requires city
     */
    public function test_branch_update_requires_city()
    {
        $branch = Branch::create(['name' => 'Test Branch', 'city' => 'Test City', 'status' => 'Active']);

        $response = $this->put(route('branches.update', $branch->_id), ['name' => 'Updated Branch']);
        $response->assertSessionHasErrors(['city']);
    }

    /**
     * Test toggling branch status from Active to Deactivated
     */
    public function test_can_toggle_branch_status_from_active_to_deactivated()
    {
        $branch = Branch::create(['name' => 'Test Branch', 'city' => 'Test City', 'status' => 'Active']);

        $response = $this->post(route('branches.toggleStatus', $branch->_id));

        $response->assertRedirect(route('branches.index'));
        $response->assertSessionHas('success', 'Branch status changed to Deactivated!');

        $branch->refresh();
        $this->assertEquals('Deactivated', $branch->status);
    }

    /**
     * Test toggling branch status from Deactivated to Active
     */
    public function test_can_toggle_branch_status_from_deactivated_to_active()
    {
        $branch = Branch::create(['name' => 'Test Branch', 'city' => 'Test City', 'status' => 'Deactivated']);

        $response = $this->post(route('branches.toggleStatus', $branch->_id));

        $response->assertRedirect(route('branches.index'));
        $response->assertSessionHas('success', 'Branch status changed to Active!');

        $branch->refresh();
        $this->assertEquals('Active', $branch->status);
    }

    /**
     * Test toggle status with invalid ID returns 404
     */
    public function test_toggle_status_with_invalid_id_returns_404()
    {
        $response = $this->post(route('branches.toggleStatus', 'invalid-id'));
        $response->assertStatus(404);
    }

    /**
     * Test update with invalid branch ID returns 404
     */
    public function test_update_with_invalid_id_returns_404()
    {
        $response = $this->put(route('branches.update', 'invalid-id'), [
            'name' => 'Test',
            'city' => 'Test City',
        ]);
        $response->assertStatus(404);
    }

    /**
     * Test default status is Active
     */
    public function test_new_branch_has_active_status_by_default()
    {
        $this->post(route('branches.add'), ['name' => 'New Branch', 'city' => 'New City']);
        $branch = Branch::where('name', 'New Branch')->first();

        $this->assertEquals('Active', $branch->status);
    }

    /**
     * Test branch list count
     */
    public function test_branch_list_contains_correct_count()
    {
        Branch::create(['name' => 'Branch 1', 'city' => 'City 1', 'status' => 'Active']);
        Branch::create(['name' => 'Branch 2', 'city' => 'City 2', 'status' => 'Active']);
        Branch::create(['name' => 'Branch 3', 'city' => 'City 3', 'status' => 'Active']);

        $response = $this->get(route('branches.index'));
        $response->assertViewHas('branches', function ($branches) {
            return $branches->count() === 3;
        });
    }
}
