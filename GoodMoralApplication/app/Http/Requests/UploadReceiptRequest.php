<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_num' => ['required', 'string'],
            'official_receipt_no' => ['required', 'string', 'max:255'],
            'date_paid' => ['required', 'date', 'before_or_equal:today'],
            'document_path' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }
}
