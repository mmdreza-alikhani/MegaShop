<!doctype html>
<html class="no-js" lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="پنل مدیریت فروشگاه">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('admin.sections.links')
    @yield('styles')
</head>

<body class="rtl persianumber">
<script>
    (function () {
        try {
            var saved = localStorage.getItem('adm-theme') || localStorage.getItem('theme') || 'light';
            if (saved === 'dark') {
                document.body.classList.add('dark');
            }
        } catch (e) {
            /* localStorage در دسترس نیست */
        }
    })();
</script>
@include('admin.sections.preloading')
<div class="bmd-layout-container bmd-drawer-f-l avam-container animated bmd-drawer-in">
    @include('admin.sections.header')
    @include('admin.sections.sidebar')
    @yield('content')
</div>

@include('admin.sections.scripts')
@yield('scripts')


</body>
</html>
