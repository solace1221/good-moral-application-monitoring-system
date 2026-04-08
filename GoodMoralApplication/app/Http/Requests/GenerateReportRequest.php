<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year' => ['required', 'string'],
            'report_type' => ['required', 'string', 'in:applications,violations'],
            'time_period' => ['required', 'string'],
        ];
    }
}
