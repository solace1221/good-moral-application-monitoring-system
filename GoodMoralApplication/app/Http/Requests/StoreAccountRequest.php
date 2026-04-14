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
        $noDepartmentRoles = ['sec_osa'];

        return [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:role_account,email', 'unique:users,email'],
            'department' => [
                Rule::requiredIf(! in_array($this->account_type, $noDepartmentRoles)),
                'nullable',
                'string',
                'max:255',
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'student_id' => ['nullable', 'string', 'max:20', 'unique:role_account,student_id'],
            'course_id' => [
                Rule::requiredIf(in_array($this->account_type, ['student', 'alumni'])),
                'nullable',
                'integer',
                'exists:courses,id',
            ],
            'year_level' => [
                Rule::requiredIf(in_array($this->account_type, ['student', 'alumni'])),
                'nullable',
                'string',
                'in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            ],
            'organization' => [
                'nullable',
                'string',
                'max:255',
            ],
            'account_type' => ['required', 'string', 'in:dean,sec_osa,registrar,prog_coor,student,alumni'],
        ];
    }
}
