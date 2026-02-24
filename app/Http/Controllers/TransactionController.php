<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

/**
 * TransactionController
 *
 * Handles adding income and expense transactions to a wallet.
 */
class TransactionController extends Controller
{
    /**
     * Add a new transaction (income or expense) to a wallet.
     *
     * POST /api/wallets/{wallet}/transactions
     *
     * @param StoreTransactionRequest $request
     * @param Wallet $wallet
     * @return JsonResponse
     */
    public function store(StoreTransactionRequest $request, Wallet $wallet): JsonResponse
    {
        $transaction = $wallet->transactions()->create($request->validated());

        // Return the updated wallet balance alongside the new transaction
        return response()->json([
            'message' => 'Transaction added successfully.',
            'data'    => [
                'transaction'   => [
                    'id'          => $transaction->id,
                    'type'        => $transaction->type,
                    'amount'      => $transaction->amount,
                    'description' => $transaction->description,
                    'date'        => $transaction->date?->format('Y-m-d'),
                    'created_at'  => $transaction->created_at,
                ],
                'wallet_balance' => $wallet->balance,
            ],
        ], 201);
    }
}
