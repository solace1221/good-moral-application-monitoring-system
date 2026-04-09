<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'student_id' => ['nullable', 'string', 'max:255', 'unique:role_account,student_id,' . $userId],
            'email' => ['required', 'email', 'max:255', 'unique:role_account,email,' . $userId],
            'department' => ['required', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'string', 'max:255'],
            'account_type' => ['required', 'string', 'in:admin,dean,registrar,sec_osa,prog_coor,psg_officer,student,alumni'],
            'status' => ['required', 'in:0,1'],
        ];
    }
}
