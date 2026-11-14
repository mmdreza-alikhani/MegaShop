@extends('home.profile.master')

@section('section')
    <div class="mk-panel">
        <header class="mk-panel-head">
            <div>
                <h2>سفارش‌های من</h2>
                <p>وضعیت پرداخت و جزئیات هر سفارش را اینجا ببینید.</p>
            </div>
            <span class="mk-panel-count">{{ $orders->total() }} سفارش</span>
        </header>

        @if($orders->isEmpty())
            <div class="mk-empty">
                <span class="fa fa-shopping-cart"></span>
                <p>هنوز سفارشی ثبت نکرده‌اید.</p>
                <a href="{{ route('home.products.index') }}" class="mk-btn mk-btn-primary">شروع خرید</a>
            </div>
        @else
            <div class="mk-orders">
                @foreach($orders as $order)
                    @php($isPaid = (bool) $order->getRawOriginal('payment_status'))
                    <div class="mk-order-card">
                        <div class="mk-order-main">
                            <span class="mk-order-id">سفارش #{{ $order->id }}</span>
                            <span class="mk-order-date">
                                <span class="fa fa-calendar"></span>
                                {{ verta($order->created_at)->format('%d %B %Y') }}
                            </span>
                        </div>

                        <div class="mk-order-meta">
                            <span class="mk-status {{ $isPaid ? 'is-ok' : 'is-bad' }}">
                                {{ $order->payment_status }}
                            </span>

                            <span class="mk-order-total">
                                {{ number_format($order->paying_amount) }}
                                <small>تومان</small>
                            </span>

                            <a href="{{ route('home.profile.orders.show', ['order' => $order->id]) }}"
                               class="mk-btn mk-btn-outline mk-btn-sm">
                                جزئیات
                                <span class="fa fa-angle-left"></span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mk-pagination-wrap">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
