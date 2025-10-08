<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\api\ProdukController;


// authentication
Route::get('/faspay-callback', [PesananController::class, 'faspayCallback']);
Route::get('/notification', [PesananController::class, 'responseFastpay']);
Route::post('/callback-tripay', [PesananController::class, 'handle']);

Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);
Route::post('/produk', [ProdukController::class, 'store']);
Route::put('/produk/{id}', [ProdukController::class, 'update']);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);
