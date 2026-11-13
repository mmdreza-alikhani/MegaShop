# داکری‌سازی پروژه مگاشاپ (MegaShop)

پروژه در `C:\laragon\www\megashop` یک اپلیکیشن **Laravel 12 / PHP 8.3** با **MySQL** است. تنظیمات زیر طوری ساخته شده که با یک دستور بالا بیاید: دیتابیس خودکار migrate و در اولین اجرا seed می‌شود، Nginx جدا از PHP-FPM اجرا می‌شود و فایل‌های آپلودشده در یک volume ماندگار می‌مانند.

> ⚠️ روی این سیستم فعلاً **Docker نصب نیست** (نه Docker Desktop و نه WSL2). فایل‌ها آماده‌اند، اما build/اجرا فقط بعد از نصب Docker Desktop انجام می‌شود.

---

## ۱) پیش‌نیاز

1. نصب **Docker Desktop** برای ویندوز: <https://www.docker.com/products/docker-desktop/>
2. در هنگام نصب گزینه **WSL 2** را فعال کنید (یا از قبل `wsl --install` را اجرا کنید).
3. بعد از نصب، Docker Desktop را باز کنید تا موتور داکر بالا بیاید و سپس در PowerShell بررسی کنید:

```powershell
docker --version
docker compose version
```

---

## ۲) فایل‌های اضافه‌شده

| فایل | وظیفه |
|---|---|
| `Dockerfile` | ایمیج PHP-FPM (target `app`) و ایمیج Nginx (target `web`) |
| `docker-compose.yml` | سرویس‌های `app` + `web` + `db` و `adminer` (پروفایل tools) |
| `docker-compose.dev.yml` | اورراید حالت توسعه؛ کد را mount می‌کند |
| `.env.docker` | متغیرهای محیطی کانتینر (شامل APP_KEY — در gitignore) |
| `.env.docker.example` | نمونهٔ بدون مقدار حساس |
| `docker/entrypoint.sh` | انتظار برای MySQL، اجرای migrate، seed خودکار، تنظیم مجوزها |
| `docker/nginx/default.conf` | کانفیگ Nginx با DocumentRoot روی `public` |
| `docker/php/php.ini` | محدودیت آپلود ۶۴MB، memory 512MB، timezone تهران |
| `docker/php/opcache.ini` | تنظیمات OPcache |
| `docker/php/fpm-pool.conf` | تنظیمات PHP-FPM (`clear_env = no` برای رسیدن envها) |
| `.dockerignore` | کوچک‌کردن build context و جلوگیری از رفتن `.env` به ایمیج |

نکتهٔ مهم: دیسک `public` در `config/filesystems.php` ریشه‌اش `public/storage` است. در ایمیج یک symlink از `public/storage` به `storage/app/public` ساخته می‌شود و volume مشترک بین `app` و `web` مانت می‌شود؛ بنابراین آپلودها هم توسط PHP نوشته و هم توسط Nginx سرو می‌شوند.

فرانت‌اند پروژه از قبل build شده و در `public/assets` قرار دارد؛ در ویوها از `@vite` استفاده نشده، پس مرحلهٔ `npm run build` در Dockerfile لازم نیست.

---

## ۳) اجرا

```powershell
cd C:\laragon\www\megashop
docker compose up -d --build
```

بعد از پایان build و healthy شدن سرویس‌ها:

- فروشگاه: <http://localhost:8080>
- Adminer (اختیاری): `docker compose --profile tools up -d adminer` → <http://localhost:8081>
- MySQL از روی هاست: `127.0.0.1:3307`

اولین اجرا چند دقیقه طول می‌کشد (composer install + seed دمو). ورود ادمین طبق `DEPLOY.md`:

| نقش | نام کاربری | رمز |
|---|---|---|
| مدیر | `admin` | `password` |
| مشتری | `sara.mohammadi` | `password` |
| مشتری | `amir.karimi` | `password` |

بررسی وضعیت:

```powershell
docker compose ps
docker compose logs -f app
docker compose logs -f web
```

---

## ۴) دستورهای روزمره

```powershell
# توقف
docker compose stop

# شروع مجدد
docker compose start

# حذف کانتینرها (دیتابیس و آپلودها باقی می‌مانند)
docker compose down

# اجرای artisan داخل کانتینر
docker compose exec app php artisan migrate:status
docker compose exec app php artisan cache:clear
docker compose exec app php artisan optimize

# shell داخل کانتینر
docker compose exec app sh

# ری‌استارت فقط اپ
docker compose restart app
```

### ریست کامل دیتابیس و آپلودها

```powershell
docker compose down -v
docker compose up -d --build
```

`-v` هر دو volume (`db_data` و `storage_data`) را پاک می‌کند؛ بعد از آن دوباره migrate + seed اجرا می‌شود. **آپلودهای واقعی هم پاک می‌شوند.**

### تغییر پورت

پورت پیش‌فرض وب `8080` است. یا در PowerShell متغیر را ست کنید:

```powershell
$env:APP_PORT=8090; docker compose up -d
```

یا مقدار را در `docker-compose.yml` عوض کنید. در این حالت `APP_URL` را هم در `.env.docker` با همان پورت هم‌راستا کنید.

---

## ۵) حالت توسعه (live reload کد)

برای اینکه تغییرات `app/`, `config/`, `routes/`, `resources/`, `database/` بدون rebuild دیده شوند:

```powershell
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```

در این حالت `APP_DEBUG=true` و `APP_ENV=local` است. برای برگشت به حالت عادی، فقط فایل اول را بالا بیاورید (بعد از `docker compose down`).

