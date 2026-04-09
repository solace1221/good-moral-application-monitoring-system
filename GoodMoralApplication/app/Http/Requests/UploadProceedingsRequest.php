<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadProceedingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proceedings_document' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'meeting_date' => ['required', 'date'],
            'meeting_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
