<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Seeds the database with a sample user, wallets, and transactions
 * for development and testing purposes.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a sample user
        $user = User::create([
            'name'  => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        // Create a personal wallet
        $personalWallet = Wallet::create([
            'user_id'     => $user->id,
            'name'        => 'Personal',
            'description' => 'Day-to-day personal expenses',
        ]);

        // Create a business wallet
        $businessWallet = Wallet::create([
            'user_id'     => $user->id,
            'name'        => 'Business',
            'description' => 'Business income and expenses',
        ]);

        // Add transactions to personal wallet
        $personalTransactions = [
            ['type' => 'income',  'amount' => 3000.00, 'description' => 'Monthly salary',        'date' => '2024-01-01'],
            ['type' => 'expense', 'amount' => 850.00,  'description' => 'Rent',                  'date' => '2024-01-02'],
            ['type' => 'expense', 'amount' => 120.00,  'description' => 'Groceries',             'date' => '2024-01-05'],
            ['type' => 'expense', 'amount' => 45.00,   'description' => 'Electricity bill',      'date' => '2024-01-10'],
            ['type' => 'income',  'amount' => 200.00,  'description' => 'Freelance side income', 'date' => '2024-01-15'],
        ];

        foreach ($personalTransactions as $tx) {
            Transaction::create(array_merge($tx, ['wallet_id' => $personalWallet->id]));
        }

        // Add transactions to business wallet
        $businessTransactions = [
            ['type' => 'income',  'amount' => 5000.00, 'description' => 'Client payment - Project A', 'date' => '2024-01-03'],
            ['type' => 'expense', 'amount' => 300.00,  'description' => 'Software subscriptions',     'date' => '2024-01-04'],
            ['type' => 'expense', 'amount' => 150.00,  'description' => 'Marketing spend',            'date' => '2024-01-08'],
            ['type' => 'income',  'amount' => 2500.00, 'description' => 'Client payment - Project B', 'date' => '2024-01-20'],
        ];

        foreach ($businessTransactions as $tx) {
            Transaction::create(array_merge($tx, ['wallet_id' => $businessWallet->id]));
        }
    }
}
