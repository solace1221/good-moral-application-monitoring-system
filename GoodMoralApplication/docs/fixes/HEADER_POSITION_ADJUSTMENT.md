# Fix: PDF Header Position Adjustment

## Problem

After reducing the header padding (see [HEADER_CUTOFF_FIX.md](HEADER_CUTOFF_FIX.md)), the header appeared too close to the top edge of the printed certificate, with insufficient margin between the paper edge and the header content.

---

## Solution

Adjusted the wkhtmltopdf top margin in the PDF generation call to provide adequate spacing:

```php
$pdf = PDF::loadView('certificates.good-moral', $data)
    ->setPaper('A4', 'portrait')
    ->setOptions([
        'margin-top'    => '20mm',   // Increased from 10mm
        'margin-right'  => '15mm',
        'margin-bottom' => '15mm',
        'margin-left'   => '15mm',
    ]);
```

This provides a 20mm margin from the paper edge to the start of the header, preventing the header from appearing cramped at the top.

---

## Files Modified

- `app/Http/Controllers/AdminController.php` — PDF options `margin-top` updated

---

## Testing

1. Generate a certificate.
2. Visually confirm the header has appropriate whitespace above it.
3. Confirm content does not extend below the bottom margin on the final page.
