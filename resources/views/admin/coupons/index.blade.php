@extends('admin.layout.master')

@php
    $title = 'کد های تخفیف';
@endphp

@section('title', $title)

@section('styles')
    <style>
        .fade-disable {
            opacity: 0.5;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .fade-enable {
            opacity: 1;
            pointer-events: auto;
            transition: opacity 0.3s ease;
        }
    </style>
@endsection

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
            <div class="d-flex m-1 align-items-center justify-content-between">
                @can('coupons-create')
                    <div class="c-grey text-center">
                        <button data-target="#createCouponModal" data-toggle="modal" type="button" class="btn f-primary fnt-xxs text-center">
                            ایجاد کد تخفیف
                        </button>
                    </div>
                    @include('admin.coupons.partials.create-modal')
                @endcan
                <form action="#" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary c-primary" type="submit">
                                <i class="fab fas fa-search"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" placeholder="جستجو"
                               style="width: 300px"
                               value="{{ request('q') }}" name="q" required>
                    </div>
                </form>
            </div>
            <div class="row m-1">
                <div class="col-12 p-2">
                    <div class="card shade h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $title }}</h5>
                            <hr/>
                            @if($coupons->isEmpty())
                                <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show" role="alert">
                                    هیج کد تخفیفی وجود ندارد!
                                </div>
                            @else
                                <table class="table table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">عنوان</th>
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
                                                {{ $coupon->title }}
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
                                                <div class="dropdown base show">
                                                    <a class="btn o-grey dropdown-toggle text-center" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        @can('coupons-index')
                                                            <button data-target="#showCouponModal-{{ $coupon->id }}" data-toggle="modal" type="button" class="dropdown-item">نمایش</button>
                                                        @endcan
                                                        @can('coupons-edit')
                                                            <button data-target="#editCouponModal-{{ $coupon->id }}" data-toggle="modal" type="button" class="dropdown-item">ویرایش</button>
                                                        @endcan
                                                        @can('coupons-delete')
                                                            <button data-target="#deleteCouponModal-{{ $coupon->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                        @endcan
                                                    </div>
                                                    @can('coupons-index')
                                                        @include('admin.coupons.partials.show-modal')
                                                    @endcan
                                                    @can('coupons-edit')
                                                        @include('admin.coupons.partials.edit-modal')
                                                    @endcan
                                                    @can('coupons-delete')
                                                        @include('admin.coupons.partials.delete-modal')
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            {{ $coupons->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        $(function() {
            @if(count($errors->store) > 0)
                $('#createCouponModal').modal({
                    show: true
                });
            @endif

            jalaliDatepicker.startWatch({minDate: 'today',
                time: true});

            const $type = $('#type'),
                $amount = $('#amount'),
                $percentage = $('#percentage'),
                $max = $('#max_percentage_amount');

            const $amountGroup = $amount.closest('.form-group');
            const $percentageGroup = $percentage.closest('.form-group');
            const $maxGroup = $max.closest('.form-group');

            function toggleFields(val) {
                if (val === 'amount') {
                $amount.prop({required: true, disabled: false}).attr('name', 'amount');
                $percentage.prop({required: false, disabled: true}).removeAttr('name');
                $max.prop({required: false, disabled: true}).removeAttr('name');

                $amountGroup.removeClass('fade-disable').addClass('fade-enable');
                $percentageGroup.add($maxGroup).removeClass('fade-enable').addClass('fade-disable');
                } else {
                    $amount.prop({required: false, disabled: true}).removeAttr('name');
                    $percentage.prop({required: true, disabled: false}).attr('name', 'percentage');
                    $max.prop({required: true, disabled: false}).attr('name', 'max_percentage_amount');

                    $amountGroup.removeClass('fade-enable').addClass('fade-disable');
                    $percentageGroup.add($maxGroup).removeClass('fade-disable').addClass('fade-enable');
                }
            }

            toggleFields($type.val());
            $type.on('change', function () {
            toggleFields(this.value);
            });
        });
    </script>
@endsection
