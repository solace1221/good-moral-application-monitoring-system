# Immediate Actions Required — Pre-Production Checklist

Complete all items before deploying GMAMS to a production environment.

---

## Environment Configuration

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate a strong `APP_KEY` (run `php artisan key:generate` if not set)
- [ ] Set `APP_URL` to the real production domain
- [ ] Configure `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` for production database
- [ ] Set real Gmail SMTP credentials in `.env`:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=your_email@gmail.com
  MAIL_PASSWORD=your_app_password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=your_email@gmail.com
  ```

---

## Security

- [ ] Remove or disable all test/debug routes (e.g., `/test-*`, `/debug-*`)
- [ ] Remove test helper PHP scripts from the project root (`check_*.php`, `create_*.php`, etc.)
- [ ] Enable HTTPS in `AppServiceProvider.php`:
  ```php
  if (app()->environment('production')) {
      URL::forceScheme('https');
  }
  ```
- [ ] Set `SESSION_SECURE_COOKIE=true` in `.env` for HTTPS
- [ ] Set `COOKIE_SECURE=true`
- [ ] Review `.gitignore` — ensure `.env` is never committed

---

## Performance

- [ ] Run `php artisan optimize` to cache routes and config
- [ ] Enable OPcache in PHP configuration
- [ ] Configure proper database indexing (verify via `EXPLAIN` on heavy queries)

---

## Backups

- [ ] Configure automated database backup (see [docs/setup/BACKUP_SETUP_GUIDE.md](../setup/BACKUP_SETUP_GUIDE.md))
- [ ] Test restore procedure before going live

---

## Final Checks

- [ ] Test all 7 user role logins
- [ ] Test application submission flow end-to-end
- [ ] Test certificate generation and download
- [ ] Test email notifications
- [ ] Verify bidirectional sync (register in CMS → check GMAMS, and vice versa)
