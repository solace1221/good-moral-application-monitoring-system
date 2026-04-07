# Fix: Admin Approved Applications View

## Problem

The Admin "Approved Applications" view was showing an incorrect count or displaying applications that should not appear (e.g., applications in `Ready for Pickup` status were missing, or applications from the wrong status were shown).

---

## Root Cause

The query in `AdminController` used a single status string for filtering, but the approval workflow has two statuses that represent "approved":
- `Approved by Administrator` — just approved, awaiting first print
- `Ready for Pickup` — certificate printed, awaiting student pickup

The view was only querying one of these statuses.

---

## Solution

Updated the admin query to include both statuses using `whereIn()`:

```php
// Before
$applications = GoodMoralApplication::where('application_status', 'Approved by Administrator')->get();

// After
$applications = GoodMoralApplication::whereIn('application_status', [
    'Approved by Administrator',
    'Ready for Pickup'
])->get();
```

---

## Files Modified

- `app/Http/Controllers/AdminController.php` — updated approved applications query

---

## Testing

1. Log in as Admin.
2. Navigate to **Approved Applications**.
3. Verify that both `Approved by Administrator` and `Ready for Pickup` applications appear.
4. Take count — should match total across both statuses.
