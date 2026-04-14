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
            'report_type' => ['required', 'string', 'in:good_moral_applicants,residency_applicants,minor_violators,major_violators,overall_report,minor_offenses_overall'],
            'time_period' => ['nullable', 'string'],
            'export_format' => ['nullable', 'string', 'in:pdf,docx'],
        ];
    }
}
