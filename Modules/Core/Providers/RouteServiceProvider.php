<?php

namespace Modules\Core\Providers;

use App\Providers\BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected string $name = 'Core';

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        $this->registerWebRoutes(
            module_path($this->name, '/routes/web.php')
        );
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        $this->registerApiRoutes(
            module_path($this->name, '/routes/api.php'),
            null,
            'Modules\Core\Http\Controllers'
        );
    }
}
