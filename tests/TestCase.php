<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     * 
     * Ensure APP_ENV=testing is set before Laravel bootstrap so it loads .env.testing
     * instead of .env for database configuration.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
