<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get language from X-Language header or default to Japanese
        $language = $request->header('X-Language', 'ja');
        
        // Set locale for email translations
        App::setLocale($language);
        
        return $next($request);
    }
}
