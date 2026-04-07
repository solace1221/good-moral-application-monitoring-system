# Changelog

## GMAMS — Good Moral Application and Monitoring System

---

## [Unreleased]

### Added
- Bidirectional synchronization between GMAMS and CMS (`GoodMoralSyncService`, `ClearanceSyncService::updateUser()`)
- Batch sync utility script: `batch_sync_cms_to_gmams.php`
- Semester dropdown with predefined academic year options (2023–2024, 2024–2025)
- Certificate reprint support with `_REPRINT` filename suffix
- Heroicons via `<x-icon>` Blade component
- Mobile-responsive logo fixes (`object-fit: contain`)
- Department population configuration (SITE: 640, SBAHM: 727, SNAHS: 2,831, SASTE: 409)
- Trends analysis dashboard with Previous AY (2023–2024) hardcoded data and real-time Current AY

### Fixed
- Admin approved applications view showing wrong count
- Admin dashboard RBAC — unauthorized roles blocked from accessing admin routes
- Certificate name formatting (`formatNameForCertificate()` moved to global `app/helpers.php`)
- Dean dashboard redirect (`/dashboard` now routes by `account_type`)
- Dean routes missing `role:dean,deansom,deangradsch` middleware
- Duplicate email error on registration
- `getimagesize()` fatal error during receipt validation (temp file copy + error suppression)
- PDF header cutoff (padding reduced from 130px to 40px)
- PDF header position margin adjustment for wkhtmltopdf
- Image stretching on mobile (logos)
- Population values hardcoded in `AdminController` and `SecOSAController`
- Receipt file upload validation error (PHP temp file deletion race condition)
- Reprint button failing after status changed to "Ready for Pickup"
- Violation form redirect after submission

### Changed
- Course field on application form is now static (read-only, populated from student profile)
- Static course system replaces dynamic course dropdown
