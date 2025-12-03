<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Master\Holiday;
use App\Models\Master\Test;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Run before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cleanCollections();

    }

    /**
     * Remove all data from Mongo collections.
     */
    protected function cleanCollections(): void
    {
        Holiday::truncate();
        Test::truncate();
    }
}
