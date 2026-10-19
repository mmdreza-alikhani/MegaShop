<?php

return [
    // درایور پرداخت: simulated (شبیه‌ساز داخلی برای نسخه دمو) یا zarinpal (درگاه واقعی)
    'driver' => env('PAYMENT_DRIVER', 'simulated'),

    'merchant_id' => env('ZARINPAL_MERCHANT_ID', 'cdcebd0e-8c72-4df8-a216-7d1b4aaa9a72'),

    // URLs
    'request_url' => env('ZARINPAL_SANDBOX', true)
        ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
        : 'https://payment.zarinpal.com/pg/v4/payment/request.json',

    'verify_url' => env('ZARINPAL_SANDBOX', true)
        ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
        : 'https://payment.zarinpal.com/pg/v4/payment/verify.json',

    'payment_url' => env('ZARINPAL_SANDBOX', true)
        ? 'https://sandbox.zarinpal.com/pg/StartPay/'
        : 'https://payment.zarinpal.com/pg/StartPay/',
];
