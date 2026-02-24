<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWalletRequest extends FormRequest
{
    /**
     * All users are authorized to create wallets.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for a new wallet.
     * - name: required
     * - description: optional
     * - currency: optional 3-letter uppercase currency code (e.g. USD, KES)
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'size:3', 'regex:/^[A-Z]{3}$/'],
        ];
    }

    /**
     * Automatically uppercase the currency code before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('currency')) {
            $this->merge([
                'currency' => strtoupper((string) $this->input('currency')),
            ]);
        }
    }
}
