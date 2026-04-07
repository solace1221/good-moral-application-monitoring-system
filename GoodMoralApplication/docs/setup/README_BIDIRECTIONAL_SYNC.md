# Bidirectional Sync — Overview

## What This Solution Does

This package provides complete bidirectional account synchronization between:
- **GMAMS** — Good Moral Application and Monitoring System
- **CMS** — Clearance Management System

---

## Problem Solved

| Scenario | Before | After |
|----------|--------|-------|
| Register in GMAMS | Account in GMAMS only | Account in both systems |
| Register in CMS | Account in CMS only | Account in both systems |
| Update profile in GMAMS | GMAMS only updated | GMAMS + CMS updated |
| Existing CMS users | Not in GMAMS | Batch-synced via script |

---

## Package Contents

### Core Files

| File | Description |
|------|-------------|
| `app/Services/GoodMoralSyncService.php` | New service: syncs CMS registrations to GMAMS |
| `app/Services/ClearanceSyncService.php` | Updated: added `updateUser()` for profile sync |
| `clearance-managment-system/.../RegisteredUserController.php` | Updated: calls `GoodMoralSyncService` after CMS registration |

### Utility Scripts

| File | Description |
|------|-------------|
| `batch_sync_cms_to_gmams.php` | Batch sync all existing CMS users to GMAMS |
| `test_bidirectional_sync.php` | Automated test suite |

### Documentation

| File | Description |
|------|-------------|
| [EXECUTIVE_SUMMARY.md](../reference/EXECUTIVE_SUMMARY.md) | High-level overview |
| [DATA_SYNC_ANALYSIS_AND_SOLUTION.md](../architecture/DATA_SYNC_ANALYSIS_AND_SOLUTION.md) | Full technical analysis |
| [DEPLOYMENT_GUIDE_BIDIRECTIONAL_SYNC.md](DEPLOYMENT_GUIDE_BIDIRECTIONAL_SYNC.md) | Step-by-step deployment |
| [ARCHITECTURE_DIAGRAM.md](../architecture/ARCHITECTURE_DIAGRAM.md) | System architecture |

---

## Quick Start

```bash
# 1. Run tests
php test_bidirectional_sync.php

# 2. Deploy (clear caches)
php artisan cache:clear
php artisan config:clear

# 3. Batch sync existing users (run once)
php batch_sync_cms_to_gmams.php
```

For full deployment instructions, see [DEPLOYMENT_GUIDE_BIDIRECTIONAL_SYNC.md](DEPLOYMENT_GUIDE_BIDIRECTIONAL_SYNC.md).
