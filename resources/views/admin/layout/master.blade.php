<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="nozha admin panel fully support rtl with complete dark mode css to use. ">
    <meta name=”robots” content="index, follow">
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
</body>
</html>
