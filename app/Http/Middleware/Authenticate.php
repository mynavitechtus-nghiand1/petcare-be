<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * 
     * Since this is an API-only application, we always return null to prevent redirect.
     * The unauthenticated() method in Handler will handle the JSON response.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Always return null to prevent redirect
        // The unauthenticated() method in Handler will handle the response
        return null;
    }
}

