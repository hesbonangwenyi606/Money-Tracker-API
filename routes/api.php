<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Money Tracker API Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api automatically by Laravel.
|
| Route Summary:
|   POST   /api/users                          → Create a user
|   GET    /api/users/{user}                   → Get user profile (wallets + balances)
|   POST   /api/users/{user}/wallets           → Create a wallet for a user
|   GET    /api/wallets/{wallet}               → Get wallet detail (balance + transactions)
|   POST   /api/wallets/{wallet}/transactions  → Add a transaction to a wallet
|
*/

// --- User Routes ---
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);

// --- Wallet Routes ---
Route::post('/users/{user}/wallets', [WalletController::class, 'store']);
Route::get('/wallets/{wallet}', [WalletController::class, 'show']);

// --- Transaction Routes ---
Route::post('/wallets/{wallet}/transactions', [TransactionController::class, 'store']);
