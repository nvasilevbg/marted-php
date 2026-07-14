# MarTed — PHP + MySQL

Сайт за монтаж и демонтаж на мебели (гр. Добрич), изработен на **PHP + MySQL** за shared hosting (superhosting.bg).

## Локален тест
Сайтът работи със SQLite (auto-seed). Достатъчно е PHP 8+ с `pdo_sqlite`.

```bash
php -S localhost:8080 -t .
```

Отвори `http://localhost:8080`.

## Production (superhosting.bg)
1. Качи всички файлове в `public_html/` (FTP/cPanel File Manager).
2. В cPanel → MySQL Databases → създай база + потребител, добави потребителя към базата.
3. Копирай `config.sample.php` → `inc/config.php` и попълни:
   - `driver` => `'mysql'`
   - `mysql.host/name/user/pass` → cPanel MySQL данните
   - `admin_pass` → силна парола за админа
4. В cPanel → phpMyAdmin → избери базата → SQL → копирай и изпълни `schema.sql`.
5. Увери се, че папката `assets/media/projects/` има права за запис (755 или 775).
6. Сайтът: `https://vashiqt-domajn.bg/`
7. Админ: `https://vashiqt-domajn.bg/admin/`

## Структура
```
index.php          начална
uslugi.php         услуги
proekti.php        проекти (филтри)
proekt.php         проект detail (?slug=)
kontakti.php       контакти + карта
zapazi.php         запази час (календар)
za-nas.php          за нас
admin/             админ панел (проекти + резервации)
api/taken.php      GET заети часове (JSON)
api/book.php       POST нова резервация
inc/               PHP helpers (DB, auth, partials)
assets/css/        стилове (32KB)
assets/js/         календар + интеракции
assets/media/      снимки + лого
data/              SQLite база (локално)
schema.sql         MySQL schema + seed
```

## Backup
При всяка промяна в базата — export от phpMyAdmin или `mysqldump`.