<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Roles that do not require a department (system-wide roles)
        $noDepartmentRoles = ['sec_osa', 'head_osa'];

        return [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:role_account,email'],
            'department' => [
                Rule::requiredIf(! in_array($this->account_type, $noDepartmentRoles)),
                'nullable',
                'string',
                'max:255',
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'student_id' => ['nullable', 'string', 'max:20', 'unique:role_account,student_id'],
            'course' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'string', 'max:255'],
            'organization' => [
                Rule::requiredIf($this->account_type === 'psg_officer'),
                'nullable',
                'string',
                'max:255',
            ],
            'account_type' => ['required', 'string', 'in:dean,sec_osa,head_osa,registrar,prog_coor,psg_officer,student,alumni'],
        ];
    }
}
