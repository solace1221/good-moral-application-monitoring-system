<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViolationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offense_type' => ['required', 'in:minor,major'],
            'description' => ['required', 'string', 'max:255'],
            'article' => ['nullable', 'string', 'max:100'],
        ];
    }
}
