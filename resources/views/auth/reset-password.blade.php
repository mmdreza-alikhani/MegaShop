@extends('home.layout.master')

@section('title')
    بازنشانی کلمه عبور
@endsection

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <div class="row justify-content-center" style="height: 60vh; align-items: center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="info-box m-2 rounded h-100">
                        <form method="POST" action="{{ route('password.update') }}" class="p-5 h-100">
                            @csrf
                            <div class="col-12 text-right" style="direction: rtl">
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
            </div>
        </div>
    </div>
@endsection
