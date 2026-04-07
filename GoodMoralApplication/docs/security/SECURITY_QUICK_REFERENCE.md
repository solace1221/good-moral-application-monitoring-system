# Security Quick Reference

## Critical `.env` Settings (Production)

```env
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

---

## HTTPS Enforcement

Location: `app/Providers/AppServiceProvider.php`

```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}
```

---

## Middleware Aliases

Defined in `app/Http/Kernel.php` (or `bootstrap/app.php` in Laravel 12):

| Alias | Class | Purpose |
|---|---|---|
| `auth` | `Authenticate` | Require login |
| `role:admin` | `CheckRole` | Restrict to admins |
| `role:dean` | `CheckRole` | Restrict to deans |
| `role:student` | `CheckRole` | Restrict to students |

---

## Route Group Pattern

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // admin-only routes
});
```

---

## Input Validation Pattern

```php
$validated = $request->validate([
    'name'  => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . $user->id,
]);
```

---

## What NOT to Do

- Do NOT set `APP_DEBUG=true` in production — it exposes stack traces and env values.
- Do NOT use raw SQL string interpolation — always use `?` bindings.
- Do NOT leave test/debug routes active in production.
