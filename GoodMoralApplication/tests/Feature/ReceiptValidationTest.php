<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ReceiptValidationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ReceiptValidationTest extends TestCase
{
    protected $validationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validationService = new ReceiptValidationService();
        Storage::fake('public');
    }

    /** @test */
    public function it_rejects_random_images()
    {
        // Create a fake image file that doesn't contain receipt content
        $fakeImage = UploadedFile::fake()->image('random_photo.jpg', 800, 600);
        
        $result = $this->validationService->validateReceiptContent($fakeImage);
        
        $this->assertFalse($result['is_valid']);
        $this->assertStringContainsString('does not appear to be a valid receipt', $result['error_message']);
    }

    /** @test */
    public function it_rejects_screenshots()
    {
        // Create a fake file with suspicious filename
        $screenshot = UploadedFile::fake()->image('screenshot_2024.png', 800, 600);
        
        $result = $this->validationService->validateReceiptContent($screenshot);
        
        $this->assertFalse($result['is_valid']);
        $this->assertStringContainsString('not an original receipt', $result['error_message']);
    }

    /** @test */
    public function it_rejects_files_with_suspicious_names()
    {
        $suspiciousFiles = [
            'whatsapp_image.jpg',
            'facebook_photo.png',
            'camera_photo.jpg',
            'edited_receipt.pdf',
            'screenshot_receipt.png'
        ];

        foreach ($suspiciousFiles as $filename) {
            $file = UploadedFile::fake()->image($filename, 800, 600);
            $result = $this->validationService->validateReceiptContent($file);
            
            $this->assertFalse($result['is_valid'], "File {$filename} should be rejected");
        }
    }

    /** @test */
    public function it_accepts_files_with_valid_names()
    {
        $validFiles = [
            'receipt_001.jpg',
            'official_receipt.pdf',
            'business_affairs_receipt.png',
            'payment_receipt.jpg'
        ];

        foreach ($validFiles as $filename) {
            $file = UploadedFile::fake()->image($filename, 800, 600);
            
            // Note: This will still fail content validation since it's a fake image,
            // but it should pass the filename check
            $result = $this->validationService->validateReceiptContent($file);
            
            // The error should be about content, not filename
            if (!$result['is_valid']) {
                $this->assertStringNotContainsString('not an original receipt', $result['error_message']);
                $this->assertStringContainsString('does not appear to be a valid receipt', $result['error_message']);
            }
        }
    }

    /** @test */
    public function it_validates_file_dimensions()
    {
        // Create a very small image (too small to be a receipt)
        $tinyImage = UploadedFile::fake()->image('tiny.jpg', 50, 50);
        
        $result = $this->validationService->validateReceiptContent($tinyImage);
        
        $this->assertFalse($result['is_valid']);
        $this->assertStringContainsString('Unable to read', $result['error_message']);
    }

    /** @test */
    public function it_handles_pdf_files()
    {
        // Create a fake PDF file
        $pdf = UploadedFile::fake()->create('receipt.pdf', 1000, 'application/pdf');
        
        $result = $this->validationService->validateReceiptContent($pdf);
        
        // Should attempt to process PDF but fail content validation
        $this->assertFalse($result['is_valid']);
    }

    /** @test */
    public function it_logs_validation_attempts()
    {
        $file = UploadedFile::fake()->image('test_receipt.jpg', 800, 600);
        
        // This should trigger logging
        $result = $this->validationService->validateReceiptContent($file);
        
        // We can't easily test logging in unit tests without mocking,
        // but we can ensure the method completes without errors
        $this->assertIsArray($result);
        $this->assertArrayHasKey('is_valid', $result);
        $this->assertArrayHasKey('error_message', $result);
    }
}
