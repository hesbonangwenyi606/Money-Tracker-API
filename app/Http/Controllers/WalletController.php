<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

/**
 * WalletController
 *
 * Handles creation and retrieval of individual wallets.
 */
class WalletController extends Controller
{
    /**
     * Create a new wallet for a user.
     *
     * POST /api/users/{user}/wallets
     *
     * @param StoreWalletRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(StoreWalletRequest $request, User $user): JsonResponse
    {
        $wallet = $user->wallets()->create($request->validated());

        return response()->json([
            'message' => 'Wallet created successfully.',
            'data'    => [
                'id'          => $wallet->id,
                'name'        => $wallet->name,
                'description' => $wallet->description,
                'balance'     => 0.00,
                'created_at'  => $wallet->created_at,
            ],
        ], 201);
    }

    /**
     * Retrieve a specific wallet with its balance and all transactions.
     *
     * GET /api/wallets/{wallet}
     *
     * @param Wallet $wallet
     * @return JsonResponse
     */
    public function show(Wallet $wallet): JsonResponse
    {
        // Load transactions sorted by most recent date first
        $wallet->load(['transactions' => function ($query) {
            $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        }]);

        return response()->json([
            'data' => [
                'id'           => $wallet->id,
                'name'         => $wallet->name,
                'description'  => $wallet->description,
                'balance'      => $wallet->balance,
                'transactions' => $wallet->transactions->map(function ($tx) {
                    return [
                        'id'          => $tx->id,
                        'type'        => $tx->type,
                        'amount'      => $tx->amount,
                        'description' => $tx->description,
                        'date'        => $tx->date?->format('Y-m-d'),
                        'created_at'  => $tx->created_at,
                    ];
                }),
                'created_at'   => $wallet->created_at,
            ],
        ]);
    }
}
