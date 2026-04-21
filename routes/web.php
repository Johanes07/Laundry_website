<?php
use App\Http\Controllers\CourierLocationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReceiptController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Order
Route::get('/pesan', [OrderController::class, 'create'])->name('order.create');
Route::post('/pesan', [OrderController::class, 'store'])->name('order.store');
Route::get('/pesan/sukses/{order}', [OrderController::class, 'success'])->name('order.success');
Route::get('/cek-status', [OrderController::class, 'check'])->name('order.check');

// Tracking customer (publik)
Route::get('/track/{orderCode}', [CourierLocationController::class, 'show'])->name('tracking.show');
Route::post('/orders/{order}/customer-location', [CourierLocationController::class, 'customerLocation'])->name('orders.customer-location');

// Polling lokasi kurir (publik, untuk customer)
Route::get('/orders/{order}/courier-location-get', function (App\Models\Order $order) {
    $loc = $order->courierLocation;
    return response()->json($loc ? ['lat' => $loc->lat, 'lng' => $loc->lng] : []);
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}/receipt', [OrderReceiptController::class, 'show'])->name('orders.receipt');
    Route::get('/courier/{order}/active', [CourierLocationController::class, 'courierPage'])->name('courier.active');
    Route::post('/orders/{order}/courier-location', [CourierLocationController::class, 'update'])->name('api.courier-location');
});