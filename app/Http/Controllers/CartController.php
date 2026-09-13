<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product.brand', 'items.product.prices']);

        return ApiResponse::success($cart, 'Cart detail');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::where('id', $data['product_id'])
            ->where('status', 'published')
            ->first();

        if (!$product) {
            return ApiResponse::error('Product not available', 404);
        }

        $cart = $this->getOrCreateCart($request);

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->quantity = $item->exists
            ? $item->quantity + $data['quantity']
            : $data['quantity'];
        $item->save();

        $cart->load(['items.product.brand', 'items.product.prices']);

        return ApiResponse::success($cart, 'Item added to cart', 201);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorizeCartItem($request, $cartItem);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->update(['quantity' => $data['quantity']]);

        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product.brand', 'items.product.prices']);

        return ApiResponse::success($cart, 'Cart item updated');
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorizeCartItem($request, $cartItem);

        $cartItem->delete();

        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product.brand', 'items.product.prices']);

        return ApiResponse::success($cart, 'Cart item removed');
    }

    private function getOrCreateCart(Request $request): Cart
    {
        return Cart::firstOrCreate(['user_id' => $request->user()->id]);
    }

    private function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        if ($cartItem->cart?->user_id !== $request->user()->id) {
            abort(403, 'Forbidden');
        }
    }
}
