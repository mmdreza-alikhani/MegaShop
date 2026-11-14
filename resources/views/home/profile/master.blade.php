@extends('home.layout.master')

@section('title', 'حساب کاربری')

@section('content')
    @php
        $user = $user ?? auth()->user();
        $fullName = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
        $isAdmin = auth()->user()->getAllPermissions()->isNotEmpty();
        $avatarUrl = Storage::url(config('upload.user_avatar_path') . '/' . ($user->avatar ?: 'avatar.png'));

        $navItems = [
            ['route' => 'home.profile.info', 'active' => request()->routeIs('home.profile.info'), 'icon' => 'fa-user-o', 'label' => 'مشخصات کاربری'],
            ['route' => 'home.profile.orders.index', 'active' => request()->routeIs('home.profile.orders.*'), 'icon' => 'fa-shopping-cart', 'label' => 'سفارش‌ها'],
            ['route' => 'home.profile.wishlist', 'active' => request()->routeIs('home.profile.wishlist'), 'icon' => 'fa-heart-o', 'label' => 'علاقه‌مندی‌ها'],
            ['route' => 'home.profile.comments', 'active' => request()->routeIs('home.profile.comments'), 'icon' => 'fa-comment-o', 'label' => 'نظرات من'],
            ['route' => 'home.profile.addresses.index', 'active' => request()->routeIs('home.profile.addresses.*'), 'icon' => 'fa-map-marker', 'label' => 'آدرس‌ها'],
            ['route' => 'home.profile.resetPassword', 'active' => request()->routeIs('home.profile.resetPassword'), 'icon' => 'fa-lock', 'label' => 'تغییر رمز عبور'],
        ];
    @endphp

    <div class="nk-gap-2"></div>

    <div class="container">
        <nav class="mk-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home.index') }}">خانه</a>
            <span class="fa fa-angle-left"></span>
            <a href="{{ route('home.profile.info') }}">حساب کاربری</a>
            <span class="fa fa-angle-left"></span>
            <span>{{ $user->username }}</span>
        </nav>

        <div class="mk-profile">
            <aside class="mk-profile-side">
                <div class="mk-profile-card">
                    <div class="mk-profile-avatar">
                        <img src="{{ $avatarUrl }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                             alt="{{ $user->username }}">
                        @if($isAdmin)
                            <span class="mk-profile-admin">مدیر</span>
                        @endif
                    </div>
                    <div>
                        <strong class="mk-profile-name">{{ $fullName !== '' ? $fullName : $user->username }}</strong>
                        <span class="mk-profile-username">{{ '@'.$user->username }}</span>
                        <span class="mk-profile-email" title="{{ $user->email }}">{{ $user->email }}</span>
                    </div>
                </div>

                <nav class="mk-profile-nav" aria-label="بخش‌های حساب کاربری">
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="{{ $item['active'] ? 'is-active' : '' }}">
                            <span class="fa {{ $item['icon'] }}"></span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach

                    @if($isAdmin)
                        <a href="{{ route('admin.panel') }}">
                            <span class="fa fa-dashboard"></span>
                            <span>پنل مدیریت</span>
                        </a>
                    @endif

                    @if($user->email_verified_at === null)
                        <a href="{{ route('home.profile.verifyEmail') }}"
                           class="{{ request()->routeIs('home.profile.verifyEmail') ? 'is-active' : '' }}">
                            <span class="fa fa-envelope-o"></span>
                            <span>تایید ایمیل</span>
                        </a>
                    @endif

                    <form action="{{ route('home.profile.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="mk-profile-logout">
                            <span class="fa fa-sign-out"></span>
                            <span>خروج از حساب</span>
                        </button>
                    </form>
                </nav>
            </aside>

            <section class="mk-profile-main">
                @yield('section')
            </section>
        </div>
    </div>

    <div class="nk-gap-2"></div>
@endsection