> فایل‌های `public/assets` داخل ایمیج کپی می‌شوند؛ اگر CSS/JS عمومی را تغییر دادید، `docker compose build web` یا کل build را دوباره اجرا کنید.

---

## ۶) دیتابیس و بکاپ

مشخصات پیش‌فرض (قابل تغییر در `docker-compose.yml` و `.env.docker`):

| مورد | مقدار |
|---|---|
| دیتابیس | `megashop` |
| کاربر | `megashop` |
| رمز کاربر | `megashop_secret` |
| رمز root | `root_secret` |

بکاپ:

```powershell
docker compose exec db sh -c "mysqldump -umegashop -pmegashop_secret megashop > /tmp/megashop.sql"
docker compose cp db:/tmp/megashop.sql .\megashop-backup.sql
```

بازگردانی:

```powershell
docker compose cp .\megashop-backup.sql db:/tmp/megashop.sql
docker compose exec db sh -c "mysql -umegashop -pmegashop_secret megashop < /tmp/megashop.sql"
```

> این رمزها فقط برای توسعهٔ محلی مناسب‌اند؛ برای سرور واقعی حتماً عوضشان کنید.

اگر می‌خواهید داده‌های فعلی لاراگون را به‌جای دادهٔ دمو داشته باشید، قبل از اجرا از دیتابیس `megashop` در لاراگون بکاپ بگیرید و بعد از بالا آمدن کانتینرها داخل `db` ایمپورت کنید:

```powershell
docker compose cp .\megashop-backup.sql db:/tmp/megashop.sql
docker compose exec db sh -c "mysql -umegashop -pmegashop_secret megashop < /tmp/megashop.sql"
```

(در این حالت `SEED_IF_EMPTY` را `false` بگذارید تا دادهٔ دمو اضافه نشود.)

---

## ۷) اجرای دستی مراحل دیتابیس

entrypoint به‌صورت خودکار این‌ها را انجام می‌دهد:

1. صبر تا آماده‌شدن MySQL
2. `php artisan migrate --force`
3. اگر جدول `users` خالی بود: `php artisan db:seed --force`
4. ساخت پوشه‌های storage و تنظیم مالکیت

اگر خواستید کنترل دستی داشته باشید، در `docker-compose.yml` مقدارهای زیر را ست کنید:

```yaml
environment:
  RUN_MIGRATIONS: "false"
  SEED_IF_EMPTY: "false"
```

و بعد خودتان اجرا کنید:

```powershell
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
```

---

## ۸) نکات production

- در `.env.docker`: `APP_ENV=production`، `APP_DEBUG=false`، `APP_URL` با دامنه و https.
- رمزهای MySQL را عوض کنید.
- بعد از هر تغییر env: `docker compose up -d` (کانتینر با env جدید بازسازی می‌شود) و سپس:
  ```powershell
  docker compose exec app php artisan optimize
  ```
- برای HTTPS، یک reverse proxy (Caddy/Traefik/Nginx) جلوی سرویس `web` بگذارید.
- اگر روی لینوکس با bind mount کار می‌کنید، مالکیت `storage` باید متعلق به UID 33 (`www-data`) باشد:
  ```bash
  sudo chown -R 33:33 storage bootstrap/cache
  ```
- برای ایمیج سبک‌تر (بدون dev dependencies):
  ```powershell
  docker compose build --build-arg INSTALL_DEV=false app
  ```

---

## ۹) عیب‌یابی

| مشکل | بررسی |
|---|---|
| صفحه ۵۰۰ | `docker compose logs -f app` و `docker compose exec app tail -n 100 storage/logs/laravel.log` |
| ۵۰۲ Bad Gateway | `docker compose ps` — سرویس `app` باید healthy باشد؛ لاگ PHP-FPM را ببینید |
| پورت اشغال است | `$env:APP_PORT=8090; docker compose up -d` یا سرویس اشغال‌کننده را ببندید (MySQL لاراگون روی 3306 است، به همین دلیل پورت دیتابیس روی 3307 map شده) |
| دیتابیس بالا نمی‌آید | `docker compose logs db` — اگر volume قبلی با رمز دیگری ساخته شده، `docker compose down -v` |
| تصاویر آپلودشده ۴۰۴ | `docker compose exec app ls -la public/storage/` و `docker compose logs web` |
| تغییر کد دیده نمی‌شود | حالت dev را با `docker-compose.dev.yml` بالا بیاورید یا `docker compose build app` |

---

## ۱۰) چیزهایی که عمداً داخل compose نیستند

- **Redis**: کد فعلی از `file` برای cache/session و `sync` برای queue استفاده می‌کند؛ نیازی به Redis نیست.
- **Queue worker**: هیچ Job ای در پروژه پیدا نشد (`ShouldQueue` صفر مورد) و `QUEUE_CONNECTION=sync` است. اگر بعداً صف واقعی اضافه کردید، یک سرویس با همان ایمیج و دستور زیر اضافه کنید:
  ```yaml
  worker:
    build: { context: ., target: app }
    command: ["php", "artisan", "queue:work", "--sleep=3", "--tries=3"]
    env_file: [.env.docker]
    environment:
      RUN_MIGRATIONS: "false"
      SEED_IF_EMPTY: "false"
    volumes:
      - storage_data:/var/www/html/storage
    depends_on:
      db: { condition: service_healthy }
  ```
- **Scheduler**: در `routes/console.php` هیچ task زمان‌بندی‌شده‌ای تعریف نشده؛ در صورت نیاز `php artisan schedule:work` را به‌عنوان یک سرویس اضافه کنید.
