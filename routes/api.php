<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/orders', function() {
    return response()->json([
        'success' => true,
        'orders' => [
            [
                'order_id' => 'ORD-20260110090000',
                'total' => 100000,
                'status' => 'completed',
            ],
        ]
    ]);
});

Route::post('/orders', function (Request $request) {
    $orderId = 'ORD-' . now()->format('YmdHis');
    return response()->json([
        'success' => true,
        'order_id' => $orderId,
        'message' => 'Order berhasil dibuat (mock)',
        'data' => [
            'order_id' => $orderId,
            'total' => $request->input('total', 0),
            'status' => 'pending',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ],
    ], 201);
});

Route::post('/create-qris', function (Request $request) {
    $orderId = $request->input('order_id', 'ORD-UNKNOWN');
    return response()->json([
        'success' => true,
        'order_id' => $orderId,
        'payment_method' => 'QRIS',
        'qris_data' => [
            'payment_url' => url("/mock-qris/{$orderId}"),
            'qr_string' => "MOCK-QRIS-{$orderId}",
            'deep_link' => "mockqris://pay?order_id={$orderId}",
            'status' => 'pending',
            'expires_at' => now()->addMinutes(15)->format('Y-m-d H:i:s'),
        ],
    ], 201);
});

Route::get('/order-status/{order_id}', function ($order_id) {
    return response()->json([
        'success' => true,
        'order_id' => $order_id,
        'status' => 'settlement',
        'message' => 'Pembayaran berhasil (mock)',
        'updated_at' => now()->format('Y-m-d H:i:s'),
    ]);
});

Route::get('/api/order-status/{order_id}', function ($order_id) {
    $allStatus = ['success', 'failed', 'expired', 'pending'];
    $status = $allStatus[array_rand($allStatus)];

    return response()->json([
        'success'   => true,
        'order_id'  => $order_id,
        'status'    => $status,
        'message'   => match ($status) {
            'success' => 'Pembayaran berhasil.',
            'failed'  => 'Pembayaran gagal.',
            'expired' => 'QR sudah kedaluwarsa.',
            'pending' => 'Menunggu pembayaran.',
        },
    ]);
});
