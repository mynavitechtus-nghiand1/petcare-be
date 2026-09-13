<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Assert a value is a Carbon instance within $toleranceSeconds of now()->addMinutes(config('filesystems.file.presigned_url_expiry')).
 */
function carbonMatchesConfiguredPresignedExpiry(mixed $expiration, int $toleranceSeconds = 2): bool
{
    if (!($expiration instanceof \Illuminate\Support\Carbon)) {
        return false;
    }

    $minutes = (int) config('filesystems.file.presigned_url_expiry', 5);
    $expected = now()->addMinutes($minutes);

    return abs($expiration->timestamp - $expected->timestamp) <= $toleranceSeconds;
}

/*
|--------------------------------------------------------------------------
| Global Setup
|--------------------------------------------------------------------------
|
| Here you can define global setup for your tests. This is useful for setting up
| things like database connections, fake data, or any other setup that should
| be done before any test runs.
|
*/

// Clear caches before all tests run
// This ensures fresh configuration and prevents memory issues
pest()->beforeAll(function () {
    shell_exec('php artisan config:clear');
});

pest()->afterAll(function () {
    shell_exec('php artisan config:clear');
});

// Global setup for Feature tests (Integration tests)
uses(RefreshDatabase::class)->in('Feature');
uses(WithFaker::class)->in('Feature');

// Global setup for Unit tests that need database
uses(RefreshDatabase::class)->in('Unit');
uses(WithFaker::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Groups
|--------------------------------------------------------------------------
|
| Define test groups for better organization and selective test running.
| You can run specific groups with: ./vendor/bin/pest --group=auth
|
*/

// Example: ->group('auth', 'api')
