<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/xendit/callback', [App\Http\Controllers\Api\XenditCallbackController::class, '__invoke'])->name('xendit.callback');


Route::post('/xendit/webhook', [PaymentController::class, 'webhook']);