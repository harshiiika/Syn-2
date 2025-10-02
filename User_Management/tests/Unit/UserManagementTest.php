<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\User\Role;
use App\Models\User\Department;
use Illuminate\Support\Facades\Hash;

class UserManagementTest extends TestCase
{
    /**
     * Set up before each test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Use a separate MongoDB database for testing (set in .env.testing)
        // Ensure this DB exists and is empty for safe tests
        User::truncate();
        Role::truncate();
        Department::truncate();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_add_a_user()
    {
        $role = Role::create(['name' => 'Administration']);
        $dept = Department::create(['name' => 'Front Office']);

        $response = $this->post('/users/add', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobileNumber' => '1234567890',
            'branch' => 'Bikaner',
            'roles' => [$role->name],
            'departments' => [$dept->name],
            'password' => 'password123',
            'confirm_password' => 'password123',
        ]);

        $response->assertRedirect(route('emp'));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function it_can_show_users()
    {
        $user = User::create([
            'name' => 'Show User',
            'email' => 'show@example.com',
            'mobileNumber' => '9876543210',
            'branch' => 'Bikaner',
            'roles' => [],
            'departments' => [],
            'password' => Hash::make('password123'),
            'status' => 'Active',
        ]);

        $response = $this->get('/emp/list');
        $response->assertStatus(200);
        $response->assertSee('Show User');
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'mobileNumber' => '1111111111',
            'branch' => 'Bikaner',
            'roles' => [],
            'departments' => [],
            'password' => Hash::make('password123'),
            'status' => 'Active',
        ]);

        $dept = Department::create(['name' => 'Front Office']);
        $role = Role::create(['name' => 'Administration']);

        $response = $this->put("/users/update/{$user->_id}", [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'mobileNumber' => '2222222222',
            'branch' => 'Bikaner',
            'department' => 'Front Office',
        ]);

        $response->assertRedirect(route('emp'));

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
    }

    /** @test */
    public function it_can_toggle_user_status()
    {
        $user = User::create([
            'name' => 'Toggle User',
            'email' => 'toggle@example.com',
            'mobileNumber' => '9999999999',
            'branch' => 'Bikaner',
            'roles' => [],
            'departments' => [],
            'password' => Hash::make('password123'),
            'status' => 'Active',
        ]);

        $response = $this->post("/users/toggle-status/{$user->_id}");
        $response->assertRedirect(route('emp'));

        $user->refresh();
        $this->assertEquals('Deactivated', $user->status);

        // Toggle back
        $this->post("/users/toggle-status/{$user->_id}");
        $user->refresh();
        $this->assertEquals('Active', $user->status);
    }
}
