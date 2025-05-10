@extends('admin.layout.master')
@section('title')
    نمایش کد تخفیف: {{$coupon->name}}
@endsection
@php
    $active_parent = 'coupons';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 row">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    مشخصات کد تخفیف
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-3">
                            <label>عنوان</label>
                            <input type="text" value="{{ $coupon->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>کد</label>
                            <input type="text" value="{{ $coupon->code }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>نوع</label>
                            <input type="text" value="{{ $coupon->type }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-3">
                            <label>مبلغ</label>
                            <input type="text" value="{{ $coupon->amount }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>درصد</label>
                            <input type="text" value="{{ $coupon->percentage }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>حداکثر مبلغ برای نوع درصدی</label>
                            <input type="text" value="{{ $coupon->max_percentage_amount }}" class="form-control"
                                   disabled>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label>تاریخ انقضا</label>
                            <input type="text" value="{{ verta($coupon->expired_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label>متن</label>
                            <textarea class="form-control" disabled>{{ strip_tags($coupon->description) }}</textarea>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($coupon->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($coupon->updated_at) }}" class="form-control" disabled>
                        </div>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-danger">بازگشت</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
