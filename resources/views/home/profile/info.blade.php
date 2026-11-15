@extends('home.profile.master')

@section('section')
    <div class="mk-panel">
        <header class="mk-panel-head">
            <div>
                <h2>مشخصات کاربری</h2>
                <p>اطلاعات حساب خود را کامل و به‌روز نگه دارید.</p>
            </div>
            <span class="mk-status is-ok">
                <span class="fa fa-check"></span>
                <span style="margin-inline-start:6px">{{ $user->email_verified_at ? 'ایمیل تایید شده' : 'ایمیل تایید نشده' }}</span>
            </span>
        </header>

        <form method="POST" action="{{ route('home.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            @include('home.sections.errors', ['errors' => $errors->updateProfile])

            <div class="mk-avatar-row">
                <img id="profileAvatarPreview"
                     src="{{ Storage::url(config('upload.user_avatar_path') . '/' . ($user->avatar ?: 'avatar.png')) }}"
                     onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                     alt="{{ $user->username }}">
                <div>
                    <strong>تصویر پروفایل</strong>
                    <small>تصویر را انتخاب کنید، خودتان برش و بزرگ‌نمایی می‌کنید. jpg، png یا webp — حداکثر ۵۰۰ کیلوبایت.</small>
                    <button type="button" class="mk-btn mk-btn-outline mk-btn-sm" id="avatarPickBtn" style="margin-top:8px">
                        <span class="fa fa-crop"></span> انتخاب و برش تصویر
                    </button>
                    <input id="avatarInput" type="file" accept="image/jpeg,image/png,image/webp"
                           data-skip-upload-limit hidden>
                    <small id="avatarStatus" class="mk-avatar-status" hidden></small>
                </div>
            </div>

            <div class="mk-form-grid">
                <div class="mk-field">
                    <label for="username">نام کاربری</label>
                    <div class="mk-field-control">
                        <span class="fa fa-user-o mk-field-icon"></span>
                        <input type="text" id="username" name="username" class="mk-input"
                               value="{{ old('username', $user->username) }}" placeholder="نام کاربری" required>
                    </div>
                </div>

                <div class="mk-field">
                    <label for="email">ایمیل</label>
                    <div class="mk-field-control mk-field-ltr">
                        <span class="fa fa-envelope-o mk-field-icon"></span>
                        <input type="email" id="email" name="email" class="mk-input" dir="ltr"
                               value="{{ old('email', $user->email) }}" placeholder="name@example.com" required>
                    </div>
                </div>

                <div class="mk-field">
                    <label for="first_name">نام</label>
                    <div class="mk-field-control">
                        <span class="fa fa-id-card-o mk-field-icon"></span>
                        <input type="text" id="first_name" name="first_name" class="mk-input"
                               value="{{ old('first_name', $user->first_name) }}" placeholder="نام">
                    </div>
                </div>

                <div class="mk-field">
                    <label for="last_name">نام خانوادگی</label>
                    <div class="mk-field-control">
                        <span class="fa fa-id-card-o mk-field-icon"></span>
                        <input type="text" id="last_name" name="last_name" class="mk-input"
                               value="{{ old('last_name', $user->last_name) }}" placeholder="نام خانوادگی">
                    </div>
                </div>

                <div class="mk-field">
                    <label for="phone_number">شماره موبایل</label>
                    <div class="mk-field-control mk-field-ltr">
                        <span class="fa fa-mobile mk-field-icon"></span>
                        <input type="tel" id="phone_number" name="phone_number" class="mk-input" dir="ltr"
                               value="{{ old('phone_number', $user->phone_number) }}"
                               placeholder="9121234567" minlength="10" maxlength="10">
                    </div>
                </div>

                <div class="mk-field">
                    <label for="member_since">تاریخ عضویت</label>
                    <div class="mk-field-control">
                        <span class="fa fa-calendar mk-field-icon"></span>
                        <input type="text" id="member_since" class="mk-input" disabled
                               value="{{ verta($user->created_at)->format('%d %B %Y') }}">
                    </div>
                </div>

                <div class="mk-form-actions">
                    <button type="submit" class="mk-btn mk-btn-primary">ذخیره تغییرات</button>
                    <a href="{{ route('home.profile.resetPassword') }}" class="mk-btn mk-btn-outline">تغییر رمز عبور</a>
                </div>
            </div>
        </form>

        @include('home.profile.partials.avatar-modal')
    </div>
@endsection
