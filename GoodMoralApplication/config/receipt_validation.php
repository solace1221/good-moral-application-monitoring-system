<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Receipt Validation Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for validating uploaded receipts
    | to ensure they are legitimate Business Affairs Office receipts.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Required Text Patterns
    |--------------------------------------------------------------------------
    |
    | These patterns MUST be present in a valid SPUP receipt.
    | The validation will fail if too few of these patterns are found.
    |
    */
    'required_patterns' => [
        'st\.?\s*paul\s*university\s*philippines?',
        'business\s*affairs?\s*office',
        'official\s*receipt',
        'tuguegarao\s*city',
        'cagayan',
        'non-?vat'
    ],

    /*
    |--------------------------------------------------------------------------
    | Important Text Patterns
    |--------------------------------------------------------------------------
    |
    | These patterns are important indicators of a genuine receipt.
    | A good score here increases confidence in the validation.
    |
    */
    'important_patterns' => [
        'received\s*from',
        'the\s*sum\s*of',
        'as\s*payment\s*of',
        'accounts\s*receivable',
        'student'
    ],

    /*
    |--------------------------------------------------------------------------
    | Optional Text Patterns
    |--------------------------------------------------------------------------
    |
    | These patterns add confidence but are not required.
    | They help distinguish genuine receipts from fake ones.
    |
    */
    'optional_patterns' => [
        'balance',
        'cash',
        'change',
        'teller',
        'printed\s*on',
        'thank\s*you',
        'cashier',
        'amount',
        'total'
    ],

    /*
    |--------------------------------------------------------------------------
    | Suspicious Filename Patterns
    |--------------------------------------------------------------------------
    |
    | Files with these patterns in their names will be rejected immediately.
    | These typically indicate screenshots or non-original documents.
    |
    */
    'suspicious_filename_patterns' => [
        'screenshot',
        'screen_shot',
        'screen-shot',
        'photo_editor',
        'camera',
        'gallery',
        'download',
        'whatsapp',
        'facebook',
        'instagram',
        'twitter',
        'social',
        'edited',
        'copy',
        'duplicate',
        'snap',
        'capture'
    ],

    /*
    |--------------------------------------------------------------------------
    | Suspicious Content Patterns
    |--------------------------------------------------------------------------
    |
    | If these patterns are found in the extracted text, the file will be rejected.
    | These indicate the file is not a genuine receipt.
    |
    */
    'suspicious_content_patterns' => [
        'screenshot',
        'photo\s*editor',
        'camera\s*app',
        'gallery',
        'download',
        'whatsapp',
        'facebook',
        'instagram',
        'twitter',
        'social\s*media',
        'edited\s*with',
        'created\s*with',
        'generated\s*by',
        'photoshop',
        'gimp',
        'canva'
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Thresholds
    |--------------------------------------------------------------------------
    |
    | These values determine how strict the validation should be.
    | Adjust these values to make validation more or less strict.
    |
    */
    'thresholds' => [
        'required_score_minimum' => 50,    // Minimum % of required patterns that must be found
        'important_score_minimum' => 40,   // Minimum % of important patterns that must be found
        'minimum_image_width' => 200,      // Minimum image width in pixels
        'minimum_image_height' => 200,     // Minimum image height in pixels
        'minimum_white_ratio' => 0.5,      // Minimum ratio of white pixels (receipts are typically on white paper)
    ],

    /*
    |--------------------------------------------------------------------------
    | Score Weights
    |--------------------------------------------------------------------------
    |
    | These weights determine how much each category contributes to the final score.
    | All weights should add up to 1.0.
    |
    */
    'score_weights' => [
        'required' => 0.5,     // 50% weight for required patterns
        'important' => 0.3,    // 30% weight for important patterns
        'optional' => 0.2,     // 20% weight for optional patterns
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | Customizable error messages for different validation failures.
    |
    */
    'error_messages' => [
        'suspicious_filename' => 'The file name suggests this is not an original receipt. Please upload the official receipt document from Business Affairs Office.',
        'unreadable_document' => 'Unable to read the uploaded document. Please ensure the file is clear and readable, or try uploading in a different format.',
        'invalid_receipt_content' => 'The uploaded file does not appear to be a valid receipt from St. Paul University Philippines Business Affairs Office. Please ensure you upload the official receipt you received after making payment.',
        'missing_essential_info' => 'The uploaded document is missing essential receipt information. Please upload the complete official receipt from Business Affairs Office.',
        'suspicious_content' => 'The uploaded file appears to be a screenshot, edited image, or non-original document. Please upload the original receipt document from Business Affairs Office.',
        'validation_error' => 'Error validating the uploaded file. Please try uploading a different format or contact support if the problem persists.',
        'image_too_small' => 'The uploaded image is too small to be a valid receipt. Please upload a clear, full-size image of your receipt.'
    ],

    /*
    |--------------------------------------------------------------------------
    | OCR Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OCR services (when implemented).
    |
    */
    'ocr' => [
        'enabled' => false,                    // Enable actual OCR processing
        'service' => 'tesseract',             // OCR service to use (tesseract, google, aws, azure)
        'confidence_threshold' => 60,         // Minimum OCR confidence score
        'preprocessing' => [
            'enhance_contrast' => true,
            'remove_noise' => true,
            'deskew' => true,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure what validation events should be logged.
    |
    */
    'logging' => [
        'log_all_attempts' => true,           // Log all validation attempts
        'log_rejections' => true,             // Log rejected uploads
        'log_suspicious_activity' => true,   // Log suspicious upload patterns
        'include_file_details' => true,      // Include file metadata in logs
    ]
];
