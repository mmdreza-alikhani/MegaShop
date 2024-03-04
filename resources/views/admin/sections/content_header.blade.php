<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-5">
                <h1 class="m-0 text-dark">@yield('title')</h1>
            </div>
            <div class="col-lg-2">
                @yield('button')
            </div>
            <div class="col-lg-5">
                <ol class="breadcrumb float-sm-left row">
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel') }}">خانه</a></li>
                    <li class="breadcrumb-item active text-left">@yield('title')</li>
                </ol>
            </div>
        </div>
    </div>
</div>
