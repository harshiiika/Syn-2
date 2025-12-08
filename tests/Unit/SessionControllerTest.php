<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Session\SessionController;
use App\Models\Session\AcademicSession;
use Illuminate\Http\Request;
use App\Models\Session\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User as UserModel;

class SessionControllerTest extends TestCase
{
    // use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Clean DB before each test
        AcademicSession::truncate();
        User::truncate();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_new_session_successfully()
    {
        $request = Request::create('/session', 'POST', [
            'name'       => '2024 - 2025',
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date'   => now()->addDays(20)->toDateString(),
        ]);

        $controller = new SessionController();
        $response   = $controller->store($request);

        $this->assertEquals(302, $response->status());
        $this->assertTrue(session()->has('success'));

        $this->assertDatabaseHas('academic_sessions', [
            'name'   => '2024 - 2025',
            'status' => 'active',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_a_session_as_json()
    {
        $session = AcademicSession::create([
            'name'       => '2024 - 2025',
            'start_date' => now()->toDateString(),
            'end_date'   => now()->addDays(10)->toDateString(),
            'status'     => 'deactive'
        ]);

        $controller = new SessionController();
        $response   = $controller->show($session);

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());
        $this->assertStringContainsString($session->name, $response->getContent());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_updates_a_session_successfully()
    {
        $session = AcademicSession::create([
            'name'       => '2024 - 2025',
            'start_date' => now()->toDateString(),
            'end_date'   => now()->addDays(10)->toDateString(),
            'status'     => 'active'
        ]);

        $request = Request::create('/session/update/' . $session->_id, 'POST', [
            'name'       => '2024 - 2025 Updated',
            'start_date' => now()->addDays(6)->toDateString(),
            'end_date'   => now()->addDays(12)->toDateString(),
            'status'     => 'deactive'
        ]);

        $controller = new SessionController();
        $response   = $controller->update($request, $session);

        $this->assertEquals(302, $response->status());
        $this->assertTrue(session()->has('success'));

        $updated = AcademicSession::find($session->_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function home_route_redirects_to_dashboard_for_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('dashboard')); // or ->assertRedirect('/dashboard') if unnamed
        $this->assertAuthenticatedAs($user);
    }
}
// {
//     $user = User::factory()->create();

//     $response = $this->actingAs($user)->get('/');
    
//     // Should redirect to dashboard
//     $response->assertStatus(302);
//     $response->assertRedirect(route('dashboard'));
// }

