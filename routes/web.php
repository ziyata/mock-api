<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes (Laravel baru, termasuk API)
|--------------------------------------------------------------------------
*/

/** @var Router $router */
$router = app('router');

// ROUTE TEST TANPA CSRF
$router->post('/api/orders-no-csrf', function (Request $request) {
    $orderId = 'ORD-' . now()->format('YmdHis');
    return response()->json([
        'success' => true,
        'order_id' => $orderId,
        'total' => $request->input('total', 0),
    ]);
})->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);


Route::get('/api/orders', function() {
    return response()->json(['orders' => []]);
});


// ===== MOCK API UNTUK ANDROID =====

Route::post('/api/orders', function (Request $request) {
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
    ]);
});

Route::post('/api/create-qris', function (Request $request) {
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
    ]);
});

Route::get('/api/order-status/{order_id}', function ($orderId) {
    return response()->json([
        'order_id' => $orderId,
        'status' => 'settlement',
        'message' => 'Pembayaran berhasil (mock)',
        'updated_at' => now()->format('Y-m-d H:i:s'),
    ]);
});
