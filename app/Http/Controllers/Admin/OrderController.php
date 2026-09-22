<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 50);
        $status  = $request->string('status')->toString() ?: null;

        $orders = Order::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->with(['user', 'items', 'payments'])
            ->orderByDesc('id')
            ->paginate($perPage);

        return ApiResponse::paginated($orders, 'Orders list', $request);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,paid,processing,shipped,delivered,cancelled,refunded'],
        ]);

        $order->update($data);

        return ApiResponse::success($order->fresh()->load(['items', 'payments']), 'Order updated');
    }
}
