<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * User Model
 *
 * Represents a user account in the money tracker system.
 * A user can own multiple wallets and track finances across them.
 */
class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * Get all wallets belonging to this user.
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Calculate the total balance across all wallets for this user.
     *
     * @return float
     */
    public function getTotalBalanceAttribute(): float
    {
        return $this->wallets->sum(fn($wallet) => $wallet->balance);
    }
}
