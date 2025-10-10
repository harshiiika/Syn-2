<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\FeesMaster;   // adjust if your model name/table differs

class FeesMasterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 👇 Skip auth / other middleware so we don't need a User model
        $this->withoutMiddleware();
    }

    /** @test */
    public function index_shows_table_and_create_modal(): void
    {
        $fees = FeesMaster::factory()->count(2)->create();

        $response = $this->get(route('fees.index'));

        $response->assertOk();
        $response->assertSee('FEES MASTER', false);
        $response->assertSee('Create Fees', false);

        foreach ($fees as $fee) {
            $response->assertSee($fee->course, false);
        }

        // bits from the modal/form to ensure structure
        $response->assertSee('GST %', false);
        $response->assertSee('Fees (before GST)', false);
        $response->assertSee('name="classroom_fee"', false);
        $response->assertSee('name="live_fee"', false);
        $response->assertSee('name="recorded_fee"', false);
        $response->assertSee('name="study_fee"', false);
        $response->assertSee('name="test_fee"', false);
    }

    /** @test */
    public function create_page_renders_form_like_modal(): void
    {
        if (! app('router')->has('fees.create')) {
            $this->markTestSkipped('fees.create route not present; create form is shown in a modal.');
        }

        $response = $this->get(route('fees.create'));

        $response->assertOk();
        $response->assertSee('Create Fees', false);
        $response->assertSee('GST %', false);
        $response->assertSee('Fees (before GST)', false);
        $response->assertSee('name="classroom_fee"', false);
    }

    /** @test */
    public function edit_page_renders_with_prefilled_values(): void
    {
        $fee = FeesMaster::factory()->create([
            'course'        => 'Momentum',
            'gst_percent'   => 18,
            'classroom_fee' => 2,
            'live_fee'      => 1,
            'recorded_fee'  => 4,
            'study_fee'     => 3,
            'test_fee'      => 2,
            'status'        => 'Active',
        ]);

        $response = $this->get(route('fees.edit', $fee->id));

        $response->assertOk();
        $response->assertSee('Edit Fees', false);
        $response->assertSee('Momentum', false);
        $response->assertSee('GST %', false);
        $response->assertSee('name="classroom_fee"', false);
        $response->assertSee('name="live_fee"', false);
        $response->assertSee('name="recorded_fee"', false);
        $response->assertSee('name="study_fee"', false);
        $response->assertSee('name="test_fee"', false);
    }

    /** @test */
    public function store_persists_fee_and_redirects(): void
    {
        $payload = [
            'course'         => 'Impulse',
            'gst_percent'    => 18,
            'classroom_fee'  => 45000,
            'live_fee'       => 40000,
            'recorded_fee'   => 30000,
            'study_fee'      => 15000,
            'test_fee'       => 10000,
            'status'         => 'Active',
        ];

        $response = $this->post(route('fees.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('fees_master', [ // adjust table name if different
            'course'      => 'Impulse',
            'gst_percent' => 18,
            'status'      => 'Active',
        ]);
    }

    /** @test */
    public function update_changes_fields(): void
    {
        $fee = FeesMaster::factory()->create(['course' => 'Momentum', 'status' => 'Active']);

        $payload = [
            'course'         => 'Intensity',
            'gst_percent'    => 18,
            'classroom_fee'  => 100,
            'live_fee'       => 200,
            'recorded_fee'   => 300,
            'study_fee'      => 400,
            'test_fee'       => 500,
            'status'         => 'Inactive',
        ];

        $response = $this->put(route('fees.update', $fee->id), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('fees_master', [
            'id'            => $fee->id,
            'course'        => 'Intensity',
            'status'        => 'Inactive',
            'classroom_fee' => 100,
        ]);
    }

    /** @test */
    public function toggle_status_flips_between_active_and_inactive(): void
    {
        $fee = FeesMaster::factory()->create(['status' => 'Active']);

        $this->patch(route('fees.toggle', $fee->id))->assertRedirect();
        $fee->refresh();
        $this->assertEquals('Inactive', $fee->status);

        $this->patch(route('fees.toggle', $fee->id))->assertRedirect();
        $fee->refresh();
        $this->assertEquals('Active', $fee->status);
    }

    /** @test */
    public function show_returns_json_for_view_button(): void
    {
        $fee = FeesMaster::factory()->create([
            'course'      => 'Thrust',
            'gst_percent' => 18,
            'status'      => 'Active',
        ]);

        $this->get(route('fees.show', $fee->id))
            ->assertOk()
            ->assertJsonFragment([
                'course'      => 'Thrust',
                'gst_percent' => 18,
                'status'      => 'Active',
            ]);
    }

    /** @test */
    public function store_requires_course_and_gst(): void
    {
        $this->from(route('fees.index'))
             ->post(route('fees.store'), [
                 'course'      => '',
                 'gst_percent' => '',
             ])
             ->assertRedirect(route('fees.index'))
             ->assertSessionHasErrors(['course', 'gst_percent']);
    }
}
