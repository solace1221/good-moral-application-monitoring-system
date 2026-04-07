<?php

namespace App\Helpers;

class NameFormatter
{
    /**
     * Format name from "LASTNAME, FIRSTNAME MIDDLEINITIAL" to "FIRSTNAME MIDDLEINITIAL. LASTNAME EXTENSION"
     *
     * @param string $fullname The full name in database format
     * @param string|null $extension Name extension (JR, SR, III, etc.)
     * @return string Formatted name for certificates and reports
     */
    public static function formatForCertificate($fullname, $extension = null)
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
