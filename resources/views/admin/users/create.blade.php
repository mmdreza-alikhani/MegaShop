@extends('admin.layouts.master')
@section('title' , 'ایجاد کاربر')
@php
    $active_parent = 'users';
    $active_child = 'makeuser'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.users.store') }}" method="POST" class="row">
            @csrf
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        افزودن کاربر
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label for="username">نام کاربری*</label>
                                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="first_name">نام</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="last_name">نام خانوادگی</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="email">ایمیل</label>
                                <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="password">رمز عبور</label>
                                <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}" required>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="phone_number">شماره تلفن</label>
                                <div class="input-group-prepend">
                                    <input type="tel" name="phone_number" id="phone_number" minlength="10" maxlength="10" class="form-control" value="{{ old('phone_number') }}">
                                    <div class="input-group-text">98+</div>
                                </div>
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
                                <a href="{{ route('admin.users.index') }}" class="btn btn-danger w-100" type="cancel" name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
