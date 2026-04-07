# Fix: Duplicate Email on Registration

## Problem

Registering with an email address that already existed in the database caused an unhandled database exception instead of a friendly validation error.

---

## Root Cause

The registration form validation did not include a `unique:users,email` rule. When a duplicate email was submitted, MySQL threw an integrity constraint violation, which Laravel surfaced as a 500 error.

---

## Solution

Added the `unique` validation rule to the registration request:

```php
$request->validate([
    'email' => ['required', 'email', 'unique:users,email'],
    // ... other rules
]);
```

For profile update (email change), used the `ignore` modifier to exclude the current user:

```php
$request->validate([
    'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
]);
```

---

## Files Modified

- `app/Http/Controllers/Auth/RegisteredUserController.php` — added `unique:users,email` rule
- `app/Http/Controllers/ApplicationController.php` — added `Rule::unique(...)->ignore()` for email updates

---

## Testing

1. Register with a new email → should succeed.
2. Register with an existing email → should show: `"The email has already been taken."`
3. Update profile with the same email → should succeed (not flagged as duplicate).
4. Update profile with another user's email → should show the duplicate error.
