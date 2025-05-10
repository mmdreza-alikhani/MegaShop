@extends('admin.layout.master')
@section('title')
    لیست کد های تخفیف
@endsection
@section('button')
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-outline-primary">
        <i class="fa fa-plus"></i>
        افزودن کد تخفیف جدید
    </a>
@endsection
@php
    $active_parent = 'coupons';
    $active_child = 'showcoupons';
@endphp
@section('content')
    <form id="search" class="form-inline ml-3 mb-3" action="{{ route('admin.coupons.search') }}" method="GET"
          style="display: flex;align-items: center;justify-content: center">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="text" placeholder="جستجو با نام کد تخفیف"
                   value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="mx-4">
        <table class="table text-center">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">نام</th>
                <th scope="col">کد</th>
                <th scope="col">نوع</th>
                <th scope="col">تاریخ انقضا</th>
                <th scope="col">تنظیمات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($coupons as $key => $coupon)
                <tr>
                    <th>
                        {{ $coupons->firstItem() + $key }}
                    </th>
                    <td>
                        {{ $coupon->name }}
                    </td>
                    <td>
                        {{ $coupon->code }}
                    </td>
                    <td>
                        {{ $coupon->type }}
                    </td>
                    <td>
                        {{ verta($coupon->expired_at) }}
                    </td>
                    <td>
                        <div class="row" style="display: flex;justify-content: center">
                            <a href="{{ route('admin.coupons.show' , [$coupon->id]) }}" class="btn btn-success m-1">
                                نمایش
                            </a>
                            <a href="{{ route('admin.coupons.edit' , [$coupon->id]) }}" class="btn btn-info m-1">
                                ویرایش
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $coupons->links() }}
    </div>
@endsection
