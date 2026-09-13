<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $page       = $request->integer('page', 1);
        $perPage    = min(max((int) $request->integer('per_page', 10), 1), 50);
        $brandId    = $request->integer('brand_id') ?: null;
        $categoryId = $request->integer('category_id') ?: null;
        $q          = $request->string('q')->trim()->toString() ?: null;

        // Cache chỉ khi không có filter — filter thường unique, cache vô nghĩa
        $cacheKey = $brandId || $categoryId || $q
            ? null
            : "products.list.p{$page}.pp{$perPage}";

        $fetch = function () use ($perPage, $brandId, $categoryId, $q) {
            return Product::query()
                ->where('status', 'published')
                ->when($brandId, fn($query) => $query->where('brand_id', $brandId))
                ->when($categoryId, fn($query) => $query->whereHas('categories', fn($q) => $q->where('categories.id', $categoryId)))
                ->when($q, fn($query) => $query->where('name', 'ilike', "%{$q}%"))
                ->with(['brand', 'prices', 'inventory'])
                ->orderBy('name')
                ->paginate($perPage);
        };

        $products = $cacheKey
            ? Cache::remember($cacheKey, now()->addMinutes(5), $fetch)
            : $fetch();

        return ApiResponse::paginated($products, 'Products list', $request);
    }

    public function show(Product $product): JsonResponse
    {
        if ($product->status !== 'published') {
            return ApiResponse::error('Product not found', 404);
        }

        $product->load(['brand', 'categories', 'prices', 'inventory']);

        return ApiResponse::success($product, 'Product detail');
    }
}
