# MarTed â€” PHP + MySQL

Ð¡Ð°Ð¹Ñ‚ Ð·Ð° Ð¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñ‚Ð°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸ (Ð³Ñ€. Ð”Ð¾Ð±Ñ€Ð¸Ñ‡), Ð¸Ð·Ñ€Ð°Ð±Ð¾Ñ‚ÐµÐ½ Ð½Ð° **PHP + MySQL** Ð·Ð° shared hosting (superhosting.bg).

## Ð›Ð¾ÐºÐ°Ð»ÐµÐ½ Ñ‚ÐµÑÑ‚
Ð¡Ð°Ð¹Ñ‚ÑŠÑ‚ Ñ€Ð°Ð±Ð¾Ñ‚Ð¸ ÑÑŠÑ SQLite (auto-seed). Ð”Ð¾ÑÑ‚Ð°Ñ‚ÑŠÑ‡Ð½Ð¾ Ðµ PHP 8+ Ñ `pdo_sqlite`.

```bash
php -S localhost:8080 -t .
```

ÐžÑ‚Ð²Ð¾Ñ€Ð¸ `http://localhost:8080`.

## Production (superhosting.bg)
1. ÐšÐ°Ñ‡Ð¸ Ð²ÑÐ¸Ñ‡ÐºÐ¸ Ñ„Ð°Ð¹Ð»Ð¾Ð²Ðµ Ð² `public_html/` (FTP/cPanel File Manager).
2. Ð’ cPanel â†’ MySQL Databases â†’ ÑÑŠÐ·Ð´Ð°Ð¹ Ð±Ð°Ð·Ð° + Ð¿Ð¾Ñ‚Ñ€ÐµÐ±Ð¸Ñ‚ÐµÐ», Ð´Ð¾Ð±Ð°Ð²Ð¸ Ð¿Ð¾Ñ‚Ñ€ÐµÐ±Ð¸Ñ‚ÐµÐ»Ñ ÐºÑŠÐ¼ Ð±Ð°Ð·Ð°Ñ‚Ð°.
3. ÐšÐ¾Ð¿Ð¸Ñ€Ð°Ð¹ `config.sample.php` â†’ `inc/config.php` Ð¸ Ð¿Ð¾Ð¿ÑŠÐ»Ð½Ð¸:
   - `driver` => `'mysql'`
   - `mysql.host/name/user/pass` â†’ cPanel MySQL Ð´Ð°Ð½Ð½Ð¸Ñ‚Ðµ
   - `admin_pass` â†’ ÑÐ¸Ð»Ð½Ð° Ð¿Ð°Ñ€Ð¾Ð»Ð° Ð·Ð° Ð°Ð´Ð¼Ð¸Ð½Ð°
4. Ð’ cPanel â†’ phpMyAdmin â†’ Ð¸Ð·Ð±ÐµÑ€Ð¸ Ð±Ð°Ð·Ð°Ñ‚Ð° â†’ SQL â†’ ÐºÐ¾Ð¿Ð¸Ñ€Ð°Ð¹ Ð¸ Ð¸Ð·Ð¿ÑŠÐ»Ð½Ð¸ `schema.sql`.
5. Ð£Ð²ÐµÑ€Ð¸ ÑÐµ, Ñ‡Ðµ Ð¿Ð°Ð¿ÐºÐ°Ñ‚Ð° `assets/media/projects/` Ð¸Ð¼Ð° Ð¿Ñ€Ð°Ð²Ð° Ð·Ð° Ð·Ð°Ð¿Ð¸Ñ (755 Ð¸Ð»Ð¸ 775).
6. Ð¡Ð°Ð¹Ñ‚ÑŠÑ‚: `https://vashiqt-domajn.bg/`
7. ÐÐ´Ð¼Ð¸Ð½: `https://vashiqt-domajn.bg/admin/`

## Ð¡Ñ‚Ñ€ÑƒÐºÑ‚ÑƒÑ€Ð°
```
index.php          Ð½Ð°Ñ‡Ð°Ð»Ð½Ð°
uslugi.php         ÑƒÑÐ»ÑƒÐ³Ð¸
proekti.php        Ð¿Ñ€Ð¾ÐµÐºÑ‚Ð¸ (Ñ„Ð¸Ð»Ñ‚Ñ€Ð¸)
proekt.php         Ð¿Ñ€Ð¾ÐµÐºÑ‚ detail (?slug=)
kontakti.php       ÐºÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð¸ + ÐºÐ°Ñ€Ñ‚Ð°
zapazi.php         Ð·Ð°Ð¿Ð°Ð·Ð¸ Ñ‡Ð°Ñ (ÐºÐ°Ð»ÐµÐ½Ð´Ð°Ñ€)
za-nas.php          Ð·Ð° Ð½Ð°Ñ
admin/             Ð°Ð´Ð¼Ð¸Ð½ Ð¿Ð°Ð½ÐµÐ» (Ð¿Ñ€Ð¾ÐµÐºÑ‚Ð¸ + Ñ€ÐµÐ·ÐµÑ€Ð²Ð°Ñ†Ð¸Ð¸)
api/taken.php      GET Ð·Ð°ÐµÑ‚Ð¸ Ñ‡Ð°ÑÐ¾Ð²Ðµ (JSON)
api/book.php       POST Ð½Ð¾Ð²Ð° Ñ€ÐµÐ·ÐµÑ€Ð²Ð°Ñ†Ð¸Ñ
inc/               PHP helpers (DB, auth, partials)
assets/css/        ÑÑ‚Ð¸Ð»Ð¾Ð²Ðµ (32KB)
assets/js/         ÐºÐ°Ð»ÐµÐ½Ð´Ð°Ñ€ + Ð¸Ð½Ñ‚ÐµÑ€Ð°ÐºÑ†Ð¸Ð¸
assets/media/      ÑÐ½Ð¸Ð¼ÐºÐ¸ + Ð»Ð¾Ð³Ð¾
data/              SQLite Ð±Ð°Ð·Ð° (Ð»Ð¾ÐºÐ°Ð»Ð½Ð¾)
schema.sql         MySQL schema + seed
```

## Backup
ÐŸÑ€Ð¸ Ð²ÑÑÐºÐ° Ð¿Ñ€Ð¾Ð¼ÑÐ½Ð° Ð² Ð±Ð°Ð·Ð°Ñ‚Ð° â€” export Ð¾Ñ‚ phpMyAdmin Ð¸Ð»Ð¸ `mysqldump`.

## Demo
Deployed via Coolify with auto-deploy from GitHub.
