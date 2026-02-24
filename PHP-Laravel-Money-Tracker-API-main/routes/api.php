<?php

use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

// Users
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);

// Wallets
Route::get('/wallets', [WalletController::class, 'index']); // List all wallets
Route::post('/users/{user}/wallets', [WalletController::class, 'store']);
Route::get('/wallets/{wallet}', [WalletController::class, 'show']);

// Transactions
Route::post('/wallets/{wallet}/transactions', [TransactionController::class, 'store']);
Route::get('/wallets/{wallet}/transactions', [TransactionController::class, 'index']); 
