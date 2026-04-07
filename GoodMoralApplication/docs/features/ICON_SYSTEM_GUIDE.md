# Icon System Guide — Heroicons

## Overview

GMAMS uses **Heroicons** as its icon library, integrated via a custom `<x-icon>` Blade component. Heroicons are SVG-based, clean, and maintained by the Tailwind CSS team.

---

## Library Details

| Property | Value |
|----------|-------|
| Library | Heroicons |
| Style | Outline (default), Solid (optional) |
| Format | Inline SVG via Blade component |
| Provider | Tailwind CSS team |
| Reference | https://heroicons.com/ |

---

## Component

**File**: `resources/views/components/icon.blade.php`

The component renders the appropriate SVG based on the `name` prop.

```blade
{{-- Usage --}}
<x-icon name="icon-name" size="20" class="text-gray-600" stroke="1.5" />
```

| Prop | Default | Description |
|------|---------|-------------|
| `name` | required | Heroicon name (kebab-case) |
| `size` | `20` | Width and height in pixels |
| `class` | `""` | Tailwind CSS classes |
| `stroke` | `1.5` | SVG stroke width |

---

## Recommended Icons by Section

### Dashboard Navigation

| Section | Icon |
|---------|------|
| Dashboard home | `squares-2x2` |
| Violations (dropdown) | `exclamation-triangle` |
| Major violations | `shield-exclamation` |
| Minor violations | `shield-check` |
| Applications | `document-text` |
| Certificates | `document-check` |
| Reports | `chart-bar-square` |
| Profile | `user-circle` |
| Settings | `cog-6-tooth` |
| Students | `users` |
| Courses | `book-open` |

### Actions

| Action | Icon |
|--------|------|
| Edit | `pencil` |
| Delete | `trash` |
| View | `eye` |
| Download | `arrow-down-tray` |
| Print | `printer` |
| Search | `magnifying-glass` |
| Filter | `funnel` |
| Save | `check` |
| Cancel | `x-mark` |

### Status

| Status | Icon |
|--------|------|
| Approved | `check-circle` |
| Rejected | `x-circle` |
| Pending | `clock` |
| Warning | `exclamation-circle` |
| Lock (read-only) | `lock-closed` |
| Info | `information-circle` |

---

## Alternative: Lucide Icons

If Heroicons does not have a needed icon, **Lucide Icons** (https://lucide.dev/) is the recommended alternative. Lucide uses a consistent stroke width and similar naming convention.

---

## Accessibility

- Icon-only buttons **must** include `aria-label`.
- Decorative icons (e.g., inline with label text) should have `aria-hidden="true"`.

```blade
{{-- Accessible icon button --}}
<button aria-label="Delete record">
    <x-icon name="trash" size="18" aria-hidden="true" />
</button>
```
