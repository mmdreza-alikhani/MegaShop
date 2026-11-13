<?php

return [
    /*
     * هویت برند فروشگاه. همه‌جا از این مقادیر استفاده می‌شود تا با
     * config:cache هم درست کار کند (env در زمان اجرا خالی می‌شود).
     */
    'name' => env('BRAND_NAME', 'مگاشاپ'),

    'slogan' => env('BRAND_SLOGAN', 'فروشگاه تخصصی کالای دیجیتال'),

    'logo' => env('BRAND_LOGO', '/assets/images/logo.png'),

    // نسخه تیره‌ی لوگو برای حالت روشن
    'logo_light' => env('BRAND_LOGO_LIGHT', '/assets/images/logo-light.png'),

    'favicon' => env('BRAND_FAVICON', '/assets/images/favicon.png'),

    'owner_image_path' => env('BRAND_OWNER_IMAGE_PATH', 'images/owner'),

    'phone' => env('BRAND_PHONE', '021-00000000'),

    'email' => env('BRAND_EMAIL', 'info@megashop.test'),

    'address' => env('BRAND_ADDRESS', 'تهران'),

    'social' => [
        'instagram' => env('BRAND_INSTAGRAM', ''),
        'telegram' => env('BRAND_TELEGRAM', ''),
        'linkedin' => env('BRAND_LINKEDIN', ''),
        'github' => env('BRAND_GITHUB', ''),
    ],
];
