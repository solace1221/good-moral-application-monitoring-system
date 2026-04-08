<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultipleViolatorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offense_type' => ['required', 'in:minor,major'],
            'violation' => ['required_without:multiple_violations_data', 'nullable', 'string'],
            'multiple_violations_data' => ['nullable', 'string'],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'string'],
            'ref_num' => ['nullable', 'string', 'max:255'],
        ];
    }
}
