<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * All requests are authorized - no authentication needed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a new user.
     * - name: required
     * - email: required, valid format, must be unique
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ];
    }
}
