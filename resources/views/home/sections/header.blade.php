@php
    use App\Models\Category;
    use App\Models\Product;
    use Illuminate\Support\Facades\Cache;

    // یک‌بار خواندن سبد برای کل هدر
    $headerCartItems = auth()->check() ? cartItems() : collect();
    $headerCartCount = $headerCartItems->count();

    $menuKey = 'header_menu_categories';

    $buildMenu = function (): array {
        $categories = Category::query()
            ->where('parent_id', 0)
            ->where('is_active', 1)
            ->with(['children' => fn ($query) => $query->where('is_active', 1)->orderBy('title')])
            ->orderBy('title')
            ->get();

        $counts = Product::query()
            ->where('is_active', 1)
            ->selectRaw('category_id, count(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $totals = $categories->mapWithKeys(fn ($root) => [
            $root->id => (int) ($counts[$root->id] ?? 0)
                + $root->children->sum(fn ($child) => (int) ($counts[$child->id] ?? 0)),
        ]);

        return [
            'categories' => $categories
                ->sortByDesc(fn ($root) => $totals[$root->id] ?? 0)
                ->values(),
            'totals' => $totals->all(),
            'counts' => $counts->all(),
        ];
    };

    try {
        $menu = Cache::remember($menuKey, now()->addHour(), $buildMenu);

        if (empty($menu['categories']) || $menu['categories']->isEmpty()) {
            Cache::forget($menuKey);
            $menu = $buildMenu();
        }
    } catch (\Throwable $e) {
        report($e);
        $menu = $buildMenu();
    }

    $headerCategories = $menu['categories'];
    $categoryTotals = $menu['totals'];
    $categoryCounts = $menu['counts'];

    $categoryIcon = function (string $title): string {
        $map = [
            'موبایل' => 'fa-mobile',
            'لپ‌تاپ' => 'fa-laptop',
            'لپ تاپ' => 'fa-laptop',
            'تبلت' => 'fa-tablet',
            'ساعت' => 'fa-clock-o',
            'مچ‌بند' => 'fa-heartbeat',
            'هدفون' => 'fa-headphones',
            'هندزفری' => 'fa-headphones',
            'اسپیکر' => 'fa-volume-up',
            'کنسول' => 'fa-gamepad',
            'بازی' => 'fa-gamepad',
            'مانیتور' => 'fa-desktop',
            'کیبورد' => 'fa-keyboard-o',
            'ماوس' => 'fa-mouse-pointer',
            'پاوربانک' => 'fa-battery-three-quarters',
            'شارژر' => 'fa-plug',
            'قاب' => 'fa-shield',
            'لوازم' => 'fa-cube',
        ];

        foreach ($map as $needle => $icon) {
            if (mb_strpos($title, $needle) !== false) {
                return $icon;
            }
        }

        return 'fa-th-large';
    };


    // آیتم فعال نوار پایین صفحه
    $navActive = match (true) {
        request()->routeIs('home.index') => 'home',
        request()->routeIs('home.products.*', 'home.categories.*') => 'products',
        request()->routeIs('home.search') => 'search',
        request()->routeIs('home.cart.*', 'home.payment.*') => 'cart',
        request()->routeIs('home.profile.*') => 'account',
        default => '',
    };
