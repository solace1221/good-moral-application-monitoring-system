<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - SPUP Good Moral Application System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
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
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .reset-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .reset-button:hover {
            background-color: #2980b9;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .alternative-link {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            word-break: break-all;
            font-size: 12px;
            color: #7f8c8d;
        }
        .footer {
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .expiry-info {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">SPUP Good Moral Application System</div>
            <div class="subtitle">St. Paul University Philippines</div>
        </div>

        <div class="content">
            <div class="greeting">Hello!</div>
            
            <div class="message">
                You are receiving this email because we received a password reset request for your account in the SPUP Good Moral Application System.
            </div>

            <div class="button-container">
                <a href="{{ $url }}" class="reset-button">Reset Password</a>
            </div>

            <div class="expiry-info">
                <strong>⏰ Important:</strong> This password reset link will expire in {{ $count }} minutes for security reasons.
            </div>

            <div class="message">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
            </div>

            <div class="alternative-link">
                {{ $url }}
            </div>

            <div class="warning">
                <strong>⚠️ Security Notice:</strong> If you did not request a password reset, no further action is required. Your account remains secure.
            </div>
        </div>

        <div class="footer">
            <p><strong>SPUP Good Moral Application System</strong></p>
            <p>St. Paul University Philippines</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you need assistance, please contact the registrar's office.</p>
        </div>
    </div>
</body>
</html>
