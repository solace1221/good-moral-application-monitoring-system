# Fix: Good Moral Report PDF Issues

## Problem

Generated PDF reports for Good Moral and Residency certificates had multiple formatting issues:
- Content was cut off at page boundaries
- Signatures and seal areas appeared on incorrect pages
- Margins were inconsistent between the preview and the downloaded PDF

---

## Root Cause

The wkhtmltopdf page size and margin settings in the PDF generator were not aligned with the certificate template's CSS dimensions. The template assumed A4 margins but the generator was using default margins.

---

## Solution

### 1. Explicit Margin Settings in PDF Generator

```php
$pdf = PDF::loadView('certificates.good-moral', $data)
    ->setPaper('A4', 'portrait')
    ->setOptions([
        'margin-top'    => '15mm',
        'margin-right'  => '15mm',
        'margin-bottom' => '15mm',
        'margin-left'   => '15mm',
        'dpi'           => 150,
        'enable-local-file-access' => true,
    ]);
```

### 2. CSS Page Break Prevention

Added `page-break-inside: avoid` to seal and signature sections:

```css
.certificate-footer,
.signature-section,
.seal-area {
    page-break-inside: avoid;
}
```

### 3. Header Height Fix

Reduced header container padding from `130px` to `40px` and added `max-height: 120px` to prevent the header from consuming the first page. See [HEADER_CUTOFF_FIX.md](HEADER_CUTOFF_FIX.md) for details.

---

## Files Modified

- `app/Http/Controllers/AdminController.php` — PDF generation options updated
- `resources/views/certificates/good-moral.blade.php` — page-break CSS added
- `resources/views/certificates/residency.blade.php` — same CSS applied

---

## Testing

1. Generate a Good Moral Certificate as Admin.
2. Download the PDF.
3. Verify the full certificate fits on one page with no content cut off.
4. Verify the seal and signature block appear at the bottom of the correct page.
