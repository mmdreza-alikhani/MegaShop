@extends('home.profile.master')

@section('section')
    <div class="info-box p-4 m-2 h-100 w-100 row rounded">
        <form method="POST" action="{{ route('verification.send') }}" class="w-100 row p-5">
            @csrf
            <div class="col-12 col-lg-12 text-right row" style="direction: rtl">
                @include('admin.sections.errors')
                <div class="input-group mb-3 col-12 col-lg-6">
                    <label for="email">ایمیل شما:</label>
                    <input type="email" class="form-control" style="background-color: transparent" id="email" value="{{ $user->email }}" name="email" disabled>
                </div>
                <div class="input-group mb-3 col-12 col-lg-6">
                    <p>
                        ایمیلی حاوی لینک تایید به ایمیل شما ارسال میشود. جهت تغییر ایمیل
                        <a href="{{ route('home.profile.info') }}">
                            کلیک کنید.
                        </a>
                    </p>
                </div>
                <div class="input-group mb-3 col-12 col-lg-6">
                    <button class="btn btn-outline-success" type="submit">
                        تایید ایمیل
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
