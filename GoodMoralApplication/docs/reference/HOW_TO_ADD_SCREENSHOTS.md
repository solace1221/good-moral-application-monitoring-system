# How to Add Screenshots to the README

## Overview

This guide explains how to add screenshots to the GitHub README for GMAMS.

---

## Method 1: Upload via GitHub Issues (Recommended)

1. Open the project repository on GitHub.
2. Go to **Issues** → **New Issue**.
3. Drag and drop your screenshot image into the issue description field.
4. GitHub will generate a URL like:
   ```
   https://user-images.githubusercontent.com/.../<filename>.png
   ```
5. Copy the URL. You do **not** need to submit the issue.
6. Paste the URL into your `README.md`:
   ```markdown
   ![Screenshot description](https://user-images.githubusercontent.com/.../<filename>.png)
   ```

---

## Method 2: Commit Screenshots to the Repository

1. Create a `screenshots/` folder in the project root.
2. Add your screenshot files there:
   ```
   screenshots/
     admin-dashboard.png
     student-application.png
     certificate-preview.png
   ```
3. Reference them in `README.md` using relative paths:
   ```markdown
   ![Admin Dashboard](screenshots/admin-dashboard.png)
   ```

---

## Screenshot Naming Conventions

| Screen | Suggested Filename |
|--------|-------------------|
| Admin dashboard | `admin-dashboard.png` |
| Student application form | `student-application-form.png` |
| Good moral certificate PDF | `certificate-preview.png` |
| Violation tracking table | `violations-table.png` |
| Login page | `login-page.png` |

---

## Tips

- Use PNG format for UI screenshots (better quality than JPEG for text).
- Crop screenshots to the relevant area; avoid full-browser captures.
- Recommended width: 1200px maximum.
