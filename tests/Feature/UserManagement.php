<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;

class UserManagement extends TestCase
{
    /**
     * Test root URL redirects to login
     */
    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302); // redirect
        $response->assertRedirect(route('login'));
    }

    /**
     * Test login page loads successfully
     */
    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login'); 
    }

    /**
     * Test dashboard loads for authenticated user
     */
    public function test_dashboard_loads_for_authenticated_user(): void
    {
        // Create a dummy user in MongoDB
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Simulate logged-in user
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('auth.dashboard');
    }
}
