<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'rejection_reason' => ['required', 'string', 'max:255'],
            'rejection_details' => ['nullable', 'string', 'max:1000'],
            'specify_reason' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->input('rejection_reason') === 'Others: specify') {
            $rules['specify_reason'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'specify_reason.required' => 'Please specify the reason when "Others: specify" is selected.',
        ];
    }
}
