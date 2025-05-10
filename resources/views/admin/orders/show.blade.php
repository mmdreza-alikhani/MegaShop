@extends('admin.layout.master')
@section('title')
    سفارش : {{ $transaction->token }}
@endsection
@php
    $active_parent = 'orders';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    مشخصات سفارش
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-3">
                            <label>سفارش دهنده:</label>
                            <br>
                            <a href="#">
                                {{ $order->user->username }}
                            </a>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>محل تحویل:(آدرس)</label>
                            <p>
                                {{ $order->address->title }}
                            </p>
                        </div>
                        <div class="form-group col-12 col-lg-3">
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
                        <div class="form-group col-12 col-lg-3">
                            <label>توکن سفارش:</label>
                            <p>
                                {{ $transaction->token }}
                            </p>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>تاریخ ثبت سفارش:</label>
                            <p>
                                {{ verta($order->created_at)->format('%d %B, %Y') }}
                            </p>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>مبلغ کل:</label>
                            <p>
                                {{ number_format($order->total_amount) }}
                                تومان
                            </p>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>هزینه ارسال:</label>
                            <p>
                                {{ number_format($order->delivery_amount) }}
                                تومان
                            </p>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>مبلغ قابل پرداخت:</label>
                            <p>
                                {{ number_format($order->paying_amount) }}
                                تومان
                            </p>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>نحوه پرداخت:</label>
                            <p>
                                {{ $order->payment_type }}
                            </p>
                        </div>
                        <div class="form-group col-12 col-lg-3">
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
                        <div class="form-group col-12 col-lg-3">
                            <label>درگاه پرداخت:</label>
                            <span class="badge badge-success">

                                {{ $transaction->getaway_name }}
                            </span>
                        </div>
                        <div class="form-group col-12 col-lg-12">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    محصولات سفارش:
                </div>
                <div class="card-body">
                    <div class="col-12 col-lg-12 text-right" style="direction: rtl">
                        <ul class="text-main-1 pl-20 text-right" style="direction: rtl">
                            @foreach($order->items as $item)
                                <li class="col-12 col-lg-4"><a
                                            href="{{ route('home.products.show', ['product' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                    <br>
                                    <strong>{{ $item->productVariation->value . ' - ' . $item->quantity . ' عدد' }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-danger mb-2 h-25">بازگشت</a>
    </div>
@endsection
