# UI/UX Quick Reference

## Overview

This document is a concise reference card for all UI/UX patterns, components, and conventions used across the GMAMS dashboards.

---

## Icon System

Uses **Heroicons** via the `<x-icon>` Blade component.

```blade
{{-- Basic usage --}}
<x-icon name="dashboard" />

{{-- With size --}}
<x-icon name="users" size="24" />

{{-- With color class --}}
<x-icon name="warning" class="text-yellow-500" />
```

Common icon names:

| Context | Icon Name |
|---------|-----------|
| Dashboard | `squares-2x2` |
| Violations | `exclamation-triangle` |
| Applications | `document-text` |
| Reports | `chart-bar-square` |
| Profile | `user-circle` |
| Settings | `cog-6-tooth` |
| Students | `users` |
| Courses | `book-open` |
| Certificates | `document-check` |

---

## Color Conventions

| Status | Color | Tailwind Class |
|--------|-------|----------------|
| Pending | Yellow | `text-yellow-600`, `bg-yellow-100` |
| Approved | Green | `text-green-600`, `bg-green-100` |
| Rejected | Red | `text-red-600`, `bg-red-100` |
| Ready for Pickup | Blue | `text-blue-600`, `bg-blue-100` |
| Increasing violations | Red | `text-red-500` |
| Decreasing violations | Green | `text-green-500` |
| Stable | Gray | `text-gray-500` |

---

## Form Conventions

- All required fields use `required` attribute and `*` label suffix.
- Readonly fields display a lock icon (`<x-icon name="lock" />`).
- Validation errors shown below the input in `text-red-600`.
- Form state preserved on validation failure via `old()`.

---

## Dashboard Trend Indicators

| Symbol | Meaning |
|--------|---------|
| Increasing (red arrow) | Current AY violations > Previous AY |
| Decreasing (green arrow) | Current AY violations < Previous AY |
| Stable (gray arrow) | No change |

Variance formula:

```
Variance (%) = ((Previous AY - Current AY) / Previous AY) × 100
```

Positive = fewer violations (improvement). Negative = more violations.

---

## PDF Certificates

- Generator: **wkhtmltopdf**
- Header max-height: `120px`; padding: `40px`
- First print: status → `Ready for Pickup`; filename: `GoodMoral_Certificate_{student_id}_{ref}.pdf`
- Reprint: status unchanged; filename appended with `_REPRINT`

---

## Mobile Responsiveness

- Logos: `object-fit: contain`, `flex-shrink: 0`
- Breakpoints follow Tailwind defaults (`sm`, `md`, `lg`, `xl`)
- All tables scroll horizontally on small screens

---

## Navigation Structure

Each dashboard (Admin, Dean, SecOSA, ProgCoor) follows this layout:

```
Sidebar
  └─ Logo
  └─ Navigation links (with icons)
       └─ Dashboard
       └─ Violations (dropdown)
            └─ Major Violations
            └─ Minor Violations
       └─ Applications / Certificates
       └─ Reports
       └─ Profile
```
