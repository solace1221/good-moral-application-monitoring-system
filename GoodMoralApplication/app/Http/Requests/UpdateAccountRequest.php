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
            'student_id' => [
                Rule::requiredIf($this->account_type === 'student'),
                'nullable',
                'string',
                'max:255',
                'unique:role_account,student_id,' . $userId,
            ],
            'email' => ['required', 'email', 'max:255', 'unique:role_account,email,' . $userId],
            'department' => ['required', 'string', 'max:255'],
            'course_id' => [
                Rule::requiredIf($this->account_type === 'student'),
                'nullable',
                'integer',
                'exists:courses,id',
            ],
            'year_level' => [
                Rule::requiredIf($this->account_type === 'student'),
                'nullable',
                'string',
                'in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            ],
            'account_type' => ['required', 'string', 'in:admin,dean,registrar,sec_osa,prog_coor,psg_officer,student,alumni'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
