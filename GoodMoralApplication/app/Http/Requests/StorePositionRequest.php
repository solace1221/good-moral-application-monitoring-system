<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dsn_id' => ['required', 'exists:designations,id'],
            'position_title' => ['required', 'string', 'max:255'],
        ];
    }
}
