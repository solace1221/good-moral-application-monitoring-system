<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Setup - SPUP Good Moral Application System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .status-card {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 5px solid;
        }
        .status-success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .status-error {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .config-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .config-table th,
        .config-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .config-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .steps {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .step {
            margin: 15px 0;
            padding-left: 20px;
        }
        .code-block {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            margin: 10px 0;
            overflow-x: auto;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Email Configuration Setup</div>
            <div class="subtitle">SPUP Good Moral Application System</div>
        </div>

        @if($isConfigured)
            <div class="status-card status-success">
                <h3>✅ Email Configuration Status: Ready</h3>
                <p>Your email settings are configured and ready to send password reset emails.</p>
            </div>
        @else
            <div class="status-card status-error">
                <h3>❌ Email Configuration Status: Not Configured</h3>
                <p>You need to configure SMTP settings to enable password reset emails.</p>
            </div>
        @endif

        <h3>Current Configuration</h3>
        <table class="config-table">
            <thead>
                <tr>
                    <th>Setting</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mail Driver</td>
                    <td>{{ $currentConfig['mail_mailer'] }}</td>
                </tr>
                <tr>
                    <td>SMTP Host</td>
                    <td>{{ $currentConfig['mail_host'] }}</td>
                </tr>
                <tr>
                    <td>SMTP Port</td>
                    <td>{{ $currentConfig['mail_port'] }}</td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td>{{ $currentConfig['mail_username'] ?: 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>{{ $currentConfig['mail_password'] }}</td>
                </tr>
                <tr>
                    <td>Encryption</td>
                    <td>{{ $currentConfig['mail_encryption'] }}</td>
                </tr>
                <tr>
                    <td>From Address</td>
                    <td>{{ $currentConfig['mail_from_address'] }}</td>
                </tr>
                <tr>
                    <td>From Name</td>
                    <td>{{ $currentConfig['mail_from_name'] }}</td>
                </tr>
            </tbody>
        </table>

        @if(!$isConfigured)
            <div class="steps">
                <h3>🔧 Setup Instructions for Gmail</h3>
                
                <div class="step">
                    <strong>Step 1:</strong> Enable 2-Factor Authentication
                    <ul>
                        <li>Go to <a href="https://myaccount.google.com/security" target="_blank">Google Account Security</a></li>
                        <li>Enable 2-Step Verification if not already enabled</li>
                    </ul>
                </div>

                <div class="step">
                    <strong>Step 2:</strong> Generate App Password
                    <ul>
                        <li>In Google Account Security, go to "App passwords"</li>
                        <li>Select "Mail" and "Other (custom name)"</li>
                        <li>Enter "SPUP Good Moral System" as the name</li>
                        <li>Copy the 16-character password (format: abcd efgh ijkl mnop)</li>
                    </ul>
                </div>

                <div class="step">
                    <strong>Step 3:</strong> Update .env file
                    <div class="code-block">
MAIL_USERNAME=your-gmail-address@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_FROM_ADDRESS="your-gmail-address@gmail.com"
                    </div>
                </div>

                <div class="step">
                    <strong>Step 4:</strong> Clear Laravel cache
                    <div class="code-block">php artisan config:clear</div>
                </div>

                <div class="step">
                    <strong>Step 5:</strong> Test the configuration
                    <div class="code-block">php artisan email:test your-email@example.com</div>
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong> Never use your regular Gmail password. You must use an App Password generated specifically for this application.
            </div>
        @endif

        <h3>Testing & Troubleshooting</h3>
        <div style="margin: 20px 0;">
            <a href="{{ route('test.smtp.config') }}" class="btn" target="_blank">Check SMTP Config</a>
            @if($isConfigured)
                <a href="{{ url('/test-password-reset-email/test@example.com') }}" class="btn btn-success" target="_blank">Test Password Reset Email</a>
            @endif
        </div>

        @if($isConfigured)
            <div class="status-card status-success">
                <h4>🎉 Ready to Use!</h4>
                <p>Your password reset functionality is now working. Users can:</p>
                <ul>
                    <li>Click "Forgot Password" on the login page</li>
                    <li>Enter their email address</li>
                    <li>Receive a professional password reset email</li>
                    <li>Click the link to reset their password securely</li>
                </ul>
            </div>
        @endif

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <a href="{{ route('login') }}" class="btn">Back to Login</a>
        </div>
    </div>
</body>
</html>
