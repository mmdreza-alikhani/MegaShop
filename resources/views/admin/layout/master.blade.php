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
@include('admin.sections.preloading')
<div class="bmd-layout-container bmd-drawer-f-l avam-container animated bmd-drawer-in">
    @include('admin.sections.header')
    @include('admin.sections.sidebar')
    @yield('content')
</div>

@include('admin.sections.scripts')
@yield('scripts')

{{-- موبایل: کشوی منو به صورت overlay ساده --}}
<script>
    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-toggle="drawer"]');

        if (toggle) {
            event.preventDefault();
            event.stopPropagation();
            document.body.classList.toggle('mk-admin-drawer-open');
            return;
        }

        if (document.body.classList.contains('mk-admin-drawer-open') && !event.target.closest('#dw-s1')) {
            document.body.classList.remove('mk-admin-drawer-open');
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 991) {
            document.body.classList.remove('mk-admin-drawer-open');
        }
    });
</script>
</body>
</html>
