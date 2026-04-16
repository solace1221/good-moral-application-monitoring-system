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
            'offense_type' => ['required', 'in:major'],
            'violation' => ['required_without:multiple_violations_data', 'nullable', 'string'],
            'multiple_violations_data' => ['nullable', 'string'],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'string', 'exists:student_registrations,student_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'offense_type.in' => 'Minor violations cannot be assigned to multiple students. Only major violations are allowed.',
            'student_ids.*.exists' => 'One or more selected student IDs do not exist in the system.',
        ];
    }
}
