<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        $brands = Brand::orderBy('name')->paginate(50);

        return ApiResponse::success($brands);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', 'unique:brands,name'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $brand = Brand::create($data);

        return ApiResponse::success($brand, 'Brand created', 201);
    }

    public function update(Request $request, Brand $brand): JsonResponse
    {
        $data = $request->validate([
            'name'      => ['sometimes', 'string', 'max:255', 'unique:brands,name,' . $brand->id],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $brand->update($data);

        return ApiResponse::success($brand->fresh(), 'Brand updated');
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $brand->update(['is_active' => false]);

        return ApiResponse::success(null, 'Brand deactivated');
    }
}
