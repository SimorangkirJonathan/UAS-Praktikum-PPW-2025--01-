<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

Route::post('/users', [UserController::class, 'store']);
Route::get('/wallet/{user_id}', [WalletController::class, 'show']);
Route::post('/transfer', [TransactionController::class, 'transfer']);
