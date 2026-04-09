<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'fname' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'mname' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]*$/'],
            'lname' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'extension' => ['nullable', 'string', 'max:10', 'regex:/^[A-Za-z\s]*$/'],
            'gender' => ['required', 'string', 'in:male,female'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:student_registrations,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'department' => ['required', 'string', 'in:SITE,SBAHM,SASTE,SNAHS,SOM,GRADSCH'],
            'student_id' => ['required', 'string', 'max:20', 'unique:student_registrations'],
            'account_type' => ['required', 'string', 'in:student,alumni,psg_officer'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->input('account_type') === 'psg_officer') {
            $rules['organization'] = ['required', 'string', 'max:255'];
            $rules['position'] = ['required', 'string', 'max:255'];
        } elseif ($this->input('account_type') === 'student') {
            $rules['year_level'] = ['required', 'string', 'max:50'];
        }

        return $rules;
    }
}
