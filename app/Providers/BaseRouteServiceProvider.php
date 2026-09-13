<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * Base Route Service Provider for Foundation
 * 
 * This can be extended by different development patterns:
 * - Module pattern: can extend this for consistent routing
 * - Repository pattern: can use this for API routes
 * - DDD pattern: can extend for domain-specific routing
 * - Service pattern: can use for service-based routes
 */
abstract class BaseRouteServiceProvider extends ServiceProvider
{
    /**
     * The prefix for API routes.
     */
    protected string $apiPrefix = 'api';
    
    /**
     * The API version prefix.
     */
    protected string $apiVersion = 'v1';

    /**
     * Called before routes are registered.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     * Override this method in concrete implementations.
     */
    abstract protected function mapWebRoutes(): void;

    /**
     * Define the "api" routes for the application.
     * Override this method in concrete implementations.
     */
    abstract protected function mapApiRoutes(): void;

    /**
     * Helper method to register API routes with common configuration.
     */
    protected function registerApiRoutes(string $routeFile, ?string $prefix = null, ?string $namespace = null): void
    {
        Route::middleware('api')
            ->prefix($this->apiPrefix . '/' . ($prefix ?? $this->apiVersion))
            ->namespace($namespace)
            ->group($routeFile);
    }

    /**
     * Helper method to register web routes with common configuration.
     */
    protected function registerWebRoutes(string $routeFile, ?string $prefix = null, ?string $namespace = null): void
    {
        Route::middleware('web')
            ->prefix($prefix)
            ->namespace($namespace)
            ->group($routeFile);
    }
}
