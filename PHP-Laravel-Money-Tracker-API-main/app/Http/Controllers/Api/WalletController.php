<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    /**
     * Display a listing of all wallets.
     */
    public function index(): JsonResponse
    {
        $wallets = Wallet::all();

        return response()->json(WalletResource::collection($wallets));
    }

    /**
     * Store a new wallet for a given user.
     * Defaults currency to USD if not provided.
     */
    public function store(StoreWalletRequest $request, User $user): JsonResponse
    {
        $payload = $request->validated();

        // Default to USD if no currency is specified
        $payload['currency'] = $payload['currency'] ?? 'USD';

        $wallet = $user->wallets()->create($payload);

        return (new WalletResource($wallet))->response()->setStatusCode(201);
    }

    /**
     * Display a specific wallet with its balance and paginated transactions.
     * Balance is calculated as total income minus total expenses (in cents).
     */
    public function show(Wallet $wallet): JsonResponse
    {
        // Load income and expense totals using conditional sums
        $wallet->loadSum(['transactions as income_total' => function ($query) {
            $query->where('type', 'income');
        }], 'amount_cents');

        $wallet->loadSum(['transactions as expense_total' => function ($query) {
            $query->where('type', 'expense');
        }], 'amount_cents');

        // Calculate balance: income - expenses
        $income = (int) ($wallet->income_total ?? 0);
        $expense = (int) ($wallet->expense_total ?? 0);
        $wallet->balance_cents = $income - $expense;

        // Paginate transactions, default 15 per page (max 100)
        $perPage = min(max((int) request()->query('per_page', 15), 1), 100);
        $transactions = $wallet->transactions()
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'wallet' => new WalletResource($wallet),
            'transactions' => TransactionResource::collection($transactions)->response()->getData(true),
        ]);
    }
}
