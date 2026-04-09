<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeanRejectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:255'],
            'rejection_details' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
