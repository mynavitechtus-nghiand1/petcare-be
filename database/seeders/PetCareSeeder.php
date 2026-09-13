<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class PetCareSeeder extends Seeder
{
    public function run(): void
    {
        // Brands
        $royal = Brand::firstOrCreate(
            ['slug' => 'royal-canin'],
            ['name' => 'Royal Canin', 'is_active' => true]
        );
        $pedigree = Brand::firstOrCreate(
            ['slug' => 'pedigree'],
            ['name' => 'Pedigree', 'is_active' => true]
        );

        // Categories
        $food = Category::firstOrCreate(
            ['slug' => 'thuc-an'],
            ['name' => 'Thức ăn', 'sort_order' => 1, 'is_active' => true]
        );
        Category::firstOrCreate(
            ['slug' => 'phu-kien'],
            ['name' => 'Phụ kiện', 'sort_order' => 2, 'is_active' => true]
        );
        Category::firstOrCreate(
            ['slug' => 've-sinh'],
            ['name' => 'Vệ sinh', 'sort_order' => 3, 'is_active' => true]
        );

        // Products
        $p1 = Product::firstOrCreate(
            ['sku' => 'RC-DOG-001'],
            [
                'brand_id' => $royal->id,
                'name' => 'Royal Canin Maxi Adult',
                'slug' => 'royal-canin-maxi-adult',
                'product_type' => 'single',
                'status' => 'published',
            ]
        );
        $p2 = Product::firstOrCreate(
            ['sku' => 'PD-DOG-001'],
            [
                'brand_id' => $pedigree->id,
                'name' => 'Pedigree Puppy',
                'slug' => 'pedigree-puppy',
                'product_type' => 'single',
                'status' => 'published',
            ]
        );

        // Product ↔ Category (pivot)
        $p1->categories()->syncWithoutDetaching([$food->id]);
        $p2->categories()->syncWithoutDetaching([$food->id]);

        // Prices (VND)
        ProductPrice::firstOrCreate(
            ['product_id' => $p1->id, 'currency' => 'VND'],
            ['amount' => 850000]
        );
        ProductPrice::firstOrCreate(
            ['product_id' => $p2->id, 'currency' => 'VND'],
            ['amount' => 320000]
        );

        // Inventories
        Inventory::firstOrCreate(
            ['product_id' => $p1->id],
            ['quantity' => 50, 'version' => 0]
        );
        Inventory::firstOrCreate(
            ['product_id' => $p2->id],
            ['quantity' => 100, 'version' => 0]
        );

        // User + Cart
        $user = User::firstOrCreate(
            ['email' => 'khach@petcare.test'],
            ['name' => 'Khách Test', 'password' => bcrypt('password')]
        );
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id]
        );
        CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $p1->id],
            ['quantity' => 2]
        );
        CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $p2->id],
            ['quantity' => 1]
        );

        // Vouchers
        Voucher::firstOrCreate(
            ['code' => 'SUMMER20'],
            ['discount_amount' => 50000, 'expires_at' => now()->addMonths(3), 'is_active' => true]
        );
        Voucher::firstOrCreate(
            ['code' => 'WELCOME10'],
            ['discount_amount' => 20000, 'expires_at' => null, 'is_active' => true]
        );
    }
}
