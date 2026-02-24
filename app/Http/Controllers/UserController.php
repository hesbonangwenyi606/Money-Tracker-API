<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * UserController
 *
 * Handles user account creation and profile retrieval.
 */
class UserController extends Controller
{
    /**
     * Create a new user account.
     *
     * POST /api/users
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User created successfully.',
            'data'    => $user,
        ], 201);
    }

    /**
     * Retrieve a user's profile including all wallets,
     * each wallet's balance, and total balance across all wallets.
     *
     * GET /api/users/{user}
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        // Eager load wallets with their transactions for balance calculation
        $user->load('wallets.transactions');

        // Build wallet data with balance for each wallet
        $wallets = $user->wallets->map(function ($wallet) {
            return [
                'id'          => $wallet->id,
                'name'        => $wallet->name,
                'description' => $wallet->description,
                'balance'     => $wallet->balance,
                'created_at'  => $wallet->created_at,
            ];
        });

        return response()->json([
            'data' => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'total_balance' => $user->total_balance,
                'wallets'       => $wallets,
                'created_at'    => $user->created_at,
            ],
        ]);
    }
}
