<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Coupon;
use App\Models\Platform;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Observers\ContentCacheObserver;
use App\Observers\UserObserver;
use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // یک نمونه در طول هر درخواست؛ هم کش سبد و هم محاسبات یک‌بار انجام می‌شوند.
        $this->app->scoped(CartService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        // هر تغییر در محتوای فروشگاه، کش صفحه اصلی/منو را باطل می‌کند.
        foreach ([Product::class, Post::class, Banner::class, Category::class, Brand::class, Platform::class, Coupon::class, Comment::class] as $model) {
            $model::observe(ContentCacheObserver::class);
        }

        if (request()->is('management/')) {
            Paginator::useBootstrap();
        } else {
            Paginator::defaultView('vendor.pagination.mk');
        }
    }
}
