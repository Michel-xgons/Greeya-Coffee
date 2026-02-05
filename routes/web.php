<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\DetailMenuController;

Route:: get('/',[BrandaController::class,'index']) 
->name ('Branda');
Route:: get('/checkout',[CheckOutController::class,'index']) 
->name ('CekOut');
Route:: get('/pemesanan',[PemesananController::class,'index']) 
->name ('Pemesanan');
Route:: get('/DetailMenu', [DetailMenuController::class, 'index'])
    ->name('detail.menu');
Route::post('/pemesanan/simpan', [PemesananController::class, 'simpan'])
    ->name('pemesanan.simpan');
Route::post('/xendit/invoice', [PaymentController::class, 'createInvoice']);



