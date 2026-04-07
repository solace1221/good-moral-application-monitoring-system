<?php
/**
 * Email Setup Helper Script
 * Run this script to configure your email settings interactively
 */

echo "=== SPUP Good Moral Application System - Email Setup ===\n\n";

// Read current .env file
$envFile = '.env';
$envContent = file_get_contents($envFile);

echo "Current email configuration:\n";
echo "MAIL_MAILER: " . (getEnvValue($envContent, 'MAIL_MAILER') ?: 'Not set') . "\n";
echo "MAIL_HOST: " . (getEnvValue($envContent, 'MAIL_HOST') ?: 'Not set') . "\n";
echo "MAIL_USERNAME: " . (getEnvValue($envContent, 'MAIL_USERNAME') ?: 'Not set') . "\n";
echo "MAIL_PASSWORD: " . (getEnvValue($envContent, 'MAIL_PASSWORD') ? 'Set (hidden)' : 'Not set') . "\n\n";

echo "To fix the '530 Authentication Required' error, you need to:\n\n";

echo "1. Enable 2-Factor Authentication on your Gmail account\n";
echo "   - Go to: https://myaccount.google.com/security\n";
echo "   - Enable 2-Step Verification\n\n";

echo "2. Generate an App Password\n";
echo "   - In Google Account Security, go to 'App passwords'\n";
echo "   - Select 'Mail' and 'Other (custom name)'\n";
echo "   - Enter 'SPUP Good Moral System' as the name\n";
echo "   - Copy the 16-character password (format: abcd efgh ijkl mnop)\n\n";

echo "3. Update your .env file with these values:\n\n";

echo "MAIL_USERNAME=your-gmail-address@gmail.com\n";
echo "MAIL_PASSWORD=your-16-character-app-password\n";
echo "MAIL_FROM_ADDRESS=\"your-gmail-address@gmail.com\"\n\n";

echo "4. Clear Laravel cache:\n";
echo "   php artisan config:clear\n\n";

echo "5. Test the configuration:\n";
echo "   php artisan email:test your-email@example.com\n\n";

echo "Alternative: Use a different email provider\n";
echo "If you don't want to use Gmail, you can use:\n\n";

echo "For Outlook/Hotmail:\n";
echo "MAIL_HOST=smtp-mail.outlook.com\n";
echo "MAIL_PORT=587\n";
echo "MAIL_USERNAME=your-email@outlook.com\n";
echo "MAIL_PASSWORD=your-password\n\n";

echo "For Yahoo:\n";
echo "MAIL_HOST=smtp.mail.yahoo.com\n";
echo "MAIL_PORT=587\n";
echo "MAIL_USERNAME=your-email@yahoo.com\n";
echo "MAIL_PASSWORD=your-app-password\n\n";

echo "=== End of Setup Instructions ===\n";

function getEnvValue($content, $key) {
    if (preg_match("/^{$key}=(.*)$/m", $content, $matches)) {
        return trim($matches[1], '"');
    }
    return null;
}
