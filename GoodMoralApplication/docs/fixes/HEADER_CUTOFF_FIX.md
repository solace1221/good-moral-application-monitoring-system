# Fix: PDF Certificate Header Cutoff

## Problem

The university logo and header information (institution name, address) were cut off or partially hidden in generated PDF certificates.

---

## Root Cause

The certificate Blade template's header container had a `padding` of `130px`, which caused the header block to overflow its allocated space within wkhtmltopdf's rendering. The PDF generator could not crop the overflow, resulting in header content being hidden.

---

## Solution

Reduced the header container padding and added a maximum height constraint:

```css
/* Before */
.certificate-header {
    padding: 130px 20px 20px 20px;
}

/* After */
.certificate-header {
    padding: 40px 20px 20px 20px;
    max-height: 120px;
}
```

---

## Files Modified

- `resources/views/certificates/good-moral.blade.php` — header CSS updated
- `resources/views/certificates/residency.blade.php` — same change applied

---

## Testing

1. Generate a certificate as Admin.
2. Download the PDF.
3. Confirm the full header (logo, institution name, tagline) is visible and not clipped.
