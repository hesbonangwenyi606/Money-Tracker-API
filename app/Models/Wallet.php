<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Wallet Model
 *
 * Represents a wallet (account) owned by a user.
 * A wallet holds multiple transactions and tracks a running balance.
 */
class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    /**
     * Get the user that owns this wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions for this wallet.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate the current balance of the wallet.
     * Income adds to balance, Expense subtracts from balance.
     *
     * @return float
     */
    public function getBalanceAttribute(): float
    {
        $income  = $this->transactions()->where('type', 'income')->sum('amount');
        $expense = $this->transactions()->where('type', 'expense')->sum('amount');

        return $income - $expense;
    }
}
