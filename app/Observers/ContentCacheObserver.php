<?php

namespace App\Observers;

use App\Services\StorefrontCache;

/**
 * هر تغییر در محتوای فروشگاه، کش صفحه اصلی و منو را باطل می‌کند.
 */
class ContentCacheObserver
{
    public function saved(): void
    {
        app(StorefrontCache::class)->forgetAll();
    }

    public function deleted(): void
    {
        app(StorefrontCache::class)->forgetAll();
    }
}
