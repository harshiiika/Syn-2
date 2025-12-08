<?php

namespace Tests\Feature;

use App\Models\Master\FeesMaster;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FeesMasterTest extends TestCase
{
    protected $userId;

    protected function setUp(): void
    {
        parent::setUp();

        //    : Clear any mocks from Unit tests
        \Mockery::close();

        // Clean database
        $this->cleanDatabase();

        // Create test user
        $this->userId = DB::connection('mongodb')
            ->getCollection('users')
            ->insertOne([
                'name' => 'Test Admin',
                'email' => 'feesmaster_' . uniqid() . '@test.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ])->getInsertedId();

        Auth::loginUsingId($this->userId);
    }

    protected function tearDown(): void
    {
        $this->cleanDatabase();
        Auth::logout();
        parent::tearDown();
    }

    protected function cleanDatabase(): void
    {
        try {
            DB::connection('mongodb')->getCollection('fees_masters')->deleteMany([]);
            DB::connection('mongodb')->getCollection('users')->deleteMany([
                'email' => ['$regex' => '^feesmaster_']
            ]);
        } catch (\Exception $e) {
            // Ignore
        }
    }

    public function test_it_can_create_fees_master()
    {
        $fees = FeesMaster::create([
            'course' => 'Test Course',
            'gst_percentage' => 18,
            'classroom_course' => 10000,
            'status' => 'active',
        ]);

        $this->assertNotNull($fees->_id);
        $this->assertEquals('Test Course', $fees->course);
        $this->assertEquals(10000, $fees->classroom_course);
    }

    public function test_it_calculates_gst_automatically()
    {
        $fees = FeesMaster::create([
            'course' => 'Test Course',
            'gst_percentage' => 18,
            'classroom_course' => 10000,
            'status' => 'active',
        ]);

        $this->assertEquals(1800, $fees->classroom_gst);
        $this->assertEquals(11800, $fees->classroom_total);
    }

    public function test_gst_recalculates_on_update()
    {
        $fees = FeesMaster::create([
            'course' => 'Test Course',
            'gst_percentage' => 18,
            'classroom_course' => 10000,
            'status' => 'active',
        ]);

        $fees->classroom_course = 15000;
        $fees->save();
        $fees->refresh();

        $this->assertEquals(2700, $fees->classroom_gst);
        $this->assertEquals(17700, $fees->classroom_total);
    }
}