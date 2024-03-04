@extends('admin.layouts.master')
@section('title')
    بنر : {{ $banner->title }}
@endsection
@php
    $active_parent = 'banners';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 row">
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    مشخصات بنر
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label>عنوان</label>
                            <input type="text" value="{{ $banner->title }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>نوع بنر</label>
                            <input type="text" value="{{ $banner->type }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>اولویت</label>
                            <input type="text" value="{{ $banner->priority }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>وضعیت</label>
                            <input type="text" value="{{ $banner->is_active }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <label>متن</label>
                            <textarea class="form-control" disabled>{{ $banner->text }}</textarea>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>متن دکمه</label>
                            <input type="text" value="{{ $banner->button_text }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>لینک دکمه</label>
                            <input type="text" value="{{ $banner->button_link }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>آیکون دکمه</label>
                            <input type="text" value="{{ $banner->button_icon }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($banner->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($banner->updated_at) }}" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    تصویر بنر
                </div>
                <div class="card-body">
                    <div class="form-group col-12 col-lg-12">
                        <label>تصویر</label>
                        <img class="card-img" src="{{ url(env('BANNER_IMAGES_UPLOAD_PATH')) . '/' . $banner->image }}" alt="{{ $banner->title }}-image">
                    </div>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>
    </div>
@endsection
