<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGraduationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_graduating' => ['required', 'boolean'],
            'graduation_date' => ['required_if:is_graduating,true', 'date', 'after_or_equal:today'],
        ];
    }
}
