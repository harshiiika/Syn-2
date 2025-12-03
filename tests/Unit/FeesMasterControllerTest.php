<?php

namespace Tests\Unit;

use App\Http\Controllers\Master\FeesMasterController;
use App\Models\Master\FeesMaster;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Collection;

class FeesMasterControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Provide a Redirector mock (satisfies Laravel type-hints) that returns a small
        // fake redirect object when ->route() or ->back() is called. The fake object
        // implements with() and getStatusCode() so controller code and our assertions work,
        // but it does NOT use session flashing.
        $redirectMock = Mockery::mock(\Illuminate\Routing\Redirector::class);

        $redirectMock->shouldReceive('route')->andReturnUsing(function (...$args) {
            return new class {
                public function with($key, $value = null)
                {
                    // no session flash; just store if needed
                    $this->flash = is_array($key) ? $key : [$key => $value];
                    return $this;
                }
                public function getStatusCode()
                {
                    return 302;
                }
            };
        })->byDefault();

        $redirectMock->shouldReceive('back')->andReturnUsing(function (...$args) {
            return new class {
                public function with($key, $value = null)
                {
                    $this->flash = is_array($key) ? $key : [$key => $value];
                    return $this;
                }
                public function getStatusCode()
                {
                    return 302;
                }
            };
        })->byDefault();

        $this->app->instance('redirect', $redirectMock);
    }

    public function test_index_returns_view_with_fees_collection_when_successful()
    {
        $modelAlias = Mockery::mock('alias:' . FeesMaster::class);

        $collection = collect([
            (object) ['id' => 1, 'course' => 'A'],
            (object) ['id' => 2, 'course' => 'B'],
        ]);

        $modelAlias->shouldReceive('orderBy')->once()->with('created_at', 'desc')->andReturnSelf();
        $modelAlias->shouldReceive('get')->once()->andReturn($collection);

        $controller = new FeesMasterController();
        $response = $controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertArrayHasKey('fees', $response->getData());
        $this->assertEquals($collection, $response->getData()['fees']);
    }

    public function test_index_handles_exception_and_returns_empty_collection()
    {
        $modelAlias = Mockery::mock('alias:' . FeesMaster::class);
        $modelAlias->shouldReceive('orderBy')->once()->andThrow(new \Exception('db fail'));

        $controller = new FeesMasterController();
        $response = $controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertArrayHasKey('fees', $response->getData());
        $this->assertInstanceOf(Collection::class, $response->getData()['fees']);
        $this->assertTrue($response->getData()['fees']->isEmpty());
    }

    public function test_store_validates_and_creates_with_gst_calculations_and_returns_fake_redirect()
    {
        $input = [
            'course' => 'Course X',
            'gst_percentage' => 18,
            'classroom_course' => 1000,
            'live_online_course' => 2000,
            'recorded_online_course' => 3000,
            'study_material_only' => 400,
            'test_series_only' => 500,
        ];

        $modelAlias = Mockery::mock('alias:' . FeesMaster::class);
        $modelAlias->shouldReceive('create')->once()->with(Mockery::on(function ($payload) use ($input) {
            // verify important calculated fields
            if (($payload['course'] ?? null) !== $input['course']) return false;
            if (($payload['classroom_gst'] ?? null) !== round(1000 * 0.18, 2)) return false;
            if (($payload['classroom_total'] ?? null) !== round(1000 + (1000 * 0.18), 2)) return false;
            if (($payload['status'] ?? null) !== 'active') return false;
            return isset($payload['created_at']) && isset($payload['updated_at']);
        }))->andReturnTrue();

        // create request WITHOUT attaching a Laravel session (per your request)
        $request = Request::create('/fees', 'POST', $input);

        $controller = new FeesMasterController();
        $response = $controller->store($request);

        // our fake redirect object exposes getStatusCode()
        $this->assertIsObject($response);
        $this->assertTrue(method_exists($response, 'getStatusCode'));
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_show_returns_json_on_found_and_404_on_exception()
    {
        $modelAlias = Mockery::mock('alias:' . FeesMaster::class);
        $feeObj = (object) ['id' => 1, 'course' => 'Course X'];

        $modelAlias->shouldReceive('findOrFail')->once()->with(1)->andReturn($feeObj);

        $controller = new FeesMasterController();
        $response = $controller->show(1);

        $this->assertEquals(200, $response->getStatusCode());
        $decoded = json_decode($response->getContent(), true);
        $this->assertEquals((array) $feeObj, $decoded);

        $modelAlias->shouldReceive('findOrFail')->once()->with(999)->andThrow(new \Exception('not found'));
        $response2 = $controller->show(999);
        $this->assertEquals(404, $response2->getStatusCode());
        $decoded2 = json_decode($response2->getContent(), true);
        $this->assertArrayHasKey('error', $decoded2);
    }

    public function test_update_recalculates_and_calls_update_on_model_and_returns_fake_redirect()
    {
        $input = [
            'course' => 'Updated Course',
            'gst_percentage' => 10,
            'classroom_course' => 100,
            'live_online_course' => 200,
            'recorded_online_course' => 300,
            'study_material_only' => 50,
            'test_series_only' => 25,
        ];

        $mockModel = Mockery::mock();
        $mockModel->shouldReceive('update')->once()->with(Mockery::on(function ($payload) use ($input) {
            if (($payload['course'] ?? null) !== $input['course']) return false;
            if (($payload['classroom_gst'] ?? null) !== round(100 * 0.10, 2)) return false;
            return isset($payload['updated_at']);
        }))->andReturnTrue();

        $modelAlias = Mockery::mock('alias:' . FeesMaster::class);
        $modelAlias->shouldReceive('findOrFail')->once()->with(5)->andReturn($mockModel);

        $request = Request::create('/fees/5', 'PUT', $input);

        $controller = new FeesMasterController();
        $response = $controller->update($request, 5);

        $this->assertIsObject($response);
        $this->assertTrue(method_exists($response, 'getStatusCode'));
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_activate_and_deactivate_set_status_and_return_fake_redirect()
    {
        $mockModel = Mockery::mock();
        $mockModel->shouldReceive('update')->twice()->with(Mockery::on(function ($payload) {
            return array_key_exists('status', $payload) && array_key_exists('updated_at', $payload);
        }))->andReturnTrue();

        $modelAlias = Mockery::mock('alias:' . FeesMaster::class);
        $modelAlias->shouldReceive('findOrFail')->twice()->with(7)->andReturn($mockModel);

        $controller = new FeesMasterController();

        $resp1 = $controller->activate(7);
        $this->assertIsObject($resp1);
        $this->assertTrue(method_exists($resp1, 'getStatusCode'));
        $this->assertEquals(302, $resp1->getStatusCode());

        $resp2 = $controller->deactivate(7);
        $this->assertIsObject($resp2);
        $this->assertTrue(method_exists($resp2, 'getStatusCode'));
        $this->assertEquals(302, $resp2->getStatusCode());
    }
}
