<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string'],
            'rejection_details' => ['nullable', 'string'],
            'specify_reason' => ['required_if:rejection_reason,Others', 'nullable', 'string'],
        ];
    }
}
