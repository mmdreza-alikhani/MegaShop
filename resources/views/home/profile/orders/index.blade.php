@extends('home.profile.master')

@php
    $active = 'orders';
@endphp

@section('section')
    <div class="info-box m-2 h-100 w-100 row rounded" style="background-color: rgba(35,41,48,.6)">
        <table class="nk-table text-right container" style="direction: rtl">
                <thead>
                <tr>
                    <th colspan="5">تمامی سفارشات</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th class="text-center">شناسه سفارش</th>
                    <th class="text-center">تاریخ ثبت سفارش</th>
                    <th class="text-center">مبلغ قابل پرداخت</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">نمایش</th>
                </tr>
                @foreach($orders as $order)
                    <tr>
                        <td class="text-center">{{ $order->id }}</td>
                        <td class="text-center">{{ verta($order->created_at)->format('%d %B, %Y') }}</td>
                        <td class="text-center">{{ number_format($order->paying_amount) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $order->getRawOriginal('payment_status') ?  'badge-success' : 'badge-danger' }}">
                                {{ $order->payment_status }}
                            </span>
                        </td>
                        <td class="text-center row">
                            <div class="col-12">
                                <a class="btn nk-btn-color-main-1 text-light" href="{{ route('home.profile.orders.showOrder', ['order' => $order->id]) }}">
                                    <i class="fa fa-info"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
        </table>
        {{ $orders->links() }}
    </div>
@endsection
