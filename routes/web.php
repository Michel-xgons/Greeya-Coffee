<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\PemesananController;

Route::get('/', [BrandaController::class, 'index'])
    ->name('Branda');
Route::post('/cart/add', [BrandaController::class, 'cart_add'])
    ->name('cart.add');

Route::prefix('cart')->group(function () {
    Route::get('/checkout', [CheckOutController::class, 'index'])
        ->name('checkout');
    Route::post('/note', [CheckOutController::class, 'note'])
    ->name('cart.note');
});
Route::post('/cart/update', [CheckOutController::class, 'update'])
    ->name('cart.update');

Route::get('/pemesanan', [PemesananController::class, 'index'])
    ->name('Pemesanan');
Route::get('/DetailMenu/{id}', [BrandaController::class, 'show'])
    ->name('detail.menu');
Route::post('/pemesanan/simpan', [PemesananController::class, 'simpan'])
    ->name('pemesanan.simpan');
Route::post('/xendit/invoice', [PaymentController::class, 'createInvoice']);
