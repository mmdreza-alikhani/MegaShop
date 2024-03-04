@extends('admin.layouts.master')
@section('title')
    پلتفرم : {{ $platform->name }}
@endsection
@php
    $active_parent = 'platforms';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 row">
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    نمایش
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label>عنوان</label>
                            <input type="text" value="{{ $platform->name }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>اسلاگ</label>
                            <input type="text" value="{{ $platform->slug }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>وضعیت</label>
                            <input type="text" value="{{ $platform->is_active }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ ایجاد</label>
                            <input type="text" value="{{ verta($platform->created_at) }}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>تاریخ آخرین تغییرات</label>
                            <input type="text" value="{{ verta($platform->updated_at) }}" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    تصویر
                </div>
                <div class="card-body">
                    <div class="form-group col-12 col-lg-12">
                        <label>تصویر</label>
                        <img class="card-img" src="{{ url(env('CATEGORY_IMAGE_PATH')) . '/' . $platform->image }}" alt="{{ $platform->name }}-image">
                    </div>
                    <a href="{{ route('admin.platforms.index') }}" class="btn btn-danger">بازگشت</a>
                </div>
            </div>
        </div>
    </div>
@endsection
