# Visual Summary — System Architecture

## System Overview

```
┌──────────────────────────────────────────────────────┐
│              SHARED DATABASE                         │
│              db-clearance-system                     │
│              MySQL · localhost:3306                  │
└──────────────────┬───────────────────────────────────┘
                   │
        ┌──────────┴──────────┐
        │                     │
┌───────▼────────┐   ┌────────▼──────────┐
│  GMAMS         │   │  CMS              │
│  (Good Moral   │   │  (Clearance Mgmt  │
│   Application) │   │   System)         │
│                │   │                   │
│  Port: 8000    │   │  Port: 8001       │
│  (or Herd)     │   │                   │
└───────┬────────┘   └────────┬──────────┘
        │                     │
        │  ◄── ClearanceSyncService (GMAMS→CMS)
        │  ──► GoodMoralSyncService (CMS→GMAMS)
        │                     │
        └──────────┬──────────┘
                   │
            Bidirectional
              Account Sync
```

---

## User Flow

```
Student Registers (GMAMS)
    │
    ├─► users table (auth)
    ├─► student_registrations table
    ├─► role_account table
    └─► ClearanceSyncService
           ├─► clearance_users table (CMS)
           ├─► students table (CMS)
           └─► clearances table (CMS)

Student Registers (CMS)
    │
    ├─► users table (CMS auth)
    ├─► students table (CMS)
    └─► GoodMoralSyncService
           ├─► users table (GMAMS auth)
           ├─► student_registrations table
           └─► role_account table
```

---

## Application Workflow

```
Student submits application
    │
    ▼
ApplicationController::store()
    │
    ├─► Validate receipt (upload + copy to temp)
    ├─► Store application record
    └─► Notify SecOSA / Admin

Admin reviews
    │
    ├─► Approve → status: "Approved by Administrator"
    └─► Reject  → status: "Rejected"

Admin prints certificate
    │
    ├─► First print → status: "Ready for Pickup"
    │                  Notify student
    └─► Reprint    → status unchanged
                     Filename: *_REPRINT.pdf
```

---

## Role Access Matrix

| Feature | Admin | SecOSA | Dean | ProgCoor | Student |
|---------|-------|--------|------|----------|---------|
| View all applications | ✓ | ✓ | — | — | Own only |
| Approve/reject | ✓ | ✓ | — | — | — |
| Print certificate | ✓ | — | — | — | — |
| View violations | ✓ | ✓ | Own dept | Own courses | — |
| Add violations | ✓ | ✓ | Own dept | — | — |
| Trends dashboard | ✓ | ✓ | — | — | — |
| User management | ✓ | — | — | — | — |
| Course management | ✓ | — | — | — | — |
