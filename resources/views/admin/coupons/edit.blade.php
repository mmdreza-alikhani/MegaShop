@extends('admin.layout.master')
@section('title')
    ویرایش کد تخفیف: {{$coupon->name}}
@endsection
@php
    $active_parent = 'coupons';
    $active_child = ''
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.coupons.update' , ['coupon' => $coupon->id]) }}" method="POST" class="row"
              enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        مشخصات کد تخفیف:
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" value="{{ $coupon->id }}">
                            <div class="form-group col-12 col-lg-3">
                                <label for="name">عنوان:*</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ $coupon->name }}">
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="code">کد:*</label>
                                <input type="text" name="code" id="code" class="form-control"
                                       value="{{ $coupon->code }}">
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="type">نوع:*</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="amount" {{ $coupon->getRawOriginal('type') ? 'selected' : '' }}>
                                        مبلغی
                                    </option>
                                    <option value="percentage" {{ $coupon->getRawOriginal('type') ? 'selected' : '' }}>
                                        درصدی
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="amount">مبلغ:*</label>
                                <input type="text" name="amount" id="amount" class="form-control"
                                       value="{{ $coupon->amount }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="percentage">درصد:*</label>
                                <input type="text" name="percentage" id="percentage" class="form-control"
                                       value="{{ $coupon->percentage }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="max_percentage_amount">حداکثر مبلغ برای نوع درصدی:*</label>
                                <input type="text" name="max_percentage_amount" id="max_percentage_amount"
                                       class="form-control" value="{{ $coupon->max_percentage_amount }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="expired_at">تاریخ انقضا:*</label>
                                <input data-jdp name="expired_at" id="expired_at" class="form-control"
                                       value="{{ verta($coupon->expired_at) }}">
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="editor">توضیحات:*</label>
                                <textarea type="text" name="description" id="editor" class="form-control">
                                    {{ $coupon->description }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        انتشار
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary w-100" type="submit" name="submit">ویرایش</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-danger w-100" type="cancel"
                                   name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                language: {
                    // The UI will be English.
                    ui: 'fa',

                    // But the content will be edited in Arabic.
                    content: 'fa'
                }
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });

        jalaliDatepicker.startWatch({time: true});
    </script>
@endsection
