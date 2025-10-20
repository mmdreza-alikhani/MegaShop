@php
    use App\Models\Comment;
    $pendingComments = Comment::where('status', '1')->count();
@endphp
<style>
    .custom-dropdown {
        width: 400px; /* اندازه پیش‌فرض برای دسکتاپ */
        font-size: 16px; /* اندازه متن برای دسکتاپ */
    }

    @media (max-width: 768px) {
        /* برای حالت گوشی */
        .custom-dropdown {
            width: 50%; /* عرض کوچکتر برای موبایل */
            font-size: 14px; /* اندازه متن برای موبایل */
            margin: 0 auto; /* محتوای نوتیفیکیشن را وسط‌چین می‌کند */
        }
    }
</style>
<header class="bmd-layout-header ">
    <div class="navbar navbar-light bg-faded animate__animated animate__fadeInDown">
        @can('comments-toggle')
            <button class="navbar-toggler animate__animated animate__wobble animate__delay-2s" type="button"
                    data-toggle="drawer" data-target="#dw-s1">
                <span class="navbar-toggler-icon"></span>
            </button>
        @endcan
        <ul class="nav navbar-nav p-0">
            <li class="nav-item">
                <div class="dropdown">
                    <button class="btn  dropdown-toggle m-0" type="button" id="dropdownMenu3" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-bell fa-lg"></i>
                        <span class="badge badge-pill badge-warning animate__animated animate__flash animate__repeat-3 animate__slower animate__delay-2s">
                            {{ $pendingComments }}
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right custom-dropdown">
                        @if($pendingComments > 0)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" style="max-width: 100%" href="{{ route('admin.comments.index') }}">
                            <i class="far fa-envelope c-main mr-2"></i> {{ $pendingComments }} نظر منتظر تایید شما
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <img src="{{ Storage::url(config('upload.user_avatar_path') . '/') . auth()->user()->avatar }}" alt="your avatar" class="rounded-circle screen-user-profile">
            </li>
            <li class="nav-item">
                <div class="dropdown">
                    <button class="btn  dropdown-toggle m-0 d-none d-md-block" type="button" id="dropdownMenu4"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->username . ', خوش اومدی!' }}
                    </button>
                    <div class="dropdown-menu  pl-3 dropdown-menu-right dropdown-menu dropdown-menu-right">
                        <button onclick="dark()" class="dropdown-item" type="button">
                            <i class="fas fa-moon fa-sm c-main mr-2"></i>
                            حالت شب
                        </button>
                        <a href="{{ route('home.index') }}" class="dropdown-item" type="button">
                            <i class="fas fa-undo fa-sm c-main mr-2"></i>
                            بازگشت به سایت
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
