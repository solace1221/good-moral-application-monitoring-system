# Modern Icon Recommendations

## Overview

This document provides recommendations for updating the icon system across all dashboards to Heroicons, with specific icon mappings for each role and section.

---

## Recommended Library

**Heroicons** — maintained by the Tailwind CSS team. Clean outline style, SVG-based, consistent stroke width. Reference: https://heroicons.com/

**Alternative**: Lucide Icons (https://lucide.dev/) — for icons not available in Heroicons.

---

## Icon Audit and Replacements

### Dean Dashboard

| Section | Replace With |
|---------|-------------|
| Dashboard home | `squares-2x2` |
| Violations (dropdown) | `exclamation-triangle` |
| Major violations | `shield-exclamation` (solid) |
| Minor violations | `shield-check` (outline) |
| Applications | `document-text` |
| Reports | `chart-bar-square` |
| Profile | `user-circle` |

### Program Coordinator Dashboard

| Section | Replace With |
|---------|-------------|
| Dashboard | `squares-2x2` |
| Students | `academic-cap` or `users` |
| Courses | `book-open` |
| Clear button | Fix contrast: `bg-white text-gray-700 border` + `x-circle` icon |

### SecOSA (Moderator) Dashboard

| Section | Replace With |
|---------|-------------|
| Dashboard | `squares-2x2` |
| Violations (dropdown) | `exclamation-triangle` |
| Major violations | `shield-exclamation` |
| Minor violations | `shield-check` |
| Certificate Management | `document-check` |
| Students | `users` |

### Admin Dashboard

| Section | Replace With |
|---------|-------------|
| Dashboard | `squares-2x2` |
| Applications | `clipboard-document-list` |
| Violations | `exclamation-triangle` |
| Users | `user-group` |
| Courses | `book-open` |
| Reports | `presentation-chart-bar` |
| Settings | `cog-6-tooth` |

---

## Implementation Strategy

1. Create or update `resources/views/components/icon.blade.php` if not already done.
2. Replace all inline SVG blocks and Font Awesome classes with `<x-icon name="..." />`.
3. Use `size="18"` for sidebar navigation icons, `size="16"` for inline/button icons.
4. Ensure all icon-only buttons have `aria-label` attributes.

For code examples, see [ICON_COMPONENT_EXAMPLES.md](ICON_COMPONENT_EXAMPLES.md).
