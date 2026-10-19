# راهنمای دیپلوی نمایشی مگاشاپ (MegaShop Demo)

> این نسخه برای **دیپلوی دمو** آماده شده است:
> - درگاه پرداخت **شبیه‌سازی‌شده** است (`PAYMENT_DRIVER=simulated`) و هیچ تراکنش بانکی واقعی انجام نمی‌شود.
> - reCAPTCHA و ورود با حساب گوگل در حالت دمو غیرفعال‌اند (بدون نیاز به کلیدهای گوگل).

## پیش‌نیازهای سرور

- PHP >= 8.3 با افزونه‌ها: `curl`, `gd`, `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `zip`
- MySQL 8 (یا MariaDB) — یک دیتابیس خالی + کاربر
- Composer 2
- وب‌سرور Nginx یا Apache با DocumentRoot روی پوشه `public`

## متغیرهای محیطی کلیدی (.env)

| کلید | مقدار پیشنهادی دمو |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | آدرس نهایی سایت (با https) |
| `DB_*` | مشخصات دیتابیس سرور |
| `SESSION_DRIVER` | `file` |
| `CACHE_DRIVER` | `file` |
| `QUEUE_CONNECTION` | `sync` |
| `MAIL_MAILER` | `log` (ایمیل‌ها در لاگ ذخیره می‌شوند) |
| `PAYMENT_DRIVER` | `simulated` |
| `RECAPTCHA_ENABLED` | `false` |
| `SOCIALITE_GOOGLE_ENABLED` | `false` |
| `ZARINPAL_*` | فقط در صورت بازگشت به درگاه واقعی |

## مراحل دیپلوی (VPS / دسترسی SSH)

1. آپلود کد (git clone یا zip) — بدون پوشه‌های `vendor` و `node_modules`
2. `composer install --no-dev --optimize-autoloader`
3. `cp .env.example .env` → مقادیر جدول بالا را تنظیم کن → `php artisan key:generate`
4. دیتابیس را بساز، سپس:
   - `php artisan migrate --force --seed` → همه داده‌های نمونه (۳۳ محصول، کاربران، سفارش‌ها، بنرها و...) ساخته می‌شود
   - تصاویر نمونه به‌صورت خودکار در `storage/app/public` تولید می‌شوند (افزونه GD لازم است)
5. `php artisan storage:link`
6. بهینه‌سازی: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
7. مجوز پوشه‌ها: `chown -R www-data:www-data storage bootstrap/cache` (کاربر وب‌سرور)
8. در Nginx/Apache دامنه را به مسیر `<project>/public` وصل کن

## مراحل دیپلوی (cPanel / هاست اشتراکی)

- فایل zip را آپلود و در مسیر پروژه استخراج کن.
- دامنه/ساب‌دامین را روی پوشه `public` ست کن؛ اگر امکانش نیست، محتوای `public` را به `public_html` منتقل کن و مسیر `require` و autoload در `index.php` را دو پله به عقب اصلاح کن.
- از Terminal سی‌پنل یا SSH همان مراحل ۲ تا ۷ بالا را اجرا کن (بدون sudo).
- اگر `storage:link` روی هاست مجاز نبود، از پشتیبانی هاست فعال‌سازی symlink را بخواه یا لینک را دستی بساز.

## ورودهای نمونه برای ارائه

| نقش | نام کاربری | رمز عبور |
|---|---|---|
| مدیر | `admin` | `password` |
| مشتری | `sara.mohammadi` | `password` |
| مشتری | `amir.karimi` | `password` |

> قبل از عمومی‌کردن، رمز ادمین را از پروفایل تغییر بده.

## بازگشت به درگاه واقعی (اختیاری)

1. `PAYMENT_DRIVER=zarinpal` در `.env`
2. مقداردهی `ZARINPAL_MERCHANT_ID` و `ZARINPAL_SANDBOX=false`
3. `php artisan config:cache`

## نکات عملیاتی

- بعد از هر تغییر `.env` حتماً `php artisan config:cache` را دوباره اجرا کن.
- خطای ۵۰۰؟ اول `storage/logs/laravel.log` را ببین.
- برای ریست کامل داده‌ها: `php artisan migrate:fresh --seed --force` (دیتابیس را پاک می‌کند!)
- شبیه‌ساز پرداخت: مسیر `/payment/simulator/{order}` — دو گزینه «پرداخت موفق» و «انصراف».
