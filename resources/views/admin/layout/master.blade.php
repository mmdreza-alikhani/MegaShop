<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('admin.sections.links')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    @include('admin.sections.header')

    @include('admin.sections.sidebar')

    <div class="content-wrapper">
        @include('admin.sections.content_header')

        @yield('content')
    </div>

    @include('admin.sections.footer')

</div>

@include('admin.sections.scripts')
@yield('scripts')
</body>
</html>
