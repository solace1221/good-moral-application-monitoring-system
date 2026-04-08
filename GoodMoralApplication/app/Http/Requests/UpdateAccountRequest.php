<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'fullname' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:255', Rule::unique('role_accounts', 'student_id')->ignore($userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('role_accounts', 'email')->ignore($userId)],
            'department' => ['required', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'string', 'max:255'],
            'account_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
