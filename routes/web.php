<?php
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Order
Route::get('/pesan', [OrderController::class, 'create'])->name('order.create');
Route::post('/pesan', [OrderController::class, 'store'])->name('order.store');
Route::get('/pesan/sukses/{order}', [OrderController::class, 'success'])->name('order.success');
Route::get('/cek-status', [OrderController::class, 'check'])->name('order.check');
