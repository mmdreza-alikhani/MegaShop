@extends('home.layout.master')

@section('title', 'سبد خرید')

@section('content')
    <div class="container mk-cart-page">
        <nav class="mk-crumbs" aria-label="مسیر صفحه">
            <a href="{{ route('home.index') }}">خانه</a>
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <span>سبد خرید</span>
        </nav>

        @include('home.sections.demo-notice')

        <div class="mk-cart-head">
            <div>
                <h1>سبد خرید</h1>
                <p>{{ isCartEmpty() ? 'سبد شما خالی است' : number_format(cartItems()->count()) . ' قلم کالا در سبد شما' }}</p>
            </div>
            <a href="{{ route('home.products.index') }}" class="mk-btn mk-btn-outline">
                <span class="fa fa-shopping-bag"></span> ادامه خرید
            </a>
        </div>

        @if(isCartEmpty())
            <div class="mk-empty-state">
                <span class="mk-empty-icon"><span class="fa fa-shopping-cart"></span></span>
                <h2>سبد خرید شما خالی است</h2>
                <p>هنوز محصولی به سبد اضافه نکرده‌اید. از میان محصولات، انتخاب کنید و به سبد اضافه کنید.</p>
                <a href="{{ route('home.products.index') }}" class="mk-btn mk-btn-primary">
                    <span class="fa fa-shopping-bag"></span> مشاهده محصولات
                </a>
            </div>
        @else
            @if(cartService()->hasUnavailableItems())
                <div class="mk-alert mk-alert-warning">
                    <span class="fa fa-exclamation-triangle"></span>
                    برخی محصولات سبد موجود نیستند؛ لطفاً تعداد آن‌ها را کاهش دهید یا حذف کنید.
                </div>
            @endif

            <div class="mk-cart-layout">
                <div class="mk-cart-main">
                    <form id="cartUpdateForm" action="{{ route('home.cart.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('home.sections.errors', ['errors' => $errors->updateCart])

                        <div class="mk-cart-list">
                            @foreach(cartItems() as $item)
                                @php($product = $item->itemable)
                                <article class="mk-cart-item">
                                    <a href="{{ route('home.products.show', ['product' => $product->slug]) }}"
                                       class="mkc-thumb">
                                        <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $product->primary_image) }}"
                                             alt="{{ $product->title }}" loading="lazy">
                                    </a>

                                    <div class="mkc-info">
                                        <a href="{{ route('home.products.show', ['product' => $product->slug]) }}"
                                           class="mkc-title">{{ $product->title }}</a>

                                        <span class="mk-cart-variant">
                                            {{ $item->variation->attribute?->title }}:
                                            {{ $item->variation->value }}
                                        </span>

                                        <span class="mk-cart-unit">{{ number_format($item->unit_price) }} تومان</span>

                                        @if(! $item->is_available)
                                            <span class="mk-cart-warn">این تنوع ناموجود است</span>
                                        @elseif($item->is_quantity_adjusted)
                                            <span class="mk-cart-warn">تعداد تا موجودی انبار ({{ $item->available_quantity }}) کاهش یافت</span>
                                        @endif
                                    </div>

                                    <div class="mk-cart-qty">
                                        <label for="qty-{{ $item->id }}">تعداد</label>
                                        <input id="qty-{{ $item->id }}" type="number" min="1"
                                               max="{{ max(1, $item->available_quantity) }}"
                                               value="{{ $item->quantity }}"
                                               name="products[{{ $product->id }}][{{ $item->variation->id }}]">
                                    </div>

                                    <div class="mk-cart-line-total">
                                        <strong>{{ number_format($item->total_price) }}</strong>
                                        <small>تومان</small>
                                    </div>

                                    <button type="submit" form="cartRemove{{ $item->id }}" class="mkc-remove"
                                            title="حذف از سبد" aria-label="حذف {{ $product->title }}">
                                        <span class="fa fa-trash-o"></span>
                                    </button>
                                </article>
                            @endforeach
                        </div>

                        <div class="mk-cart-toolbar">
                            <button type="submit" class="mk-btn mk-btn-outline">
                                <span class="fa fa-refresh"></span> ثبت تغییرات
                            </button>
                            <button type="submit" form="cartClearForm" class="mk-btn mk-btn-ghost is-danger"
                                    onclick="return confirm('سبد خرید خالی شود؟')">
                                <span class="fa fa-trash"></span> خالی کردن سبد
                            </button>
                        </div>
                    </form>

                    @foreach(cartItems() as $item)
                        <form id="cartRemove{{ $item->id }}" method="POST" class="mk-hidden-form"
                              action="{{ route('home.cart.remove', ['itemId' => $item->id]) }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach

                    <form id="cartClearForm" method="POST" class="mk-hidden-form" action="{{ route('home.cart.clear') }}">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

                <aside class="mk-cart-summary">
                    <h2>خلاصه سفارش</h2>

                    <div class="mk-sum-row">
                        <span>جمع کالاها</span>
                        <strong>{{ number_format(cartTotalAmount()) }} تومان</strong>
                    </div>
                    <div class="mk-sum-row">
                        <span>هزینه ارسال</span>
                        <strong>{{ number_format(cartDeliveryAmount()) }} تومان</strong>
                    </div>
                    @if(session()->has('coupon'))
                        <div class="mk-sum-row is-discount">
                            <span>کد تخفیف ({{ session('coupon.code') }})</span>
                            <strong>− {{ number_format(session('coupon.amount')) }} تومان</strong>
                        </div>
                    @endif
                    <div class="mk-sum-row is-total">
                        <span>مبلغ قابل پرداخت</span>
                        <strong>{{ number_format(cartPayingAmount()) }} تومان</strong>
                    </div>

                    <form action="{{ route('home.cart.coupon.check') }}" method="POST" class="mk-coupon-form">
                        @csrf
                        <label for="coupon-code">کد تخفیف دارید؟</label>
                        <div class="mk-coupon-row">
                            <input type="text" id="coupon-code" name="code" placeholder="مثلاً MEGA25" dir="ltr"
                                   autocomplete="off" required>
                            <button type="submit" class="mk-btn mk-btn-outline">اعمال</button>
                        </div>
                    </form>

                    <a href="{{ route('home.cart.checkout') }}" class="mk-btn mk-btn-primary mk-btn-block mk-cart-cta">
                        <span class="fa fa-credit-card"></span> ادامه و تسویه حساب
                    </a>

                    <p class="mk-cart-demo-note">
                        <span class="fa fa-info-circle"></span>
                        پرداخت در این نسخه شبیه‌سازی می‌شود و سفارش واقعی ثبت نمی‌شود.
                    </p>
                </aside>
            </div>
        @endif
    </div>
@endsection
