@php
    use App\Models\Comment;
    use App\Models\Order;

    $pendingComments = Comment::where('status', '1')->count();
    $pendingOrders = Order::where('status', 0)->count();
@endphp

<header class="bmd-layout-header">
    <div class="navbar navbar-light bg-faded mk-adm-header">
        <div class="mk-adm-header-start">
            <button class="navbar-toggler" type="button" data-toggle="drawer" data-target="#dw-s1"
                    aria-label="باز و بسته کردن منو">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="mk-adm-page-title">
                <strong>@yield('title', 'داشبورد')</strong>
                <span>پنل مدیریت مگاشاپ</span>
            </div>
        </div>

        <form class="mk-adm-search" action="{{ route('admin.products.index') }}" method="GET" role="search">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجوی محصول…"
                   aria-label="جستجوی محصول">
            <button type="submit" class="mk-adm-search-btn">جستجو</button>
        </form>

        <div class="mk-adm-header-actions">
            <a class="mk-adm-quick-btn" href="{{ route('admin.products.create') }}">
                <i class="fas fa-plus"></i>
                <span>محصول جدید</span>
            </a>

            <a class="mk-adm-quick-btn" href="{{ route('admin.orders.index') }}">
                <i class="fas fa-receipt"></i>
                <span>سفارش‌ها</span>
                @if($pendingOrders > 0)
                    <em class="mk-adm-count">{{ $pendingOrders }}</em>
                @endif
            </a>

            <button type="button" class="mk-adm-icon-btn" data-adm-theme-toggle
                    aria-label="تغییر حالت روشن و تاریک" title="حالت روشن / تاریک" aria-pressed="false">
                <i class="fas fa-moon mk-adm-theme-dark" aria-hidden="true"></i>
                <i class="fas fa-sun mk-adm-theme-light" aria-hidden="true"></i>
            </button>

            <div class="dropdown">
                <button class="mk-adm-icon-btn" type="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" title="اعلان‌ها">
                    <i class="far fa-bell"></i>
                    @if($pendingComments > 0)
                        <em class="mk-adm-count is-alert">{{ $pendingComments }}</em>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-right mk-adm-dropdown">
                    <div class="mk-adm-dropdown-head">اعلان‌ها</div>

                    <a class="dropdown-item" href="{{ route('admin.comments.index') }}">
                        <i class="far fa-comment-alt"></i>
                        <span>{{ $pendingComments }} نظر در انتظار تایید</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ $pendingOrders }} سفارش در انتظار پرداخت</span>
                    </a>
                </div>
            </div>

            <div class="dropdown mk-adm-user">
                <button class="mk-adm-user-btn" type="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <img src="{{ Storage::url(config('upload.user_avatar_path') . '/' . auth()->user()->avatar) }}"
                         onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                         alt="{{ auth()->user()->username }}">
                    <span class="mk-adm-user-name">{{ auth()->user()->username }}</span>
                    <i class="fas fa-angle-down" aria-hidden="true"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right mk-adm-dropdown">
                    <div class="mk-adm-dropdown-head">{{ auth()->user()->first_name ?: auth()->user()->username }}</div>

                    <a class="dropdown-item" href="{{ route('home.profile.info') }}">
                        <i class="far fa-user"></i>
                        <span>پروفایل من</span>
                    </a>

                    <button type="button" class="dropdown-item" data-adm-theme-toggle>
                        <i class="fas fa-moon"></i>
                        <span>حالت روشن / تاریک</span>
                    </button>

                    <a class="dropdown-item" href="{{ route('home.index') }}" target="_blank" rel="noopener">
                        <i class="fas fa-store"></i>
                        <span>مشاهده فروشگاه</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <form action="{{ route('home.profile.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item is-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>خروج از حساب</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
