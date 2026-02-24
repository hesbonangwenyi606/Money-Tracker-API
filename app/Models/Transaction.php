<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Transaction Model
 *
 * Represents a single financial transaction (income or expense)
 * associated with a specific wallet.
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'wallet_id',
        'type',        // 'income' or 'expense'
        'amount',
        'description',
        'date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'float',
        'date'   => 'date',
    ];

    /**
     * Get the wallet this transaction belongs to.
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
