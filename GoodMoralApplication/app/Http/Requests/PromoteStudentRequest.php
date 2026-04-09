<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoteStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:role_account,student_id'],
            'new_year_level' => ['required', 'integer', 'min:1', 'max:4'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
