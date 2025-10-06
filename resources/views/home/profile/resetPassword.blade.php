@extends('home.profile.master')

@section('section')
    <div class="info-box p-4 m-2 h-100 w-100 row rounded">
        <form method="POST" action="{{ route('home.profile.resetPasswordCheck') }}" class="w-100 row p-5" enctype="multipart/form-data">
        @csrf
            <div class="col-12 col-lg-12 text-right" style="direction: rtl">
                @include('admin.sections.errors')
                @if(auth()->user()->provider_name != 'google')
                    <label for="currentPassword">کلمه عبور فعلی:</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="رمز عبور فعلی خود را وارد کنید.." id="currentPassword" name="currentPassword" value="{{ old('currentPassword') }}" required>
                    </div>
                @endif
                <label for="newPassword">کلمه عبور جدید:</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="رمز عبور جدید خود را وارد کنید.." id="newPassword" name="newPassword" value="{{ old('newPassword') }}" required>
                </div>
                <label for="confirmNewPassword">تکرار کلمه عبور جدید:</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="رمز عبور جدید خود را وارد کنید.." id="confirmNewPassword" name="confirmNewPassword" value="{{ old('confirmNewPassword') }}" required>
                </div>
                <hr>
                <button class="btn btn-success w-100" type="submit">ثبت</button>
            </div>
        </form>
    </div>
@endsection
