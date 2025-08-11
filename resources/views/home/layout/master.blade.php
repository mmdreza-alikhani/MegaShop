<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MegaShop | @yield('title') </title>
    <meta name="description" content="">
    <meta name="author" content="_nK">
    <link rel="icon" type="image/png" href="{{ env('FAV_ICON') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEO::generate(true) !!}
    @include('home.sections.links')
</head>
<body>

@include('home.sections.header')

@yield('content')

@include('home.sections.footer')

<!-- START: Page Background -->

<img class="nk-page-background-top" src="/assets/images/bg-top.png" alt="">

<!-- END: Page Background -->

@include('home.sections.modals.search')

@include('home.sections.modals.user')

@include('home.sections.scripts')

@yield('scripts')

{!!  GoogleReCaptchaV3::init() !!}

</body>
</html>
