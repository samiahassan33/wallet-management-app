<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\walletController;
use App\Http\Controllers\Api\TransactionController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('{id}', [UserController::class, 'show']);
});

Route::prefix('wallet')->group(function () {
    Route::post('deposit', [walletController::class, 'deposit']);
    Route::post('withdraw', [walletController::class, 'withdraw']);
});

Route::get('{id}/transactions', [TransactionController::class, 'index']);
Route::post('transactions/transfer', [TransactionController::class, 'transfer']);

