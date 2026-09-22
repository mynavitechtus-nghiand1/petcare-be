<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->paginate(50);

        return ApiResponse::success($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255', 'unique:categories,name'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category = Category::create($data);

        return ApiResponse::success($category, 'Category created', 201);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $request->validate([
            'name'       => ['sometimes', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return ApiResponse::success($category->fresh(), 'Category updated');
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->update(['is_active' => false]);

        return ApiResponse::success(null, 'Category deactivated');
    }
}
