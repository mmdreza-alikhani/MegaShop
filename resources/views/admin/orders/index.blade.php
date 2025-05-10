@extends('admin.layout.master')
@section('title')
    لیست سفارشات
@endsection
@php
    $active_parent = 'orders';
    $active_child = 'showorders';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.orders.search') }}" method="GET"
          style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با شناسه سفارش"
                   value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="mx-4">
        <table class="table text-center table-responsive-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">شناسه سفارش</th>
                <th scope="col">توکن</th>
                <th scope="col">مبلغ قابل پرداخت</th>
                <th scope="col">وضعیت پرداخت</th>
                <th scope="col">نمایش</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $key => $order)
                <tr>
                    <th>
                        {{ $orders->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $order->id }}
                    </td>
                    <td>
                        {{ $order->transaction->token }}
                    </td>
                    <td>
                        {{ number_format($order->paying_amount) . 'تومان' }}
                    </td>
                    <td>
                        <span class="badge {{ $order->getRawOriginal('payment_status') ?  'badge-success' : 'badge-danger' }}">
                            {{ $order->payment_status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show' , ['order' => $order->id]) }}"
                           class="btn btn-success m-1">
                            نمایش
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
@endsection