@endphp
<header class="mk-header">
    <div class="mk-topbar">
        <div class="container mk-topbar-inner">
            <div class="mk-topbar-info">
                <a href="tel:{{ config('brand.phone') }}">
                    <span class="fa fa-phone"></span>
                    {{ config('brand.phone') }}
                </a>
                <span class="mk-topbar-sep"></span>
                <span class="mk-topbar-slogan">{{ config('brand.slogan') }}</span>
            </div>

            <div class="mk-topbar-actions">
                <button type="button" class="mk-theme-toggle" data-theme-toggle
                        aria-label="تغییر حالت روشن و تاریک" title="حالت روشن / تاریک">
                    <span class="mk-theme-ico mk-theme-ico-moon"><span class="fa fa-moon-o"></span></span>
                    <span class="mk-theme-ico mk-theme-ico-sun"><span class="fa fa-sun-o"></span></span>
                </button>

                <button type="button" class="mk-icon-btn mk-topbar-only" data-toggle="modal" data-target="#modalSearch"
                        aria-label="جستجو">
                    <span class="fa fa-lg fa-search"></span>
                </button>

                @auth
                    <button type="button" class="mk-icon-btn mk-cart-toggle mk-topbar-only" aria-label="سبد خرید">
                        <span class="fa fa-lg fa-shopping-cart"></span>
                        @if($headerCartCount > 0)
                            <span class="mk-badge">{{ $headerCartCount > 9 ? '۹+' : $headerCartCount }}</span>
                        @endif
                    </button>

                    <a href="{{ route('home.profile.info') }}" class="mk-icon-btn mk-topbar-only" aria-label="حساب کاربری">
                        <span class="fa fa-lg fa-user"></span>
                    </a>

                    <form action="{{ route('home.profile.logout') }}" method="POST" class="mk-inline-form mk-topbar-only">
                        @csrf
                        <button type="submit" class="mk-icon-btn" aria-label="خروج" title="خروج از حساب">
                            <span class="fa fa-lg fa-sign-out"></span>
                        </button>
                    </form>
                @else
                    <button type="button" class="mk-icon-btn mk-topbar-only" data-toggle="modal" data-target="#modalLogin"
                            aria-label="ورود و ثبت‌نام">
                        <span class="fa fa-lg fa-user"></span>
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <div class="mk-navbar">
        <div class="container mk-navbar-inner">
            <a href="{{ route('home.index') }}" class="mk-logo">
                <img class="mk-logo-dark" src="{{ asset(ltrim(config('brand.logo'), '/')) }}"
                     alt="{{ config('brand.name') }}">
                <img class="mk-logo-light" src="{{ asset(ltrim(config('brand.logo_light'), '/')) }}"
                     alt="{{ config('brand.name') }}">
            </a>

            <nav class="mk-nav" aria-label="منوی اصلی">
                <a href="{{ route('home.index') }}">خانه</a>

                <div class="mk-nav-dropdown" data-mega-root>
                    <a href="{{ route('home.products.index') }}" class="mk-nav-trigger"
                       aria-haspopup="true" aria-expanded="false">
                        محصولات
                        <span class="fa fa-angle-down mk-nav-caret"></span>
                    </a>

                    <div class="mk-mega">
                        <div class="mk-mega-body">
                            <ul class="mk-mega-parents" role="tablist">
                                @foreach($headerCategories as $index => $parentCategory)
                                    <li>
                                        <button type="button"
                                                class="mk-mega-parent {{ $index === 0 ? 'is-active' : '' }}"
                                                data-mega-parent="cat-{{ $parentCategory->id }}"
                                                role="tab"
                                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                            <span class="mk-mega-ico">
                                                <span class="fa {{ $categoryIcon($parentCategory->title) }}"></span>
                                            </span>
                                            <span class="mk-mega-parent-title">{{ $parentCategory->title }}</span>
                                            <span class="mk-mega-count">{{ $categoryTotals[$parentCategory->id] ?? 0 }}</span>
                                            <span class="fa fa-angle-left mk-mega-arrow"></span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mk-mega-panels">
                                @foreach($headerCategories as $index => $parentCategory)
                                    <div class="mk-mega-panel {{ $index === 0 ? 'is-active' : '' }}"
                                         data-mega-panel="cat-{{ $parentCategory->id }}"
                                         role="tabpanel">
                                        <div class="mk-mega-panel-head">
                                            <div>
                                                <span class="mk-mega-eyebrow">دسته‌بندی</span>
                                                <h4>{{ $parentCategory->title }}</h4>
                                                <span class="mk-mega-total">
                                                    {{ $categoryTotals[$parentCategory->id] ?? 0 }} محصول
                                                </span>
                                            </div>
                                            <a href="{{ route('home.categories.show', ['category' => $parentCategory->slug]) }}"
                                               class="mk-mega-all">
                                                مشاهده همه
                                                <span class="fa fa-angle-left"></span>
                                            </a>
                                        </div>

                                        @if($parentCategory->children->isNotEmpty())
                                            <div class="mk-mega-links">
                                                @foreach($parentCategory->children as $child)
                                                    <a href="{{ route('home.categories.show', ['category' => $child->slug]) }}"
                                                       class="mk-mega-link">
                                                        <span>{{ $child->title }}</span>
                                                        <span class="mk-mega-link-count">{{ $categoryCounts[$child->id] ?? 0 }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="mk-mega-empty">
                                                محصولات این دسته را در صفحه دسته‌بندی ببینید.
                                            </div>
                                        @endif

                                        <a class="mk-mega-panel-cta"
                                           href="{{ route('home.categories.show', ['category' => $parentCategory->slug]) }}">
                                            مشاهده همه محصولات {{ $parentCategory->title }}
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mk-mega-foot">
                            <a href="{{ route('home.products.index') }}">
                                <span class="fa fa-th-large"></span>
                                همه محصولات
                            </a>
                            <a href="{{ route('home.products.index', ['discount' => 'true']) }}">
                                <span class="fa fa-tags"></span>
                                محصولات تخفیف‌دار
                            </a>
                            <a href="{{ route('home.products.index', ['sortBy' => 'latest']) }}">
                                <span class="fa fa-clock-o"></span>
                                جدیدترین‌ها
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('home.posts.index') }}">مقالات</a>
                <a href="{{ route('home.aboutus') }}">درباره ما</a>
                <a href="{{ route('home.contact') }}">تماس با ما</a>

                @auth
                    @if(auth()->user()->getAllPermissions()->isNotEmpty())
                        <a href="{{ route('admin.panel') }}" class="mk-nav-admin">پنل مدیریت</a>
                    @endif
                @endauth
            </nav>
        </div>
    </div>

