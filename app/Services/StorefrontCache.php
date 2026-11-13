<?php

namespace App\Services;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * کش تکه‌های ثابت فروشگاه (صفحه اصلی و منوی دسته‌بندی).
 *
 * کلیدها با هر تغییر در محصول/مقاله/بنر/دسته پاک می‌شوند تا داده کهنه نمایش داده نشود.
 */
class StorefrontCache
{
    public const HOME = 'storefront.home.v1';

    public const MENU = 'storefront.menu.v1';

    public const TTL_HOME = 600;      // ۱۰ دقیقه

    public const TTL_MENU = 3600;     // ۱ ساعت

    public function rememberHome(Closure $callback): array
    {
        return Cache::remember(self::HOME, self::TTL_HOME, $callback);
    }

    public function rememberMenu(Closure $callback)
    {
        return Cache::remember(self::MENU, self::TTL_MENU, $callback);
    }

    public function forgetHome(): void
    {
        Cache::forget(self::HOME);
    }

    public function forgetMenu(): void
    {
        Cache::forget(self::MENU);

        // کلید قدیمی منو که در ویو هدر استفاده می‌شد.
        Cache::forget('header_menu_categories');
    }

    public function forgetAll(): void
    {
        $this->forgetHome();
        $this->forgetMenu();
    }
}
