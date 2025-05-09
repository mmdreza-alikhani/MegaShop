@extends('home.layouts.master')

@section('title')
    {{ $user->username }}
@endsection

@section('content')
    <div class="container">

        <div class="nk-gap-2"></div>
        <div class="row vertical-gap">
            <!-- START: Breadcrumbs -->
            <div class="nk-gap-1"></div>
            <div class="container my-3">
                <ul class="nk-breadcrumbs text-right" style="direction: rtl">
                    <li><a href="{{ route('home.index') }}">خانه</a></li>

                    <li><span class="fa fa-angle-left"></span></li>

                    <li><a href="#">پروفایل</a></li>

                    <li><span class="fa fa-angle-left"></span></li>

                    <li><span>{{ $user->username }}</span></li>
                </ul>
            </div>
            <div class="nk-gap-1"></div>
            <!-- END: Breadcrumbs -->

            <div class="col-lg-9">
                <div class="row vertical-gap">

                    @yield('section')

                </div>
                <div class="nk-gap-3"></div>
            </div>

            <div class="col-lg-3">
                <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
                    <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                        <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl;background-color: #293139">
                            <li><span style="font-size: 24px">تنظیمات:</span></li>
                        </ul>
                        <div class="nk-widget-content">
                            <ul class="nk-widget-categories text-right" style="direction: rtl">
                                <li><a class="info {{ $active == 'info' ? 'active-link' : '' }}" href="{{ route('home.profile.info') }}"> مشخصات کاربری </a></li>
                                <li><a class="purchasesHistory {{ $active == 'orders' ? 'active-link' : '' }}" href="{{ route('home.profile.orders') }}"> تاریخچه خرید ها </a></li>
                                <li><a class="wishlist {{ $active == 'wishlist' ? 'active-link' : '' }}" href="{{ route('home.profile.wishlist') }}"> علاقه مندیها </a></li>
                                <li><a class="comments {{ $active == 'comments' ? 'active-link' : '' }}" href="{{ route('home.profile.comments') }}"> وضعیت کامنت ها </a></li>
                                <li><a class="addresses {{ $active == 'addresses' ? 'active-link' : '' }}" href="{{ route('home.profile.addresses.index') }}"> آدرس ها </a></li>
                                <li><a class="resetPassword {{ $active == 'resetPassword' ? 'active-link' : '' }}" href="{{ route('home.profile.resetPassword') }}"> تغییر کلمه عبور </a></li>
                                @if($user->email_verified_at == null)
                                    <li><a class="verifyEmail {{ $active == 'verifyEmail' ? 'active-link' : '' }}" href="{{ route('home.profile.verifyEmail') }}"> تایید ایمیل </a></li>
                                @endif
                                <li><a class="logout" href="{{ route('home.profile.logout') }}"> ({{$user->username}}) نیستید؟ خروج از حساب کاربری  </a></li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <div class="nk-gap-2"></div>
@endsection
