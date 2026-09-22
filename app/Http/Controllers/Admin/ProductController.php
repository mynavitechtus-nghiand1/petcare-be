<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::with(['brand', 'prices', 'inventory'])->paginate(20);

        return ApiResponse::success($products);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'brand_id'     => ['required', 'integer', 'exists:brands,id'],
            'name'         => ['required', 'string', 'max:255'],
            'sku'          => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description'  => ['nullable', 'string'],
            'product_type' => ['sometimes', 'string', 'in:single'],
            'status'       => ['sometimes', 'string', 'in:draft,published'],
            'price'        => ['sometimes', 'integer', 'min:0'],
            'currency'     => ['sometimes', 'string', 'max:3'],
            'quantity'     => ['sometimes', 'integer', 'min:0'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $product = Product::create($data);

        if (isset($data['price'])) {
            ProductPrice::create([
                'product_id' => $product->id,
                'currency'   => $data['currency'] ?? 'JPY',
                'amount'     => $data['price'],
            ]);
        }

        if (isset($data['quantity'])) {
            Inventory::create([
                'product_id' => $product->id,
                'quantity'   => $data['quantity'],
                'version'    => 1,
            ]);
        }

        return ApiResponse::success($product->load(['brand', 'prices', 'inventory']), 'Product created', 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'brand_id'    => ['sometimes', 'integer', 'exists:brands,id'],
            'name'        => ['sometimes', 'string', 'max:255'],
            'sku'         => ['sometimes', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'status'      => ['sometimes', 'string', 'in:draft,published'],
            'price'       => ['sometimes', 'integer', 'min:0'],
            'currency'    => ['sometimes', 'string', 'max:3'],
            'quantity'    => ['sometimes', 'integer', 'min:0'],
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $product->update($data);

        if (isset($data['price'])) {
            $currency = $data['currency'] ?? 'JPY';
            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'currency' => $currency],
                ['amount' => $data['price']]
            );
        }

        if (isset($data['quantity'])) {
            Inventory::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $data['quantity']]
            );
        }

        return ApiResponse::success($product->fresh()->load(['brand', 'prices', 'inventory']), 'Product updated');
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return ApiResponse::success(null, 'Product deleted');
    }
}
