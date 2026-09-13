<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * First learning endpoint — proves the stack is alive.
 *
 * GET /api/v1/health
 */
class HealthController extends Controller
{
    public function show(): JsonResponse
    {
        $database = 'unknown';
        $redis = 'unknown';

        try {
            DB::connection()->getPdo();
            $database = 'ok';
        } catch (\Throwable) {
            $database = 'error';
        }

        try {
            Redis::ping();
            $redis = 'ok';
        } catch (\Throwable) {
            $redis = 'error';
        }

        return ApiResponse::success([
            'app' => config('app.name'),
            'env' => config('app.env'),
            'database' => $database,
            'redis' => $redis,
        ], 'PetCare learning API is running');
    }
}
