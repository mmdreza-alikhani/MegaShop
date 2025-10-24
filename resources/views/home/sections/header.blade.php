@php use App\Models\Category; @endphp
<header class="nk-header nk-header-opaque">

    <!-- START: Top Contacts -->
    <div class="nk-contacts-top">
        <div class="container">
            <div class="text-center">
                @auth()
                    @if(session()->has('welcome'))
                        <h4>
                            <span>
                                {{ session('welcome') }}
                            </span>
                        </h4>
                    @endif
                @endauth
                <ul class="nk-contacts-icons">

                    <li>
                        <a href="#" data-toggle="modal" data-target="#modalSearch" class="search-btn">
                            <span class="fa fa-lg fa-search"></span>
                        </a>
                    </li>

                    @auth()
                        <li>
                            <a href="{{ route('home.profile.logout') }}" class="search-btn">
                                <span class="fa fa-lg fa-sign-out"></span>
                            </a>
                        </li>
                    @endauth

                    {{--                        <li>--}}
                    {{--                            <span class="nk-cart-toggle">--}}
                    {{--                                <span class="fa fa-lg fa-shopping-cart"></span>--}}
                    {{--                                @if(!Cart::isEmpty())--}}
                    {{--                                    @if(Cart::getContent()->count() > 9)--}}
                    {{--                                        <span class="nk-badge">+9</span>--}}
                    {{--                                    @endif--}}
                    {{--                                        <span class="nk-badge">{{Cart::getContent()->count()}}</span>--}}
                    {{--                                @endif--}}
                    {{--                            </span>--}}
                    {{--                            <div class="nk-cart-dropdown">--}}

                    {{--                                @if(Cart::isEmpty())--}}
                    {{--                                   <div class="alert text-center">--}}
                    {{--                                       سبد خرید شما خالی است!--}}
                    {{--                                   </div>--}}
                    {{--                                @else--}}
                    {{--                                    @foreach(Cart::getContent() as $product)--}}
                    {{--                                        <div class="nk-widget-post">--}}
                    {{--                                            <a href="{{ route('home.products.show', ['product' => $product->associatedModel->slug]) }}" class="nk-post-image">--}}
                    {{--                                                <img src="{{ env('PRODUCT_PRIMARY_IMAGE_UPLOAD_PATH') . '/' . $product->associatedModel->primary_image }}" alt="">--}}
                    {{--                                            </a>--}}
                    {{--                                            <h3 class="nk-post-title text-right" style="direction: rtl">--}}
                    {{--                                                <a href="{{ route('home.cart.remove', ['rowId' => $product->id]) }}" class="nk-cart-remove-item"><span class="ion-android-close"></span></a>--}}
                    {{--                                                <a href="{{ route('home.products.show', ['product' => $product->associatedModel->slug]) }}">{{ $product->name }}</a>--}}
                    {{--                                                <br>--}}
                    {{--                                                <small>{{ \App\Models\Attribute::findOrFail($product->attributes->attribute_id)->name }}: {{ $product->attributes->value }}</small>--}}
                    {{--                                                @if($product->attributes->is_sale)--}}
                    {{--                                                    <br>--}}
                    {{--                                                    <small style="color: #dd163b">درصد تخفیف: {{ round((($product->attributes->price - $product->attributes->sale_price) / $product->attributes->price) * 100) . '%' }}</small>--}}
                    {{--                                                @endif--}}
                    {{--                                            </h3>--}}
                    {{--                                            <div class="nk-gap-1"></div>--}}
                    {{--                                            <div class="nk-product-price"> {{ $product->quantity == 1 ? '' : $product->quantity . ' ' . '*' }} {{ number_format($product->price) }}</div>--}}
                    {{--                                        </div>--}}
                    {{--                                    @endforeach--}}
                    {{--                                    <div class="nk-gap-2"></div>--}}
                    {{--                                @endif--}}

                    {{--                                @if(!Cart::isEmpty())--}}
                    {{--                                    <div class="text-center">--}}
                    {{--                                        <a href="{{ route('home.cart.index') }}" class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">ادامه خرید</a>--}}
                    {{--                                    </div>--}}
                    {{--                                @endif--}}
                    {{--                            </div>--}}
                    {{--                        </li>--}}

                    @auth()
                        <li>
                            <a href="{{ route('home.profile.info') }}">
                                <span class="fa fa-lg fa-dashboard"></span>
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="#" data-toggle="modal" data-target="#modalLogin">
                                <span class="fa fa-lg fa-user"></span>
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </div>
    <!-- END: Top Contacts -->

    <!-- START: Navbar -->
    <nav class="nk-navbar nk-navbar-top nk-navbar-sticky nk-navbar-autohide">
        <div class="container-fluid">
            <div class="nk-nav-table">

                <a href="{{ route('home.index') }}" class="nk-nav-logo">
                    <img src="{{ env('HORIZONTAL_LOGO_PATH') }}" alt="MegaShop" width="220">
                </a>

                <ul class="nk-nav nk-nav-right d-none d-lg-table-cell" data-nav-mobile="#nk-nav-mobile">

                    <li>
                        <a href="{{ route('home.aboutus') }}">
                            درباره من
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('home.contactForm') }}">
                            تماس با من
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->getAllPermissions()->isNotEmpty())
                            <li>
                                <a href="{{ route('admin.panel') }}">
                                    ادمین پنل
                                </a>
                            </li>
                        @endif
                    @endauth

                    <li>
                        <a href="{{ route('home.posts.index') }}">
                            مقالات
                        </a>
                    </li>

                    <li class="nk-drop-item">
                        <a href="#">
                            محصولات
                        </a>
                        <ul class="dropdown">
                            @php
                                $parent_categories = Category::where('parent_id', '=', 0)->get();
                            @endphp
                            @foreach($parent_categories as $parent_category)
                                <li class="nk-drop-item">
                                    <a class="text-right">
                                        {{ $parent_category->title }}
                                    </a>
                                    <ul class="dropdown">
                                        @foreach($parent_category->children as $children)
                                            <li>
                                                <a href="{{ route('home.categories.show', ['category' => $children->slug]) }}"
                                                   class="text-right">
                                                    {{ $children->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>

                <ul class="nk-nav nk-nav-right nk-nav-icons">

                    <li class="single-icon d-lg-none">
                        <a href="#" class="no-link-effect" data-nav-toggle="#nk-nav-mobile">
                            <span class="nk-icon-burger">
                                <span class="nk-t-1"></span>
                                <span class="nk-t-2"></span>
                                <span class="nk-t-3"></span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    </nav>
    <!-- END: Navbar -->

</header>

<!--START: Navbar Mobile-->
<div id="nk-nav-mobile" class="nk-navbar nk-navbar-side nk-navbar-right-side nk-navbar-overlay-content d-lg-none">
    <div class="nano">
        <div class="nano-content">
            <a href="index.html" class="nk-nav-logo">
                <img src="/assets/images/logo.png" alt="" width="120">
            </a>
            <div class="nk-navbar-mobile-content">
                <ul class="nk-nav text-right">
                    <!-- Here will be inserted menu from [data-mobile-menu="#nk-nav-mobile"] -->
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END: Navbar Mobile -->
