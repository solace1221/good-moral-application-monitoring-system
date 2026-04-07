# Fix: Dean Dashboard Routing

## Problem

When a dean logged in and navigated to `/dashboard`, they were redirected to the wrong dashboard (e.g., the student dashboard) instead of the dean dashboard.

---

## Root Cause

The generic `/dashboard` route used a single controller method that did not distinguish between user roles. All authenticated users were sent to the same view.

---

## Solution

Implemented a smart dashboard router that inspects `account_type` and redirects to the role-specific dashboard:

```php
// routes/web.php or DashboardController.php
public function index(): RedirectResponse
{
    $user = auth()->user();

    return match ($user->account_type) {
        'admin'                         => redirect()->route('admin.dashboard'),
        'moderator', 'secosa'           => redirect()->route('secosa.dashboard'),
        'dean', 'deansom', 'deangradsch'=> redirect()->route('dean.dashboard'),
        'progcoor'                      => redirect()->route('progcoor.dashboard'),
        'student', 'alumni'             => redirect()->route('student.dashboard'),
        'psg'                           => redirect()->route('psg.dashboard'),
        default                         => redirect()->route('home'),
    };
}
```

---

## Files Modified

- `app/Http/Controllers/DashboardController.php` (or equivalent) — added role-based redirect logic
- `routes/web.php` — `/dashboard` route points to the router method

---

## Testing

For each of the seven role types, log in and navigate to `/dashboard`. Verify the redirect goes to the correct role-specific view.
