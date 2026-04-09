<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViolationReportRequest extends FormRequest
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
            'student_id' => ['required', 'string', 'max:20'],
            'violation' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'others' => ['nullable', 'string', 'max:255'],
        ];
    }
}
