<?php

if (!function_exists('formatNameForCertificate')) {
    /**
     * Format name from "LASTNAME, FIRSTNAME MIDDLEINITIAL" to "FIRSTNAME MIDDLEINITIAL. LASTNAME EXTENSION"
     *
     * @param string $fullname The full name in database format
     * @param string|null $extension Name extension (JR, SR, III, etc.)
     * @return string Formatted name for certificates and reports
     */
    function formatNameForCertificate($fullname, $extension = null)
    {
        if (empty($fullname)) {
            return '';
        }
        
        // Handle names with comma (LASTNAME, FIRSTNAME MIDDLEINITIAL)
        if (strpos($fullname, ',') !== false) {
            $parts = explode(',', $fullname, 2);
            $lastname = trim($parts[0]);
            $firstMiddle = trim($parts[1] ?? '');
            
            // Split first and middle names
            $firstMiddleParts = explode(' ', $firstMiddle);
            
            // Check if the last part is a single letter (middle initial)
            $lastPart = end($firstMiddleParts);
            $isMiddleInitial = strlen($lastPart) === 1 || (strlen($lastPart) === 2 && str_ends_with($lastPart, '.'));
            
            if ($isMiddleInitial && count($firstMiddleParts) > 1) {
                // Last part is middle initial, everything else is first name
                $middleinitial = array_pop($firstMiddleParts);
                $firstname = implode(' ', $firstMiddleParts);
                
                // Add period to middle initial if it doesn't have one
                if ($middleinitial && !str_ends_with($middleinitial, '.')) {
                    $middleinitial .= '.';
                }
            } else {
                // No middle initial, everything is first name
                $firstname = $firstMiddle;
                $middleinitial = '';
            }
            
            // Construct the formatted name
            $formattedName = $firstname;
            if ($middleinitial) {
                $formattedName .= ' ' . $middleinitial;
            }
            $formattedName .= ' ' . $lastname;
            if ($extension) {
                $formattedName .= ' ' . $extension;
            }
            
            return $formattedName;
        }
        
        // If no comma, return as-is (fallback)
        return $fullname . ($extension ? ' ' . $extension : '');
    }
}

if (!function_exists('generateHandbookReference')) {
    /**
     * Generate Student Handbook reference format for violation notifications
     *
     * @param string $offenseType - 'major' or 'minor'
     * @param string|null $article - Article reference (e.g., "Article 1", "Article 2.1")
     * @return string
     */
    function generateHandbookReference($offenseType, $article = null)
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
}

if (!function_exists('generateViolationNotification')) {
    /**
     * Generate complete violation notification message
     *
     * @param string $offenseType - 'major' or 'minor'
     * @param string $violationDescription - Description of the violation
     * @param string|null $article - Article reference
     * @param string $addedBy - Who added the violation
     * @return string
     */
    function generateViolationNotification($offenseType, $violationDescription, $article = null, $addedBy = null)
    {
        $handbookReference = generateHandbookReference($offenseType, $article);

        $message = "{$handbookReference}. You have been issued a {$offenseType} violation: \"{$violationDescription}\".";

        if ($addedBy) {
            $message .= " Added by: {$addedBy}.";
        }

        $message .= " Please go to the Dean's office for compliance.";

        return $message;
    }
}
