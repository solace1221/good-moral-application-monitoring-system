<?php

namespace App\Services;

use App\Models\GoodMoralApplication;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReceiptService
{
    /**
     * Generate a payment notice PDF for an approved application
     */
    public function generatePaymentNotice(GoodMoralApplication $application)
    {
        // Generate receipt number
        $receiptNumber = $this->generateReceiptNumber();
        
        // Prepare receipt data
        $receiptData = [
            'receipt_number' => $receiptNumber,
            'application' => $application,
            'date_issued' => now(),
            'payment_amount' => $application->payment_amount,
            'formatted_payment' => $application->formatted_payment,
            'reasons_count' => count($application->reasons_array),
            'copies_count' => $application->number_of_copies,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.receipt', $receiptData);
        $pdf->setPaper('letter', 'portrait');
        
        // Generate filename
        $filename = "payment_notice_{$receiptNumber}_{$application->reference_number}.pdf";
        $filepath = "payment_notices/{$filename}";

        // Save PDF to storage
        Storage::disk('public')->put($filepath, $pdf->output());

        // Create or update receipt record
        $receipt = Receipt::updateOrCreate(
            ['reference_num' => $application->reference_number],
            [
                'receipt_number' => $receiptNumber,
                'student_id' => $application->student_id,
                'amount' => $application->payment_amount,
                'payment_method' => 'Pending Payment',
                'official_receipt_no' => null, // Will be filled when student uploads receipt
                'date_paid' => null, // Will be filled when student uploads receipt
                'document_path' => $filepath,
                'status' => 'pending_payment',
            ]
        );
        
        return [
            'receipt' => $receipt,
            'filepath' => $filepath,
            'filename' => $filename,
            'receipt_number' => $receiptNumber
        ];
    }
    
    /**
     * Generate a unique receipt number
     */
    private function generateReceiptNumber()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        return "RCP-{$date}-{$random}";
    }
    
    /**
     * Get receipt download URL
     */
    public function getReceiptDownloadUrl($filepath)
    {
        return Storage::disk('public')->url($filepath);
    }
}
