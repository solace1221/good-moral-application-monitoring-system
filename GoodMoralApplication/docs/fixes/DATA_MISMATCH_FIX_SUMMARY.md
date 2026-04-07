# Data Mismatch Fix Summary

## Overview

This document summarizes fixes for data mismatches discovered between GMAMS records and CMS records.

---

## Problems Found

1. **Student names inconsistently formatted** — Some records in CMS used "LASTNAME, Firstname" format while GMAMS used "Firstname Lastname". This caused display inconsistencies on certificates.
2. **Course codes mismatched** — CMS stored abbreviated course codes (e.g., `BSIT`) while GMAMS stored full names (e.g., `Bachelor of Science in Information Technology`). Filtering by course failed.
3. **Duplicate accounts** — Some students had two GMAMS accounts (one synced from CMS, one manually created) with slightly different email capitalizations.

---

## Fixes Applied

### Fix 1: Normalize name format on sync

During `GoodMoralSyncService::syncUsers()`, names are normalized to "Firstname Lastname" format:

```php
$name = $this->normalizeName($cmsUser->name);
```

### Fix 2: Unify course code storage

Course codes are now stored as short codes in both systems. The display name is resolved from the course lookup table at render time.

### Fix 3: Deduplicate by case-insensitive email

During sync, email comparison is case-insensitive (`LOWER(email)`). On conflict, the CMS record is treated as the authoritative source.

---

## Prevention

- All future syncs normalize name and email before insert/update.
- Admin UI shows a warning badge when a student has duplicate email variants.

---

## Related Files

- `app/Services/GoodMoralSyncService.php`
- [DATA_SYNCHRONIZATION_FIX_SUMMARY.md](DATA_SYNCHRONIZATION_FIX_SUMMARY.md)
- [DUPLICATE_EMAIL_FIX.md](DUPLICATE_EMAIL_FIX.md)
