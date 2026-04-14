<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViolatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:255', 'exists:role_account,student_id'],
            'department' => ['required', 'string', 'max:255'],
            'course' => ['required', 'string', 'max:255'],
            'offense_type' => ['required', 'in:minor,major'],
            'violation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.exists' => 'The selected student ID does not exist in the system.',
        ];
    }
}
