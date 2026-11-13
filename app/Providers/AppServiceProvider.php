<?php

namespace App\Providers;

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
        if (request()->is('management/')) {
            Paginator::useBootstrap();
        } else {
            Paginator::defaultView('vendor.pagination.bootstrap-5');
        }
    }
}