</header>

@auth
    <div class="mk-cart-panel" id="mkCartPanel">
        <div class="mk-cart-panel-head">
            <span>سبد خرید ({{ $headerCartCount }})</span>
            <button type="button" class="mk-cart-close" aria-label="بستن">&times;</button>
        </div>

        @if($headerCartCount === 0)
            <div class="mk-cart-empty">سبد خرید شما خالی است!</div>
        @else
            <div class="mk-cart-list">
                @foreach($headerCartItems as $cartItem)
                    <div class="mk-cart-row">
                        <a href="{{ route('home.products.show', ['product' => $cartItem->itemable->slug]) }}"
                           class="mk-cart-thumb">
                            <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $cartItem->itemable->primary_image) }}"
                                 alt="{{ $cartItem->itemable->title }}">
                        </a>
                        <div class="mk-cart-info">
                            <a href="{{ route('home.products.show', ['product' => $cartItem->itemable->slug]) }}">
                                {{ Str::limit($cartItem->itemable->title, 42) }}
                            </a>
                            <small>
                                {{ $cartItem->variation->attribute?->title }}:
                                {{ $cartItem->variation->value }}
                            </small>
                            <div class="mk-cart-meta">
                                <span>{{ $cartItem->quantity }} × {{ number_format($cartItem->unit_price) }}</span>
                                <strong>{{ number_format($cartItem->total_price) }} تومان</strong>
                            </div>
                        </div>
                        <form action="{{ route('home.cart.remove', ['itemId' => $cartItem->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mk-cart-remove" aria-label="حذف">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mk-cart-panel-foot">
                <a href="{{ route('home.cart.index') }}" class="mk-btn mk-btn-outline">مشاهده سبد</a>
                <a href="{{ route('home.cart.checkout') }}" class="mk-btn mk-btn-primary">تسویه حساب</a>
            </div>
        @endif
    </div>

@endauth

<div class="mk-overlay" id="mkOverlay"></div>

<nav class="mk-bottom-nav" aria-label="دسترسی سریع" data-bottom-nav>
    <a href="{{ route('home.index') }}"
       class="mk-bottom-item {{ $navActive === 'home' ? 'is-active' : '' }}"
       data-bottom-item>
        <span class="mk-bottom-glow" aria-hidden="true"></span>
        <span class="mk-bottom-icon"><span class="fa fa-home"></span></span>
        <span class="mk-bottom-label">خانه</span>
    </a>

    <a href="{{ route('home.products.index') }}"
       class="mk-bottom-item {{ $navActive === 'products' ? 'is-active' : '' }}"
       data-bottom-item>
        <span class="mk-bottom-glow" aria-hidden="true"></span>
        <span class="mk-bottom-icon"><span class="fa fa-th-large"></span></span>
        <span class="mk-bottom-label">محصولات</span>
    </a>

    <button type="button"
            class="mk-bottom-item {{ $navActive === 'search' ? 'is-active' : '' }}"
            data-bottom-item data-toggle="modal" data-target="#modalSearch">
        <span class="mk-bottom-glow" aria-hidden="true"></span>
        <span class="mk-bottom-icon"><span class="fa fa-search"></span></span>
        <span class="mk-bottom-label">جستجو</span>
    </button>

    @auth
        <button type="button"
                class="mk-bottom-item mk-cart-toggle {{ $navActive === 'cart' ? 'is-active' : '' }}"
                data-bottom-item>
            <span class="mk-bottom-glow" aria-hidden="true"></span>
            <span class="mk-bottom-icon">
                <span class="fa fa-shopping-cart"></span>
                @if($headerCartCount > 0)
                    <span class="mk-bottom-badge">{{ $headerCartCount > 9 ? '۹+' : $headerCartCount }}</span>
                @endif
            </span>
            <span class="mk-bottom-label">سبد</span>
        </button>

        <a href="{{ route('home.profile.info') }}"
           class="mk-bottom-item {{ $navActive === 'account' ? 'is-active' : '' }}"
           data-bottom-item>
            <span class="mk-bottom-glow" aria-hidden="true"></span>
            <span class="mk-bottom-icon"><span class="fa fa-user"></span></span>
            <span class="mk-bottom-label">حساب من</span>
        </a>
    @else
        <button type="button" class="mk-bottom-item" data-bottom-item
                data-toggle="modal" data-target="#modalLogin">
            <span class="mk-bottom-glow" aria-hidden="true"></span>
            <span class="mk-bottom-icon"><span class="fa fa-shopping-cart"></span></span>
            <span class="mk-bottom-label">سبد</span>
        </button>

        <button type="button" class="mk-bottom-item" data-bottom-item
                data-toggle="modal" data-target="#modalLogin">
            <span class="mk-bottom-glow" aria-hidden="true"></span>
            <span class="mk-bottom-icon"><span class="fa fa-user"></span></span>
            <span class="mk-bottom-label">ورود</span>
        </button>
    @endauth
</nav>
