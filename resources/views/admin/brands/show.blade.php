@extends('admin.layouts.master')
@section('title')
    برند : {{ $brand->name }}
@endsection
@php
    $active_parent = 'brands';
    $active_child = ''
@endphp
@section('content')
    <div class="m-sm-2 mx-4 ">
            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        نمایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label>عنوان</label>
                                <input type="text" value="{{ $brand->name }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>اسلاگ</label>
                                <input type="text" value="{{ $brand->slug }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>وضعیت</label>
                                <input type="text" value="{{ $brand->is_active }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>تاریخ ایجاد</label>
                                <input type="text" value="{{ verta($brand->created_at) }}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label>تاریخ آخرین تغییرات</label>
                                <input type="text" value="{{ verta($brand->updated_at) }}" class="form-control" disabled>
                            </div>
                        </div>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-danger">بازگشت</a>
                    </div>
                </div>
            </div>

            </div>
    </div>
@endsection
