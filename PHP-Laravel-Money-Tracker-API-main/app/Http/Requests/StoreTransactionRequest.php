<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * All users are authorized to create transactions.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for a new transaction.
     * - type: must be either income or expense
     * - amount: must be a positive number
     * - description: optional text
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
