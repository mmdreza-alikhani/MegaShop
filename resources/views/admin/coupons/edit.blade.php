@extends('admin.layout.master')

@section('title', 'ویرایش کد تخفیف')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ویرایش کد تخفیف</h3>
                    <p>{{ $coupon->title }} — {{ $coupon->code }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.coupons.partials.form', ['coupon' => $coupon])
        </div>
    </main>
@endsection
