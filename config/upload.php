<?php

return [
    'product_primary_path' => env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH', 'products/images/primary_images'),
    'product_others_path' => env('PRODUCT_OTHER_IMAGES_UPLOAD_PATH', 'products/images/other_images'),

    'banner_path' => env('BANNER_IMAGE_UPLOAD_PATH', 'banners/images'),
    'platform_path' => env('PLATFORM_IMAGE_UPLOAD_PATH', 'platforms/images'),

    'user_avatar_path' => env('USER_AVATAR_UPLOAD_PATH', 'users/avatars'),

    'post_path' => env('POST_IMAGE_UPLOAD_PATH', 'posts/images'),

    'category_icon_path' => env('CATEGORY_IMAGE_PATH', 'categories/images'),

    'ckeditor_path' => env('CKEDITOR_UPLOAD_PATH', 'ckeditor/images'),
];
