@extends('home.layout.master')

@section('title', 'درگاه پرداخت آزمایشی')

@section('content')
    <div class="container mk-checkout-page">
        <nav class="mk-crumbs" aria-label="مسیر صفحه">
            <a href="{{ route('home.index') }}">خانه</a>
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <a href="{{ route('home.cart.index') }}">سبد خرید</a>
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <span>درگاه آزمایشی</span>
        </nav>

        @include('home.sections.demo-notice')

        <div class="mk-gateway">
            <div class="mk-gateway-card">
                <header class="mk-gateway-head">
                    <span class="mk-gateway-logo"><span class="fa fa-credit-card"></span></span>
                    <div>
                        <strong>درگاه پرداخت آزمایشی</strong>
                        <small>سفارش #{{ $order->id }} — {{ verta($order->created_at)->format('%d %B %Y - H:i') }}</small>
                    </div>
                    <span class="mk-gateway-badge">شبیه‌سازی</span>
                </header>

                <div class="mk-gateway-amount">
                    <span>مبلغ قابل پرداخت</span>
                    <strong>{{ number_format($order->paying_amount) }} <small>تومان</small></strong>
                </div>

                <div class="mk-gateway-rows">
                    <div class="mk-sum-row">
                        <span>شناسه تراکنش</span>
                        <strong dir="ltr">#{{ $transaction->id }}</strong>
                    </div>
                    <div class="mk-sum-row">
                        <span>وضعیت</span>
                        <strong>در انتظار پرداخت</strong>
                    </div>
                    <div class="mk-sum-row">
                        <span>اقلام</span>
                        <strong>{{ number_format($order->items->count()) }} قلم</strong>
                    </div>
                </div>

                <div class="mk-gateway-items">
                    @foreach($order->items as $item)
                        <div class="mk-gateway-item">
                            <span>{{ $item->product->title ?? 'محصول حذف‌شده' }}</span>
                            <small>× {{ $item->quantity }}</small>
                            <strong>{{ number_format($item->subtotal) }}</strong>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('home.payment.simulator.submit', ['order' => $order->id]) }}"
                      class="mk-gateway-actions">
                    @csrf

                    <button type="submit" name="action" value="success" class="mk-btn mk-btn-success mk-btn-block">
                        <span class="fa fa-check-circle"></span> پرداخت موفق (شبیه‌سازی)
                    </button>
                    <button type="submit" name="action" value="cancel" class="mk-btn mk-btn-ghost mk-btn-block">
                        <span class="fa fa-times"></span> انصراف از پرداخت
                    </button>
                </form>

                <p class="mk-cart-demo-note">
                    <span class="fa fa-info-circle"></span>
                    این درگاه به هیچ بانکی متصل نیست؛ نتیجه فقط برای نمایش ثبت می‌شود.
                </p>
            </div>
        </div>
    </div>
@endsection
