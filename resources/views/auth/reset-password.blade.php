@extends('home.layout.master')

@section('title')
    بازنشانی کلمه عبور
@endsection

@section('content')
    <div class="container">
        <div class="nk-gap-2"></div>
        <div class="row vertical-gap">
            <div class="col-lg-12">
                <div class="row vertical-gap">
                    <div class="info-box p-4 m-2 h-100 w-100 row rounded">
                        <form method="POST" action="{{ route('password.update') }}" class="w-100 row p-5">
                            @csrf
                            <div class="col-12 col-lg-12 text-right" style="direction: rtl">
                                @include('admin.sections.errors')
                                <input type="hidden" name="email" value="{{ request()->email }}">
                                <input type="hidden" name="token" value="{{ request()->route('token') }}">
                                <label for="password">کلمه عبور جدید:</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="رمز عبور جدید خود را وارد کنید.." id="password" name="password" value="{{ old('password') }}">
                                </div>
                                <label for="password_confirmation">تکرار کلمه عبور جدید:</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="تکرار رمز عبور جدید خود را وارد کنید.." id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                </div>
                                <hr>
                                <button class="btn btn-success w-100" type="submit">ثبت</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="nk-gap-3"></div>
            </div>
        </div>
    </div>
    <div class="nk-gap-2"></div>
@endsection
