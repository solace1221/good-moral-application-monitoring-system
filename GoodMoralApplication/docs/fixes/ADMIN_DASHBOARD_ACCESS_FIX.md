# Fix: Admin Dashboard Access Control

## Problem

Non-admin users (students, deans, program coordinators) were able to access admin dashboard routes by navigating directly to `/admin/*` URLs. This was a role-based access control (RBAC) security vulnerability.

---

## Root Cause

The admin routes in `routes/web.php` were grouped with authentication middleware but were missing the role-checking middleware. Any authenticated user could access admin-only pages.

---

## Solution

Added the `role:admin` middleware to the admin route group:

```php
// Before
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    // ...
});

// After
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    // ...
});
```

The `role` middleware checks `auth()->user()->account_type` against the allowed roles and redirects unauthorized users to their own dashboard.

---

## Files Modified

- `routes/web.php` — `role:admin` middleware added to admin route group
- `app/Http/Middleware/RoleMiddleware.php` — verified redirect logic for unauthorized access

---

## Testing

1. Log in as a student.
2. Navigate directly to `/admin/dashboard`.
3. Expected: redirect to the student dashboard with an access denied message.
4. Log in as Admin → `/admin/dashboard` should load normally.
