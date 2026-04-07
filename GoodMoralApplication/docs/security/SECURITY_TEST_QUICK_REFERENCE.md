# Security Test Quick Reference

## Test Accounts (Per Role)

Use these accounts to verify that role-based access control is working correctly.

| Role | Email | Password |
|---|---|---|
| Admin | admin@spup.edu.ph | password |
| Dean | dean@spup.edu.ph | password |
| Program Coordinator | progcoor@spup.edu.ph | password |
| SecOSA / Moderator | secosa@spup.edu.ph | password |
| Student | student@spup.edu.ph | password |
| PSG Officer | psg@spup.edu.ph | password |

> **Note**: Change all default passwords before deploying to production.

---

## Access Control Test Cases

### Test: Student cannot access admin routes

1. Log in as student.
2. Navigate to `/admin/dashboard`.
3. **Expected**: Redirect to student dashboard or 403 Forbidden.
4. **Fail condition**: Admin dashboard loads.

### Test: Dean cannot access admin user management

1. Log in as dean.
2. Navigate to `/admin/users`.
3. **Expected**: Redirect to dean dashboard or 403.
4. **Fail condition**: User management page loads.

### Test: Unauthenticated user redirected to login

1. Open a private/incognito browser.
2. Navigate to `/student/dashboard`.
3. **Expected**: Redirect to `/login`.
4. **Fail condition**: Dashboard loads without authentication.

---

## Session Security Tests

### Test: Session cookie is secure

1. Open browser DevTools → Application → Cookies.
2. Locate the `laravel_session` cookie.
3. **Expected (production)**: `Secure` flag is checked, `HttpOnly` flag is checked.

### Test: Session invalidated on logout

1. Log in, copy session cookie value.
2. Log out.
3. Try to use the copied cookie in a new request.
4. **Expected**: Request is rejected / redirected to login.

---

## SQL Injection Test

1. In any search field, enter: `' OR '1'='1`
2. **Expected**: No extra rows returned; input treated as literal string.
3. **Fail condition**: Unexpected data is returned.
