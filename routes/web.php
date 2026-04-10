<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\RiwayatPesananController;

Route::get('/', [BrandaController::class, 'index'])
    ->name('Branda');
Route::post('/cart/add', [BrandaController::class, 'cart_add'])
    ->name('cart.add');

Route::get('/meja/{nomor}', [MejaController::class, 'setMeja']);

Route::prefix('cart')->group(function () {
    Route::get('/checkout', [CheckOutController::class, 'index'])
        ->name('checkout');
    Route::post('/note', [CheckOutController::class, 'note'])
        ->name('cart.note');
});

Route::get('/cart', [CheckOutController::class, 'cart'])
    ->name('cart.get');

Route::post('/cart/update', [CheckOutController::class, 'update'])
    ->name('cart.update');

Route::post('/cart/remove', [CheckOutController::class, 'remove'])->name('cart.remove');

Route::get('/pemesanan', [PemesananController::class, 'index'])
    ->name('Pemesanan');

Route::get('/DetailMenu/{id}', [BrandaController::class, 'show'])
    ->name('detail.menu');

Route::post('/checkout/process', [CheckOutController::class, 'process'])
    ->name('checkout.process');
Route::post('/pemesanan/simpan', [PemesananController::class, 'simpan'])
    ->name('pemesanan.simpan');

Route::get('/riwayat-pesanan', [RiwayatPesananController::class, 'index'])
    ->name('riwayat.pesanan');

Route::post('/create-invoice', [PaymentController::class, 'createInvoice'])
    ->name('invoice.create');

Route::get('/payment/{id}', [PaymentController::class, 'show'])
    ->name('payment.show');

Route::get('/riwayat/data', [RiwayatPesananController::class, 'getRiwayat']);

Route::post('/pay-again/{pesanan}', [PaymentController::class, 'payAgain'])
    ->name('pay.again');

Route::post('/cart/destroy', function () {
    session()->forget('cart');

    return response()->json(['success' => true]);
})->name('cart.destroy');

Route::get('/cek-status/{id}', [PaymentController::class, 'cekStatus']);

Route::get('/riwayat', [RiwayatPesananController::class, 'index'])->name('riwayat');

Route::get('/search-menu', [BrandaController::class, 'search'])->name('search.menu');


Route::post('/cart/delete', [BrandaController::class, 'cart_delete'])->name('cart.delete');

Route::get('/pesan-lagi', function () {
    session()->forget('cart');
    return redirect()->route('Branda');
})->name('pesan.lagi');