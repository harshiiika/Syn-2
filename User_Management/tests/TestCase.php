<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        // CRITICAL: Close ALL mocks before EVERY test
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
        
        parent::setUp();
        
        config(['database.default' => 'mongodb']);
    }

    protected function tearDown(): void
    {
        // CRITICAL: Close ALL mocks after EVERY test
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
        
        parent::tearDown();
    }
}