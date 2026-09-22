<?php

use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Learning API routes
|--------------------------------------------------------------------------
|
| Prefix is /api (see bootstrap/app.php).
| Public catalog + auth login. Cart/orders require Sanctum token.
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/health', [HealthController::class, 'show']);

    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{brand}', [BrandController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/auth/google', [GoogleController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

    Route::middleware(['auth:sanctum', 'ability:auth:refresh'])->group(function () {
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/cart', [CartController::class, 'show']);
        Route::post('/cart/items', [CartController::class, 'store']);
        Route::patch('/cart/items/{cartItem}', [CartController::class, 'update']);
        Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders/checkout', [OrderController::class, 'checkout']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        Route::get('/products', [AdminProductController::class, 'index']);
        Route::post('/products', [AdminProductController::class, 'store']);
        Route::put('/products/{product}', [AdminProductController::class, 'update']);
        Route::patch('/products/{product}', [AdminProductController::class, 'update']);
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);

        Route::get('/brands', [AdminBrandController::class, 'index']);
        Route::post('/brands', [AdminBrandController::class, 'store']);
        Route::put('/brands/{brand}', [AdminBrandController::class, 'update']);
        Route::patch('/brands/{brand}', [AdminBrandController::class, 'update']);
        Route::delete('/brands/{brand}', [AdminBrandController::class, 'destroy']);

        Route::get('/categories', [AdminCategoryController::class, 'index']);
        Route::post('/categories', [AdminCategoryController::class, 'store']);
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update']);
        Route::patch('/categories/{category}', [AdminCategoryController::class, 'update']);
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);

        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::patch('/orders/{order}', [AdminOrderController::class, 'update']);
    });
});
