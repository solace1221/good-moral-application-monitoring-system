# Good Moral Application and Monitoring System (GMAMS)

**Institution**: St. Paul University Philippines (SPUP)  
**Framework**: Laravel 12 / PHP 8.4  
**Database**: MySQL (`db-clearance-system`)  
**Local URL**: `https://goodmoralapplication.test`

---

## Overview

GMAMS is a web-based system for managing student good moral certificate applications, tracking disciplinary violations, and generating official certificates for SPUP students. It integrates bidirectionally with the Clearance Management System (CMS) and supports seven distinct user roles.

---

## User Roles

| Role | Description |
|------|-------------|
| `admin` | Full system access; manages applications, violations, users |
| `moderator` / `secosa` | OSA staff; moderates applications and violations |
| `dean` | Department dean; views and manages violations for their school |
| `deansom` | Dean of Student Organization Management |
| `deangradsch` | Dean of Graduate School |
| `progcoor` | Program coordinator; manages course-level student data |
| `student` | Submits good moral certificate applications |
| `psg` | PSG officers with limited access |
| `alumni` | Former students applying for certificates |

---

## System Integration

GMAMS shares the `db-clearance-system` MySQL database with the Clearance Management System:

- **GMAMS** — runs on `https://goodmoralapplication.test` (Herd) or `http://localhost:8000`
- **CMS** — runs on `http://localhost:8001`
- Bidirectional sync ensures accounts created in either system are available in both.

---

## Core Features

- Student application submission with receipt upload and validation
- Admin and SecOSA approval workflow
- Good Moral and Residency certificate PDF generation (wkhtmltopdf)
- Violation tracking (major/minor) with academic year trend analysis
- Role-based access control (RBAC) across all dashboards
- Automated email notifications (Gmail SMTP)
- Database backup scripts (PowerShell + batch)
- Mobile-responsive UI (Tailwind CSS + Heroicons)

---

## Quick Links

| Topic | File |
|-------|------|
| Architecture | [docs/architecture/ARCHITECTURE_DIAGRAM.md](../architecture/ARCHITECTURE_DIAGRAM.md) |
| Security | [docs/security/SECURITY_AUDIT_REPORT.md](../security/SECURITY_AUDIT_REPORT.md) |
| Setup & Deployment | [docs/setup/DEPLOYMENT_GUIDE_BIDIRECTIONAL_SYNC.md](../setup/DEPLOYMENT_GUIDE_BIDIRECTIONAL_SYNC.md) |
| Demo Guide | [docs/demo/8_MINUTE_DEMO_GUIDE.md](../demo/8_MINUTE_DEMO_GUIDE.md) |
| Fixes Index | [docs/fixes/](../fixes/) |
