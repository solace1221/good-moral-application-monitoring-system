# Profile Update Sync — Implementation Complete

## Overview

When a user updates their profile in GMAMS, the changes are automatically pushed to the CMS `clearance_users` table via `ClearanceSyncService::updateUser()`.

---

## Sync Service: `ClearanceSyncService::updateUser()`

```php
// app/Services/ClearanceSyncService.php

public function updateUser(User $user): bool
{
    try {
        DB::connection('clearance')
            ->table('clearance_users')
            ->where('email', $user->getOriginal('email'))
            ->update([
                'name'       => $user->name,
                'email'      => $user->email,
                'password'   => $user->password, // already hashed
                'updated_at' => now(),
            ]);
        return true;
    } catch (\Exception $e) {
        \Log::error('ClearanceSync updateUser failed: ' . $e->getMessage());
        return false;
    }
}
```

---

## Where It Is Called

In `ApplicationController`, every profile update route (student, PSG officer) calls this method after saving:

```php
$this->clearanceSyncService->updateUser($user);
```

---

## Fields Synced

| GMAMS field | CMS field |
|---|---|
| `name` | `name` |
| `email` | `email` |
| `password` (hashed) | `password` |

---

## Notes

- The lookup key in CMS is the **original email** (before any email change) to prevent missed updates when email is being changed.
- If sync fails, the profile update in GMAMS still succeeds — a log entry is written for investigation.
