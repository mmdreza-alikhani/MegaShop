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
                <div class="c-grey text-center">
                    <button data-target="#createCouponModal" data-toggle="modal" type="button" class="btn f-primary fnt-xxs text-center">
                        ایجاد کد تخفیف
                    </button>
                </div>
                <div class="modal w-lg fade light rtl" id="createCouponModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form method="post" action="{{ route('admin.coupons.store') }}">
                            @csrf
                            <div class="modal-content card shade">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        ایجاد کد تخفیف جدید
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.layout.errors', ['errors' => $errors->store])
                                    <div class="row">
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="title">عنوان:*</label>
                                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="code">کد:*</label>
                                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required>
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="expired_at">تاریخ انقضا:*</label>
                                            <input data-jdp name="expired_at" id="expired_at" class="form-control"
                                                   value="{{ old('expired_at') }}" required>
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="type">نوع:*</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="amount" {{ old('type') == 'amount' ? 'selected' : '' }}>مبلغی</option>
                                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>درصدی</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-lg-4">
                                            <label for="amount">مبلغ:*</label>
                                            <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
                                        </div>
                                        <div class="form-group col-12 col-lg-4">
                                            <label for="percentage">درصد:*</label>
                                            <input type="number" min="0" max="100" name="percentage" id="percentage" class="form-control" value="{{ old('percentage') }}">
                                        </div>
                                        <div class="form-group col-12 col-lg-4">
                                            <label for="max_percentage_amount">حداکثر مبلغ برای نوع درصدی:*</label>
                                            <input type="number" name="max_percentage_amount" id="max_percentage_amount"
                                                   class="form-control" value="{{ old('max_percentage_amount') }}">
                                        </div>
                                        <div class="form-group col-12 col-lg-12">
                                            <label for="description">توضیحات:*</label>
                                            <textarea type="text" name="description" id="description" class="form-control" required>{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn main f-main">ایجاد</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <form action="{{ route('admin.coupons.search') }}" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary c-primary" type="submit">
                                <i class="fab fas fa-search"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" placeholder="جستجو"
                               style="width: 300px"
                               value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword" required>
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
                                                        <button data-target="#showCouponModal-{{ $coupon->id }}" data-toggle="modal" type="button" class="dropdown-item">نمایش</button>
                                                        <button data-target="#editCouponModal-{{ $coupon->id }}" data-toggle="modal" type="button" class="dropdown-item">ویرایش</button>
                                                        <button data-target="#deleteCouponModal-{{ $coupon->id }}" data-toggle="modal" type="button" class="dropdown-item">حذف</button>
                                                    </div>
                                                    <div class="modal w-lg fade light rtl" id="showCouponModal-{{ $coupon->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content card shade">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        نمایش کد تخفیف: {{ $coupon->title }}
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="title-{{ $coupon->id }}-show">عنوان:*</label>
                                                                            <input type="text" id="title-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->title }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="code-{{ $coupon->id }}-show">کد:*</label>
                                                                            <input type="text" id="code-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->code }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="expired_at-{{ $coupon->id }}-show">تاریخ انقضا:*</label>
                                                                            <input id="expired_at-{{ $coupon->id }}-show" class="form-control" value="{{ verta($coupon->expired_at) }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-6">
                                                                            <label for="type-{{ $coupon->id }}-show">نوع:*</label>
                                                                            <select class="form-control" id="type-{{ $coupon->id }}-show" name="type" disabled>
                                                                                <option value="amount" {{ $coupon->type == 'amount' ? 'selected' : '' }}>مبلغی</option>
                                                                                <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>درصدی</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="amount-{{ $coupon->id }}-show">مبلغ:*</label>
                                                                            <input type="number" id="amount-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->amount }}" disabled>
                                                                            <script>convertNumberToPersianWords({{ $coupon->amount }})</script>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="percentage-{{ $coupon->id }}-show">درصد:*</label>
                                                                            <input type="number" min="0" max="100" id="percentage-{{ $coupon->id }}-show" class="form-control" value="{{ $coupon->percentage }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-4">
                                                                            <label for="max_percentage_amount-{{ $coupon->id }}-show">حداکثر مبلغ برای نوع درصدی:*</label>
                                                                            <input type="number" id="max_percentage_amount-{{ $coupon->id }}-show"
                                                                                   class="form-control" value="{{ $coupon->max_percentage_amount }}" disabled>
                                                                        </div>
                                                                        <div class="form-group col-12 col-lg-12">
                                                                            <label for="description-{{ $coupon->id }}-show">توضیحات:*</label>
                                                                            <textarea type="text" name="description" id="description-{{ $coupon->id }}-show" class="form-control" disabled>{{ $coupon->description }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal w-lg fade light rtl" id="editCouponModal-{{ $coupon->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post" action="{{ route('admin.coupons.update', ['coupon' => $coupon->id]) }}">
                                                                @method('put')
                                                                @csrf
                                                                <div class="modal-content card shade">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            ویرایش کد تخفیف: {{ $coupon->title }}
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @include('admin.layout.errors', ['errors' => $errors->update])
                                                                        <div class="row">
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="title-{{ $coupon->id }}">عنوان:*</label>
                                                                                <input type="text" name="title" id="title-{{ $coupon->id }}" class="form-control" value="{{ $coupon->title }}" required>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="code-{{ $coupon->id }}">کد:*</label>
                                                                                <input type="text" name="code" id="code-{{ $coupon->id }}" class="form-control" value="{{ $coupon->code }}" required>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="expired_at-{{ $coupon->id }}">تاریخ انقضا:*</label>
                                                                                <input data-jdp name="expired_at" id="expired_at-{{ $coupon->id }}" class="form-control"
                                                                                       value="{{ verta($coupon->expired_at)->format('Y/m/d H:i:s') }}" required>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-6">
                                                                                <label for="type-{{ $coupon->id }}">نوع:*</label>
                                                                                <select class="form-control" id="type-{{ $coupon->id }}" name="type" required>
                                                                                    <option value="amount" {{ $coupon->getRawOriginal('type') == 'amount' ? 'selected' : '' }}>مبلغی</option>
                                                                                    <option value="percentage" {{ $coupon->getRawOriginal('type') == 'percentage' ? 'selected' : '' }}>درصدی</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-4">
                                                                                <label for="amount-{{ $coupon->id }}">مبلغ:*</label>
                                                                                <input type="number" name="amount" id="amount-{{ $coupon->id }}" class="form-control" value="{{ $coupon->amount }}" required>
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-4">
                                                                                <label for="percentage-{{ $coupon->id }}">درصد:*</label>
                                                                                <input type="number" min="0" max="100" name="percentage" id="percentage-{{ $coupon->id }}" class="form-control" value="{{ $coupon->percentage }}">
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-4">
                                                                                <label for="max_percentage_amount-{{ $coupon->id }}">حداکثر مبلغ برای نوع درصدی:*</label>
                                                                                <input type="number" name="max_percentage_amount" id="max_percentage_amount-{{ $coupon->id }}"
                                                                                       class="form-control" value="{{ $coupon->max_percentage_amount }}">
                                                                            </div>
                                                                            <div class="form-group col-12 col-lg-12">
                                                                                <label for="description-{{ $coupon->id }}">توضیحات:*</label>
                                                                                <textarea type="text" name="description" id="description-{{ $coupon->id }}" class="form-control" required>{{ $coupon->description }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                                                                        <button type="submit" class="btn main f-main">ویرایش</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        const $type = $('#type-{{ $coupon->id }}');
                                                        const $amount = $('#amount-{{ $coupon->id }}');
                                                        const $percentage = $('#percentage-{{ $coupon->id }}');
                                                        const $max = $('#max_percentage_amount-{{ $coupon->id }}');

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
                                                    </script>
                                                    @if(count($errors->update) > 0)
                                                        <script>
                                                            $(function() {
                                                                $('#editCouponModal-{{ session()->get('coupon_id') }}').modal({
                                                                    show: true
                                                                });
                                                            });
                                                        </script>
                                                    @endif
                                                    <div class="modal w-lg fade justify rtl" id="deleteCouponModal-{{ $coupon->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">حذف کد تخفیف: {{ $coupon->title }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    آیا از این عملیات مطمئن هستید؟
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn outlined o-danger c-danger"
                                                                            data-dismiss="modal">بستن</button>
                                                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display: inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn f-main">حذف</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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

            jalaliDatepicker.startWatch({time: true});

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
