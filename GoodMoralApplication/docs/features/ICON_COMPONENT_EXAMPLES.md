# Icon Component Usage Examples

## Overview

GMAMS uses a custom `<x-icon>` Blade component backed by Heroicons SVGs. This document provides usage examples for common patterns.

---

## Basic Usage

```blade
{{-- Simple icon --}}
<x-icon name="dashboard" />

{{-- With custom size (px) --}}
<x-icon name="users" size="24" />

{{-- With Tailwind color class --}}
<x-icon name="warning" class="text-yellow-500" />

{{-- With custom stroke width --}}
<x-icon name="edit" stroke="2" />
```

---

## Navigation

```blade
{{-- Nav link with icon --}}
<a href="{{ route('admin.dashboard') }}" class="nav-link">
    <x-icon name="squares-2x2" class="nav-icon" />
    <span>Dashboard</span>
</a>

{{-- Active state --}}
<li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
    <a href="{{ route('admin.users.index') }}">
        <x-icon name="users" class="nav-icon" />
        <span>Users</span>
    </a>
</li>
```

---

## Buttons

```blade
{{-- Submit button with icon --}}
<button type="submit" class="btn btn-primary">
    <x-icon name="check" size="16" class="mr-2" />
    Save Changes
</button>

{{-- Icon-only button (must have aria-label) --}}
<button type="button" aria-label="Edit item">
    <x-icon name="pencil" size="18" />
</button>

{{-- Search button --}}
<button type="submit">
    <x-icon name="magnifying-glass" size="16" />
    <span class="ml-2">Search</span>
</button>
```

---

## Forms

```blade
{{-- Read-only field with lock icon --}}
<div class="input-wrapper">
    <input type="text" value="{{ $course }}" readonly>
    <x-icon name="lock-closed" size="16" class="text-gray-400" />
</div>

{{-- Validation error with icon --}}
@error('email')
    <div class="flex items-center text-red-600 text-sm mt-1">
        <x-icon name="exclamation-circle" size="16" class="mr-1" />
        {{ $message }}
    </div>
@enderror
```

---

## Status Indicators

```blade
{{-- Application status badges --}}
@if ($application->application_status === 'Approved by Administrator')
    <span class="badge badge-green">
        <x-icon name="check-circle" size="14" class="mr-1" />
        Approved
    </span>
@elseif ($application->application_status === 'Rejected')
    <span class="badge badge-red">
        <x-icon name="x-circle" size="14" class="mr-1" />
        Rejected
    </span>
@else
    <span class="badge badge-yellow">
        <x-icon name="clock" size="14" class="mr-1" />
        Pending
    </span>
@endif
```

---

## Icon Name Reference

| Context | Icon Name |
|---------|-----------|
| Dashboard | `squares-2x2` |
| Users / Students | `users` |
| Violations | `exclamation-triangle` |
| Applications | `document-text` |
| Certificates | `document-check` |
| Reports | `chart-bar-square` |
| Profile | `user-circle` |
| Settings | `cog-6-tooth` |
| Lock / Read-only | `lock-closed` |
| Search | `magnifying-glass` |
| Edit | `pencil` |
| Delete | `trash` |
| Approve / Check | `check-circle` |
| Reject | `x-circle` |
| Warning | `exclamation-triangle` |
| Info | `information-circle` |
| Calendar | `calendar-days` |
| Download | `arrow-down-tray` |
| Print | `printer` |
