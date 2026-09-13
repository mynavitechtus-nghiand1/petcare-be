<?php

use App\Models\Brand;
use App\Models\Product;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('list products trả về chỉ published', function () {
    $brand = Brand::factory()->create();

    Product::factory()->create(['brand_id' => $brand->id, 'status' => 'published']);
    Product::factory()->create(['brand_id' => $brand->id, 'status' => 'draft']);

    $response = $this->getJson('/api/v1/products');

    $response->assertStatus(200)
             ->assertJsonPath('success', true)
             ->assertJsonCount(1, 'data');
});

test('list products filter theo brand_id', function () {
    $brand1 = Brand::factory()->create();
    $brand2 = Brand::factory()->create();

    Product::factory()->create(['brand_id' => $brand1->id, 'status' => 'published']);
    Product::factory()->create(['brand_id' => $brand2->id, 'status' => 'published']);

    $response = $this->getJson("/api/v1/products?brand_id={$brand1->id}");

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data');
});

test('list products search theo tên', function () {
    $brand = Brand::factory()->create();

    Product::factory()->create(['brand_id' => $brand->id, 'name' => 'Royal Canin Adult', 'status' => 'published']);
    Product::factory()->create(['brand_id' => $brand->id, 'name' => 'Pedigree Puppy', 'status' => 'published']);

    $response = $this->getJson('/api/v1/products?q=royal');

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data');
});
