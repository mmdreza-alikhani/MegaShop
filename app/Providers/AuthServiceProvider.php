<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\UserAddress;
use App\Policies\Home\Profile\Address\UserAddressPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        UserAddress::class => UserAddressPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
