# SMTP Email Setup Guide (Gmail)

## Overview

GMAMS sends email notifications to students (application status updates, certificate readiness) via Gmail SMTP. This guide covers the configuration steps.

---

## Prerequisites

- A Gmail account to use as the sender
- **Google App Password** — a Gmail account with 2-Step Verification enabled can generate an App Password. Standard Gmail passwords will not work for SMTP.

---

## Step 1: Generate a Google App Password

1. Go to [myaccount.google.com](https://myaccount.google.com/).
2. Navigate to **Security** → **2-Step Verification** (must be enabled).
3. Scroll to **App passwords**.
4. Select app: **Mail**, device: **Other (Custom name)** → enter `GMAMS`.
5. Click **Generate**.
6. Copy the 16-character app password.

---

## Step 2: Configure `.env`

Open the project `.env` file and set the following values:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail_address@gmail.com
MAIL_PASSWORD=your_16_character_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_gmail_address@gmail.com
MAIL_FROM_NAME="SPUP Good Moral Application"
```

---

## Step 3: Clear Config Cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

## Step 4: Test Email

```bash
php artisan tinker
>>> Mail::raw('Test email from GMAMS', fn($m) => $m->to('test@example.com')->subject('GMAMS Test'));
```

Check the inbox at `test@example.com`. If it arrives, SMTP is configured correctly.

---

## Troubleshooting

| Issue | Solution |
|-------|---------|
| `Connection refused` or `Authentication failed` | Confirm App Password is correct; standard passwords are rejected |
| `SSL certificate error` | Try `MAIL_ENCRYPTION=tls` and `MAIL_PORT=587` (not 465) |
| Email goes to spam | Add sender address to allowed senders; configure SPF/DKIM if using a custom domain |
| No email sent | Check `storage/logs/laravel.log` for mail errors |

---

## Notes

- Never commit the `.env` file to version control.
- In production, consider a dedicated transactional email service (e.g., Mailgun, SendGrid) for higher reliability and deliverability.
