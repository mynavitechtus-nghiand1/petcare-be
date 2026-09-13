<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $page    = $request->integer('page', 1);
        $perPage = min(max((int) $request->integer('per_page', 10), 1), 50);
        $cacheKey = "brands.list.p{$page}.pp{$perPage}";
        
        $brands = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage) {
            return Brand::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->paginate($perPage);
        });

        return ApiResponse::paginated($brands, 'Brands list', $request);
    }

    public function show(Brand $brand): JsonResponse
    {
        if (!$brand->is_active) {
            return ApiResponse::error('Brand not found', 404);
        }

        $brand->load([
            'products' => fn($query) => $query
                ->where('status', 'published')
                ->orderBy('name'),
        ]);

        return ApiResponse::success($brand, 'Brand detail');
    }
}
