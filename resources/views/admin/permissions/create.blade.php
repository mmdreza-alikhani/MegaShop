@extends('admin.layout.master')
@section('title' , 'ایجاد مجوز')
@php
    $active_parent = 'users';
    $active_child = 'permissions'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.permissions.store') }}" method="POST" class="row">
            @csrf
            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">نام*</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-12 col-lg-6">
                                <label for="displayName">نام نمایشی*</label>
                                <input type="text" name="displayName" id="displayName" class="form-control"
                                       value="{{ old('displayName') }}">
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
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-danger w-100"
                                   type="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
