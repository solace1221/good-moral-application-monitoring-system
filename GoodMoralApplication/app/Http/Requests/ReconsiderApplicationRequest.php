<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReconsiderApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reconsider_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
