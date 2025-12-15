<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransaksiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// User API Routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Transaksi API Routes
Route::get('/transaksis', [TransaksiController::class, 'index']);
Route::get('/transaksis/{id}', [TransaksiController::class, 'show']);
Route::post('/transaksis', [TransaksiController::class, 'store']);
Route::delete('/transaksis/{id}', [TransaksiController::class, 'destroy']);
