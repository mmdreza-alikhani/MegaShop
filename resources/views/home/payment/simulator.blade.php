{{--
    صفحه شبیه‌ساز درگاه پرداخت (نسخه دمو)
    هیچ اتصال واقعی به درگاه بانکی انجام نمی‌شود؛ کاربر با دو دکمه،
    نتیجه پرداخت (موفق/لغو) را شبیه‌سازی می‌کند.
--}}
@extends('home.layout.master')

@section('title')
    درگاه پرداخت آزمایشی
@endsection

@section('content')

<!-- START: Breadcrumbs -->
<div class="nk-gap-1"></div>
<div class="container my-3">
    <ul class="nk-breadcrumbs text-right" style="direction: rtl">
        <li><a href="{{ route('home.index') }}">خانه</a></li>

        <li><span class="fa fa-angle-left"></span></li>

        <li><a href="{{ route('home.cart.index') }}">سبد خرید</a></li>

        <li><span class="fa fa-angle-left"></span></li>

        <li><span>درگاه پرداخت آزمایشی</span></li>
    </ul>
</div>
<div class="nk-gap-1"></div>
<!-- END: Breadcrumbs -->

<div class="container" style="direction: rtl">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-12">
            <div class="nk-gap-2"></div>

            <div class="alert alert-warning text-right">
                <strong><span class="fa fa-info-circle"></span> شبیه‌ساز درگاه پرداخت</strong>
                <p class="mb-0 mt-2">
                    این نسخهٔ نمایشی (دمو) است و هیچ تراکنش بانکی واقعی انجام نمی‌شود.
                    با انتخاب هر یک از گزینه‌های زیر می‌توانید نتیجهٔ پرداخت را شبیه‌سازی کنید.
                </p>
            </div>

            <div class="card text-right">
                <div class="card-header" style="background-color: #dd163b; color: #fff">
                    پرداخت سفارش #{{ $order->id }}
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">مبلغ قابل پرداخت: <strong>{{ number_format($order->paying_amount) }} تومان</strong></li>
                        <li class="mb-2">تاریخ ثبت سفارش: {{ verta($order->created_at)->format('%d %B %Y - H:i') }}</li>
                        <li class="mb-2">شناسهٔ تراکنش: {{ $transaction->id }}</li>
                        <li class="mb-2">وضعیت فعلی: در انتظار پرداخت</li>
                    </ul>

                    <hr>

                    <h5 class="mb-3">اقلام سفارش</h5>
                    <div class="table-responsive">
                        <table class="table table-sm text-right">
                            <thead>
                            <tr>
                                <th>محصول</th>
                                <th>تعداد</th>
                                <th>جمع</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->title ?? '—' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->subtotal) }} تومان</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <form method="POST" action="{{ route('home.payment.simulator.submit', ['order' => $order->id]) }}"
                          class="d-flex justify-content-between flex-wrap">
                        @csrf
                        <button type="submit" name="action" value="success"
                                class="nk-btn nk-btn-rounded nk-btn-color-main-1 my-2">
                            <span class="fa fa-check"></span> پرداخت موفق (شبیه‌سازی)
                        </button>

                        <button type="submit" name="action" value="cancel"
                                class="nk-btn nk-btn-rounded nk-btn-color-dark-3 my-2">
                            <span class="fa fa-times"></span> انصراف از پرداخت
                        </button>
                    </form>
                </div>
            </div>

            <div class="nk-gap-3"></div>
        </div>
    </div>
</div>

@endsection
