<?php

namespace App\Helpers;

class ViolationHelper
{
    /**
     * Generate Student Handbook reference format for violation notifications
     * 
     * @param string $offenseType - 'major' or 'minor'
     * @param string|null $article - Article reference (e.g., "Article 1", "Article 2.1")
     * @return string
     */
    public static function generateHandbookReference($offenseType, $article = null)
    {
        // Determine section based on offense type
        $section = $offenseType === 'major' ? 'Section 2' : 'Section 5';
        
        // Build the reference string
        $reference = "According to the 2024 edition of the Student Handbook, you have committed a violation under {$section}";
        
        // Add article if provided
        if ($article) {
            $reference .= ", {$article}";
        }
        
        return $reference;
    }
    
    /**
     * Generate complete violation notification message
     * 
     * @param string $offenseType - 'major' or 'minor'
     * @param string $violationDescription - Description of the violation
     * @param string|null $article - Article reference
     * @param string $addedBy - Who added the violation
     * @return string
     */
    public static function generateViolationNotification($offenseType, $violationDescription, $article = null, $addedBy = null)
    {
        $handbookReference = self::generateHandbookReference($offenseType, $article);
        
        $message = "{$handbookReference}. You have been issued a {$offenseType} violation: \"{$violationDescription}\".";
        
        if ($addedBy) {
            $message .= " Added by: {$addedBy}.";
        }
        
        $message .= " Please go to the Dean's office for compliance.";
        
        return $message;
    }
}
