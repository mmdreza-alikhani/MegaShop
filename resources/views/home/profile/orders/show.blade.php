@extends('home.profile.master')

@php
    $active = 'orders';
@endphp

@section('section')
    <div class="info-box p-5 m-2 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6);direction: rtl">
        <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
            <div class="nk-widget nk-widget-highlighted" style="direction: rtl">
                <ul class="nk-breadcrumbs text-right px-3" style="direction: rtl">
                    <li><span style="font-size: 24px">نمایش سفارش: </span></li>
                </ul>
            </div>
        </aside>
        <div class="nk-gap"></div>
        <div class="mx-4 row" style="direction: rtl">
            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>کد تخفیف استفاده شده:</label>
                @if($order->coupon_id != null)
                <p>
                    {{ $order->coupon->code . ' ' }}
                    به مبلغ:
                    {{ number_format($order->coupon_amount) }}
                    تومان
                </p>
                @else
                    <p>
                        کد تخفیفی در سفارش شما ثبت نشده است!
                    </p>
                @endif
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>محل تحویل(آدرس):</label>
                <p>
                    {{ $order->address->title }}
                </p>
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>مبلغ کل:</label>
                <p>
                    {{ number_format($order->total_amount) }}
                    تومان
                </p>
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>هزینه ارسال:</label>
                <p>
                    {{ number_format($order->delivery_amount) }}
                    تومان
                </p>
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>مبلغ قابل پرداخت:</label>
                <p>
                    {{ number_format($order->paying_amount) }}
                    تومان
                </p>
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>نحوه پرداخت:</label>
                <p>
                    {{ $order->payment_type }}
                </p>
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>وضعیت پرداخت:</label>
                <span class="badge {{ $order->getRawOriginal('payment_status') ?  'badge-success' : 'badge-danger' }}">
                    {{ $order->payment_status }}
                </span>
                @if($order->getRawOriginal('payment_status') == 1)
                    <p>
                        {{ $transaction->ref_id }}
                    </p>
                @endif
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>توکن سفارش:(مهم)</label>
                <p>
                    {{ $transaction->token }}
                </p>
            </div>

            <div class="col-12 col-lg-4 text-right" style="direction: rtl">
                <label>تاریخ ثبت سفارش:</label>
                <p>
                    {{ verta($order->created_at)->format('%d %B, %Y') }}
                </p>
            </div>

            <div class="col-12 col-lg-12 text-right" style="direction: rtl">
                <label>توضیحات:</label>
                @if($order->description == null)
                    <p>
                        توضیحاتی ثبت نشده است!
                    </p>
                @else
                    <textarea>
                        {{ $order->description }}
                    </textarea>
                @endif
            </div>
            <div class="col-12 col-lg-12 text-right" style="direction: rtl">
                <label>محصولات سفارش:</label>
                <ul class="text-main-1 pl-20 text-right row" style="direction: rtl">
                    @foreach($order->items as $item)
                        <li class="col-12 col-lg-4"><a href="{{ route('home.products.show', ['product' => $item->product->slug]) }}">{{ $item->product->title }}</a> <br> <strong class="text-white">{{ $item->productVariation->value . ' - ' . $item->quantity . ' عدد' }}</strong></li>
                    @endforeach
                </ul>
            </div>
            <hr>
            <div class="nk-gap-2"></div>
            <a class="btn btn-danger" href="{{ route('home.profile.orders.index') }}">بازگشت</a>
        </div>
    </div>
@endsection
