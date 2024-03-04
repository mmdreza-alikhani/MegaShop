@extends('admin.layouts.master')
@section('title' , 'ایجاد برند')
@php
    $active_parent = 'brands';
    $active_child = 'makebrand'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.brands.store') }}" method="POST" class="row">
            @csrf
            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">عنوان*</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="slug" data-bs-toggle="tooltip" data-bs-placement="top" title="اختیاری">نام انگلیسی</label>
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}">
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="is_active">وضعیت انتشار:*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" selected>فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
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
                                <button class="btn btn-primary w-100" type="submit" name="submit">افزودن</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-danger w-100" type="cancel" name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
@endsection
