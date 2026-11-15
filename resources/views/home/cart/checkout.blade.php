@extends('home.layout.master')

@section('title', 'نهایی کردن خرید')

@section('content')
    <div class="container mk-checkout-page">
        <nav class="mk-crumbs" aria-label="مسیر صفحه">
            <a href="{{ route('home.index') }}">خانه</a>
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <a href="{{ route('home.cart.index') }}">سبد خرید</a>
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <span>تسویه حساب</span>
        </nav>

        @include('home.sections.demo-notice')

        <div class="mk-cart-head">
            <div>
                <h1>تسویه حساب</h1>
                <p>اطلاعات ارسال را بررسی کنید و سفارش را ثبت کنید</p>
            </div>
            <a href="{{ route('home.cart.index') }}" class="mk-btn mk-btn-outline">
                <span class="fa fa-arrow-right"></span> بازگشت به سبد
            </a>
        </div>

        <form method="POST" action="{{ route('home.payment.process') }}" class="mk-checkout-layout">
            @csrf

            <div class="mk-checkout-main">
                <section class="mk-panel">
                    <header class="mk-panel-head">
                        <h2>اقلام سفارش</h2>
                        <span>{{ number_format(cartItems()->count()) }} قلم</span>
                    </header>

                    <div class="mk-checkout-items">
                        @foreach(cartItems() as $item)
                            <article class="mk-checkout-item">
                                <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $item->itemable->primary_image) }}"
                                     alt="{{ $item->itemable->title }}" loading="lazy">
                                <div>
                                    <a href="{{ route('home.products.show', ['product' => $item->itemable->slug]) }}">
                                        {{ $item->itemable->title }}
                                    </a>
                                    <small>
                                        {{ $item->variation->attribute?->title }}: {{ $item->variation->value }}
                                        × {{ $item->quantity }}
                                    </small>
                                </div>
                                <strong>{{ number_format($item->total_price) }} <small>تومان</small></strong>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="mk-panel">
                    <header class="mk-panel-head">
                        <h2>اطلاعات ارسال</h2>
                        <a href="{{ route('home.profile.addresses.index') }}" class="mk-link">
                            <span class="fa fa-plus"></span> آدرس جدید
                        </a>
                    </header>

                    @if($addresses->isEmpty())
                        <div class="mk-alert mk-alert-warning">
                            <span class="fa fa-map-marker"></span>
                            هنوز آدرسی ثبت نکرده‌اید. برای ادامه، ابتدا یک آدرس اضافه کنید.
                            <a href="{{ route('home.profile.addresses.index') }}" class="mk-link">افزودن آدرس</a>
                        </div>
                    @else
                        <div class="mk-field">
                            <label for="address_id">انتخاب آدرس <em>*</em></label>
                            <select name="address_id" id="address_id" required>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->title }} — {{ $address->province?->name ?? '' }} {{ $address->city?->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mk-field">
                        <label for="description">توضیحات سفارش</label>
                        <textarea name="description" id="description" rows="4" maxlength="255"
                                  placeholder="مثلاً: ساعت تحویل، توضیح بسته‌بندی…"></textarea>
                    </div>
                </section>

                <div class="mk-checkout-note">
                    <span class="fa fa-shield"></span>
                    <div>
                        <strong>پرداخت آزمایشی است</strong>
                        <p>با زدن دکمهٔ زیر، یک سفارش نمایشی ساخته می‌شود و به شبیه‌ساز درگاه می‌روید؛ هیچ پولی پرداخت نمی‌شود.</p>
                    </div>
                </div>
            </div>

            <aside class="mk-cart-summary">
                <h2>خلاصه پرداخت</h2>

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

                <button type="submit" class="mk-btn mk-btn-primary mk-btn-block mk-cart-cta"
                        @disabled($addresses->isEmpty())>
                    <span class="fa fa-check"></span> ثبت سفارش آزمایشی
                </button>

                <p class="mk-cart-demo-note">
                    <span class="fa fa-info-circle"></span>
                    سفارش فقط برای نمایش ثبت می‌شود و ارسال نمی‌گردد.
                </p>
            </aside>
        </form>
    </div>
@endsection
