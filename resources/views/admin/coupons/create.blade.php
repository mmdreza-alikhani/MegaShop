@extends('admin.layout.master')

@section('title', 'کد تخفیف جدید')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>کد تخفیف جدید</h3>
                    <p>یک کد تخفیف تازه برای فروشگاه بسازید</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            @include('admin.coupons.partials.form', ['coupon' => null])
        </div>
    </main>
@endsection
