<?php

return [
    /*
     * تنظیمات حالت نمایشی (دمو)
     *
     * برای دیپلوی دموی مگاشاپ؛ سرویس‌هایی که به کلیدهای خارجی نیاز دارند
     * (Google reCAPTCHA و ورود با گوگل) به‌صورت پیش‌فرض غیرفعال می‌شوند.
     */

    // بررسی reCAPTCHA در فرم‌های عمومی (مثل فرم تماس)
    'recaptcha_enabled' => env('RECAPTCHA_ENABLED', false),

    // ورود با حساب گوگل (Socialite)
    'google_login_enabled' => env('SOCIALITE_GOOGLE_ENABLED', false),
];
