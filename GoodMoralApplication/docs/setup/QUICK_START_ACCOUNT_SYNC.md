# Quick Start — Integrated Account System

## Overview

One registration gives access to both GMAMS and the Clearance Management System. This guide explains how it works for students.

---

## How to Register

1. Go to the GMAMS registration page.
2. Fill in your details:
   - First Name, Middle Name, Last Name
   - Email Address
   - Password
   - Department (SITE, SASTE, SNAHS, SBAHM)
   - Account Type: select **Student**
   - Course and Year Level (e.g., BSIT – 1st Year)
   - Student ID Number
3. Click **Create Account**.

Your account is now active in **both** GMAMS and the Clearance Management System.

---

## How to Log In

Use the same credentials for both systems:

| System | URL |
|--------|-----|
| Good Moral Application | `https://goodmoralapplication.test` or `http://localhost:8000` |
| Clearance Management System | `http://localhost:8001` |

---

## What Happens Automatically

After registration:

1. Account created in GMAMS (authentication + student profile)
2. Account created in CMS (authentication + student + clearance records)
3. Clearance requirements generated for the current semester
4. Both systems immediately accessible with the same password

---

## Auto-Login to CMS

From the GMAMS student dashboard, click **My Clearance**. You will be automatically logged into the CMS — no second login required. Authentication uses a secure encrypted token.

---

## Troubleshooting

| Problem | Solution |
|---------|---------|
| Cannot log in to CMS after registering in GMAMS | Wait a few seconds and retry; check `storage/logs/laravel.log` for sync errors |
| Cannot log in with same password | Reset password in GMAMS; the sync will update CMS automatically |
| Missing clearance records in CMS | Run `php batch_sync_cms_to_gmams.php` to re-sync |
