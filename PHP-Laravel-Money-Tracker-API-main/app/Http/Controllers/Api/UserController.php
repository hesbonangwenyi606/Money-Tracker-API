<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Store a new user account.
     * No authentication required - open registration.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * Display a user's profile including all wallets,
     * each wallet's balance, and the user's total balance.
     * Balance is calculated as income minus expenses in cents.
     */
    public function show(User $user): JsonResponse
    {
        // Load wallets with their income and expense totals
        $wallets = $user->wallets()
            ->withSum(['transactions as income_total' => function ($query) {
                $query->where('type', 'income');
            }], 'amount_cents')
            ->withSum(['transactions as expense_total' => function ($query) {
                $query->where('type', 'expense');
            }], 'amount_cents')
            ->get();

        // Calculate balance for each wallet
        $wallets->each(function ($wallet): void {
            $income = (int) ($wallet->income_total ?? 0);
            $expense = (int) ($wallet->expense_total ?? 0);
            $wallet->balance_cents = $income - $expense;
        });

        // Sum all wallet balances to get overall user balance
        $totalBalanceCents = $wallets->sum('balance_cents');

        $user->setRelation('wallets', $wallets);
        $user->total_balance_cents = $totalBalanceCents;

        return (new UserResource($user))->response();
    }
}
