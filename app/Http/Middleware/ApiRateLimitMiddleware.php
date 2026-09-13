<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ApiResponse;
use App\Exceptions\RateLimitException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class ApiRateLimitMiddleware
{
    protected int $maxAttempts = 1000;
    protected int $decayMinutes = 60;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->resolveMaxAttempts($request);

        if ($this->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->getTimeUntilNextRetry($key);
            
            $e = new RateLimitException(null, [
                'max_attempts' => $maxAttempts,
                'remaining_attempts' => 0,
                'retry_after_seconds' => $retryAfter,
                'retry_after_human' => gmdate('H:i:s', $retryAfter),
            ]);
            return ApiResponse::fromException($e);
        }

        $this->incrementAttempts($key);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Generate rate limit key for the request.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return sha1('api_limit:user:' . $user->id);
        }

        return sha1('api_limit:ip:' . $request->ip());
    }

    /**
     * Determine max attempts for the request.
     */
    protected function resolveMaxAttempts(Request $request): int
    {
        // Different limits for authenticated vs unauthenticated users
        if ($user = $request->user()) {
            // Premium users get higher limits (in real app, check user type)
            return config('core.api.rate_limit.authenticated', $this->maxAttempts);
        }

        return config('core.api.rate_limit.unauthenticated', 100);
    }

    /**
     * Check if too many attempts have been made.
     */
    protected function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        return $this->attempts($key) >= $maxAttempts;
    }

    /**
     * Get current attempt count.
     */
    protected function attempts(string $key): int
    {
        return (int) Cache::get($key, 0);
    }

    /**
     * Increment the attempts count.
     */
    protected function incrementAttempts(string $key): void
    {
        Cache::add($key, 0, now()->addMinutes($this->decayMinutes));
        Cache::increment($key);
    }

    /**
     * Calculate remaining attempts.
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return max(0, $maxAttempts - $this->attempts($key));
    }

    /**
     * Build rate limit exceeded response.
     */
    protected function buildRateLimitResponse(string $key, int $maxAttempts): \Illuminate\Http\JsonResponse
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);
        $remaining = $this->calculateRemainingAttempts($key, $maxAttempts);

        $response = ApiResponse::tooManyRequests('Rate limit exceeded. Try again later.');

        return $response->withHeaders([
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remaining,
            'X-RateLimit-Reset' => now()->addMinutes($this->decayMinutes)->timestamp,
        ]);
    }

    /**
     * Add rate limit headers to response.
     */
    protected function addHeaders($response, int $maxAttempts, int $remaining)
    {
        if (property_exists($response, 'headers') && is_object($response->headers)) {
            $response->headers->set('X-RateLimit-Limit', $maxAttempts);
            $response->headers->set('X-RateLimit-Remaining', $remaining);
            $response->headers->set('X-RateLimit-Reset', now()->addMinutes($this->decayMinutes)->timestamp);
        }

        return $response;
    }

    /**
     * Get time until next retry allowed.
     */
    protected function getTimeUntilNextRetry(string $key): int
    {
        $resetTime = Cache::get($key . '_reset');
        
        if (!$resetTime) {
            $resetTime = now()->addMinutes($this->decayMinutes);
            Cache::put($key . '_reset', $resetTime, $resetTime);
        }

        return max(1, $resetTime->diffInSeconds(now()));
    }
}
