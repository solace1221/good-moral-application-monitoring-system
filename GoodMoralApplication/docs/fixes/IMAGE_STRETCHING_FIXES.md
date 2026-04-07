# Fix: Image Stretching on Mobile

## Problem

University logos in the navigation header were being stretched or distorted on small screens (mobile phones and tablets).

---

## Root Cause

The images were inside flex containers without explicit sizing constraints. On mobile, the flex layout caused images to stretch to fill available space.

---

## Solution

Applied `object-fit: contain` and `flex-shrink: 0` to all logo images:

```css
/* Navbar logo images */
.navbar-logo {
    object-fit: contain;
    flex-shrink: 0;
    max-height: 48px;
    width: auto;
}
```

Also applied `max-width` constraints to prevent oversized rendering on larger screens:

```css
@media (max-width: 768px) {
    .navbar-logo {
        max-height: 36px;
    }
}
```

---

## Files Modified

- `resources/css/app.css` (or equivalent stylesheet) — logo sizing rules added
- Affected Blade layout files — ensured logo `img` tags have the `navbar-logo` class

---

## Testing

1. Open the application on a mobile device or use Chrome DevTools responsive mode.
2. Check all pages with logos (login, dashboard, certificate view).
3. Confirm logos display at their natural aspect ratio with no stretching.
