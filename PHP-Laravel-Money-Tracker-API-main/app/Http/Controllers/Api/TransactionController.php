<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Display all transactions for a given wallet.
     * Returns transactions ordered by most recent first.
     */
    public function index(Wallet $wallet): JsonResponse
    {
        $transactions = $wallet->transactions()->latest()->get();

        return response()->json(TransactionResource::collection($transactions));
    }

    /**
     * Store a new transaction for a given wallet.
     * Accepts type (income/expense), amount, and optional description.
     * Amount is stored in both decimal and cents format.
     */
    public function store(StoreTransactionRequest $request, Wallet $wallet): JsonResponse
    {
        $validated = $request->validated();

        // Convert amount to float and calculate cents for precision
        $amount = (float) $validated['amount'];
        $validated['amount'] = $amount;
        $validated['amount_cents'] = (int) round($amount * 100);

        $transaction = $wallet->transactions()->create($validated);

        return (new TransactionResource($transaction))->response()->setStatusCode(201);
    }
}
