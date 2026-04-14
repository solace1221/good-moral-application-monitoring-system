<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreViolationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $violationId = $this->route('id');

        return [
            'offense_type' => ['required', 'in:minor,major'],
            'description' => [
                'required',
                'string',
                'max:255',
                Rule::unique('violations')
                    ->where('offense_type', $this->input('offense_type'))
                    ->ignore($violationId),
            ],
            'article' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.unique' => 'Violation type already exists.',
        ];
    }
}
