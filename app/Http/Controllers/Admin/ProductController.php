<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'brand_id'     => ['required', 'integer', 'exists:brands,id'],
            'name'         => ['required', 'string', 'max:255'],
            'sku'          => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description'  => ['nullable', 'string'],
            'product_type' => ['sometimes', 'string', 'in:single'],
            'status'       => ['sometimes', 'string', 'in:draft,published'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $product = Product::create($data);

        return ApiResponse::success($product, 'Product created', 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'brand_id'    => ['sometimes', 'integer', 'exists:brands,id'],
            'name'        => ['sometimes', 'string', 'max:255'],
            'sku'         => ['sometimes', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'status'      => ['sometimes', 'string', 'in:draft,published'],
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $product->update($data);

        return ApiResponse::success($product->fresh(), 'Product updated');
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return ApiResponse::success(null, 'Product deleted');
    }
}
