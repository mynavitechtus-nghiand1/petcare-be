<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $page    = $request->integer('page', 1);
        $perPage = min(max((int) $request->integer('per_page', 10), 1), 50);
        $cacheKey = "categories.list.p{$page}.pp{$perPage}";

        $categories = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage) {
            return Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate($perPage);
        });

        return ApiResponse::paginated($categories, 'Categories list', $request);
    }

    public function show(Category $category): JsonResponse
    {
        if (!$category->is_active) {
            return ApiResponse::error('Category not found', 404);
        }

        $category->load(['products' => fn ($query) => $query
            ->where('status', 'published')
            ->orderBy('name'),
        ]);

        return ApiResponse::success($category, 'Category detail');
    }
}
