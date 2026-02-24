<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreTransactionRequest
 *
 * Validates incoming data for adding a transaction to a wallet.
 * Ensures type is either 'income' or 'expense' and amount is positive.
 */
class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type'        => ['required', 'in:income,expense'],
            'amount'      => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:500'],
            'date'        => ['nullable', 'date'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'type.required'  => 'A transaction type is required.',
            'type.in'        => 'Transaction type must be either "income" or "expense".',
            'amount.required' => 'An amount is required.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.gt'      => 'Amount must be a positive number greater than zero.',
            'date.date'      => 'Please provide a valid date.',
        ];
    }

    /**
     * Prepare data for validation — set a default date to today if not provided.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('date') || $this->date === null) {
            $this->merge(['date' => now()->toDateString()]);
        }
    }
}
