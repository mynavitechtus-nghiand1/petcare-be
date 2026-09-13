<?php

use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// Helper tạo product đầy đủ (price + inventory)
function createProduct(): Product
{
    $brand   = Brand::factory()->create();
    $product = Product::factory()->create([
        'brand_id' => $brand->id,
        'status'   => 'published',
    ]);

    ProductPrice::create([
        'product_id' => $product->id,
        'currency'   => 'VND',
        'amount'     => 100000,
    ]);

    Inventory::create([
        'product_id' => $product->id,
        'quantity'   => 10,
        'version'    => 0,
    ]);

    return $product;
}

test('checkout thành công với giỏ hàng hợp lệ', function () {
    $user    = User::factory()->create();
    $product = createProduct();

    $cart = Cart::create(['user_id' => $user->id]);
    CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 2]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/orders/checkout');

    $response->assertStatus(201)
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.status', 'paid');

    // Kho giảm từ 10 → 8
    $this->assertDatabaseHas('inventories', [
        'product_id' => $product->id,
        'quantity'   => 8,
    ]);

    // Giỏ bị xóa sau checkout
    $this->assertDatabaseEmpty('cart_items');
});

test('checkout thất bại khi giỏ trống', function () {
    $user = User::factory()->create();
    Cart::create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/orders/checkout');

    $response->assertStatus(422);
});
