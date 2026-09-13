<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = min(max($perPage, 1), 50);

        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with(['items', 'payments'])
            ->orderByDesc('id')
            ->paginate($perPage);

        return ApiResponse::paginated($orders, 'Orders list', $request);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return ApiResponse::error('Order not found', 404);
        }

        $order->load(['items', 'payments']);

        return ApiResponse::success($order, 'Order detail');
    }

    public function checkout(Request $request): JsonResponse
    {
        $data = $request->validate([
            'currency'         => ['sometimes', 'string', 'in:VND,USD'],
            'voucher_code'     => ['sometimes', 'nullable', 'string'],
            'shipping_name'    => ['nullable', 'string', 'max:255'],
            'shipping_phone'   => ['nullable', 'string', 'max:50'],
            'shipping_address' => ['nullable', 'string'],
        ]);

        $currency = $data['currency'] ?? 'VND';

        $voucher = null;
        if (!empty($data['voucher_code'])) {
            $voucher = Voucher::where('code', $data['voucher_code'])->first();
            if (!$voucher || !$voucher->isValid()) {
                return ApiResponse::error('Voucher không hợp lệ hoặc đã hết hạn', 422);
            }
        }

        try {
            $order = DB::transaction(function () use ($request, $data, $currency, $voucher) {
                $cart = Cart::query()
                    ->where('user_id', $request->user()->id)
                    ->with(['items.product.prices', 'items.product.inventory'])
                    ->lockForUpdate()
                    ->first();

                if (!$cart || $cart->items->isEmpty()) {
                    abort(422, 'Cart is empty');
                }

                $subtotal = 0;
                $orderItemsPayload = [];

                foreach ($cart->items as $item) {
                    $product = $item->product;

                    if ($product->status !== 'published') {
                        abort(422, "Product {$product->name} is not available");
                    }

                    $price = $product->prices->firstWhere('currency', $currency);
                    if (!$price) {
                        abort(422, "Product {$product->name} has no price in {$currency}");
                    }

                    $inventory = $product->inventory;
                    if (!$inventory) {
                        abort(422, "Product {$product->name} has no inventory");
                    }

                    $affected = Inventory::query()
                        ->where('id', $inventory->id)
                        ->where('version', $inventory->version)
                        ->where('quantity', '>=', $item->quantity)
                        ->update([
                            'quantity' => DB::raw('quantity - ' . (int) $item->quantity),
                            'version' => DB::raw('version + 1'),
                        ]);

                    if ($affected === 0) {
                        abort(409, "Insufficient stock or concurrent update for {$product->name}");
                    }

                    $lineTotal = $price->amount * $item->quantity;
                    $subtotal += $lineTotal;

                    $orderItemsPayload[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'sku' => $product->sku,
                        'unit_price' => $price->amount,
                        'currency' => $currency,
                        'quantity' => $item->quantity,
                        'line_total' => $lineTotal,
                    ];
                }

                $discount = $voucher ? min($voucher->discount_amount, $subtotal) : 0;
                $finalAmount = $subtotal - $discount;

                $order = Order::create([
                    'user_id'          => $request->user()->id,
                    'voucher_id'       => $voucher?->id,
                    'status'           => 'pending',
                    'currency'         => $currency,
                    'subtotal_amount'  => $finalAmount,
                    'shipping_name'    => $data['shipping_name'] ?? null,
                    'shipping_phone'   => $data['shipping_phone'] ?? null,
                    'shipping_address' => $data['shipping_address'] ?? null,
                ]);

                foreach ($orderItemsPayload as $payload) {
                    OrderItem::create(array_merge(['order_id' => $order->id], $payload));
                }

                Payment::create([
                    'order_id'     => $order->id,
                    'status'       => 'paid',
                    'amount'       => $finalAmount,
                    'currency'     => $currency,
                    'provider'     => 'mock',
                    'provider_ref' => 'mock_' . $order->id,
                ]);

                $order->update(['status' => 'paid']);

                $cart->items()->delete();

                return $order->load(['items', 'payments']);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return ApiResponse::error($e->getMessage(), $e->getStatusCode());
        }

        return ApiResponse::success($order, 'Checkout successful', 201);
    }
}
