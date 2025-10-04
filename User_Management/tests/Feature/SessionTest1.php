<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Session\User;

class SessionTest1 extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    // Should redirect authenticated users to dashboard
    $response->assertStatus(302);
    $response->assertRedirect(route('dashboard'));
}
}