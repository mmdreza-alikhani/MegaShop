@extends('admin.layout.master')

@php
    $title = 'نمایش سفارش'
@endphp

@section('title', $title)

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="row m-1 pb-4 mb-3">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-2">
                    <div class="page-header breadcrumb-header">
                        <div class="row align-items-end">
                            <div class="col-lg-8">
                                <div class="page-header-title text-left-rtl">
                                    <div class="d-inline">
                                        <h3 class="lite-text">پنل مدیریت</h3>
                                        <span class="lite-text">{{ $title }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $title }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mx-1">
                <div class="card shade c-grey w-100">
                    <h5 class="card-header c-primary">{{ $title }}</h5>
                    <div class="card-body row">
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
                        <div class="form-group card shade c-grey col-12 col-lg-12">
                            <h5 class="card-header c-primary">دکمه</h5>
                            <div class="card-body">
                                <div class="row col-lg-12 text-right" style="direction: rtl">
                                    <ul class="text-main-1 pl-20">
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
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
