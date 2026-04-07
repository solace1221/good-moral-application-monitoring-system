<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Models\RoleAccount;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email : The email address to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a password reset email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Testing email configuration...");
        $this->info("Target email: {$email}");

        // Check if user exists
        $user = RoleAccount::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found in the database.");
            $this->info("Available emails in database:");
            $emails = RoleAccount::pluck('email')->take(5);
            foreach ($emails as $availableEmail) {
                $this->line("  - {$availableEmail}");
            }
            return 1;
        }

        $this->info("User found: {$user->fullname}");

        // Check SMTP configuration first
        $this->info("Checking SMTP configuration...");
        $mailHost = config('mail.mailers.smtp.host');
        $mailUsername = config('mail.mailers.smtp.username');
        $mailPassword = config('mail.mailers.smtp.password');

        if (empty($mailUsername) || empty($mailPassword)) {
            $this->error("❌ SMTP credentials not configured!");
            $this->info("Current configuration:");
            $this->info("  MAIL_HOST: " . ($mailHost ?: 'Not set'));
            $this->info("  MAIL_USERNAME: " . ($mailUsername ?: 'Not set'));
            $this->info("  MAIL_PASSWORD: " . ($mailPassword ? 'Set (hidden)' : 'Not set'));
            $this->info("\nTo fix this:");
            $this->info("1. Update your .env file with valid Gmail credentials");
            $this->info("2. For Gmail, you need an App Password (not your regular password)");
            $this->info("3. Run: php setup-email.php for detailed instructions");
            return 1;
        }

        $this->info("✅ SMTP credentials are configured");

        try {
            // Test basic email configuration
            $this->info("Testing basic email configuration...");

            Mail::raw('This is a test email from SPUP Good Moral Application System.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('SPUP Good Moral System - Test Email');
            });

            $this->info("✅ Basic email test sent successfully!");

            // Test password reset email
            $this->info("Testing password reset email...");

            $status = Password::sendResetLink(['email' => $email]);

            if ($status === Password::RESET_LINK_SENT) {
                $this->info("✅ Password reset email sent successfully!");
                $this->info("Check the inbox for {$email}");
                $this->warn("Note: The reset link will expire in 60 minutes.");
            } else {
                $this->error("❌ Failed to send password reset email.");
                $this->error("Status: " . __($status));
            }

        } catch (\Exception $e) {
            $this->error("❌ Email sending failed!");
            $this->error("Error: " . $e->getMessage());

            // Provide specific troubleshooting based on error message
            if (strpos($e->getMessage(), '530') !== false) {
                $this->info("\n🔧 This is a Gmail authentication error. To fix:");
                $this->info("1. Enable 2-Factor Authentication on your Gmail account");
                $this->info("2. Generate an App Password (not your regular password)");
                $this->info("3. Update MAIL_USERNAME and MAIL_PASSWORD in .env");
                $this->info("4. Run: php artisan config:clear");
            } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
                $this->info("\n🔧 Connection issue. Check:");
                $this->info("1. MAIL_HOST and MAIL_PORT settings");
                $this->info("2. Your internet connection");
                $this->info("3. Firewall settings");
            }

            $this->info("\nFor detailed setup instructions, run: php setup-email.php");
            return 1;
        }

        return 0;
    }
}
