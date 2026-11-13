<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'فروشگاه') | {{ config('brand.name') }}</title>
    <meta name="description" content="@yield('meta_description', config('brand.name').' — '.config('brand.slogan'))">
    <meta name="author" content="{{ config('brand.name') }}">
    <link rel="icon" type="image/png" href="{{ asset(ltrim(config('brand.favicon'), '/')) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1a1c1d">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('brand.name') }}">
    <meta property="og:title" content="@yield('title', config('brand.name'))">
    <meta property="og:description" content="@yield('meta_description', config('brand.name').' — '.config('brand.slogan'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="canonical" href="{{ url()->current() }}">

    @include('home.sections.links')
    @stack('head')
</head>
<body>

@include('home.sections.header')

<main class="mk-main">
    @yield('content')
</main>

@include('home.sections.footer')

<!-- START: Page Background -->
<img class="nk-page-background-top" src="{{ asset('assets/images/bg-top.png') }}" alt="">
<!-- END: Page Background -->

@include('home.sections.modals.search')

@include('home.sections.modals.user')

@include('home.sections.scripts')

@yield('scripts')
@stack('scripts')

@if(config('demo.recaptcha_enabled'))
    {!! GoogleReCaptchaV3::init() !!}
@endif


</body>
</html>
