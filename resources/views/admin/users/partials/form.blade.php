@php
    $isEdit = isset($user) && $user;
    $action = $isEdit ? route('admin.users.update', ['user' => $user->id]) : route('admin.users.store');
    $errorBag = $isEdit ? 'update' : 'store';
@endphp

<form method="POST" action="{{ $action }}" class="mk-user-form">
    @csrf
    @if($isEdit)
        @method('PATCH')
    @endif

    @include('admin.layout.errors', ['errors' => $errors->{$errorBag}])

    <div class="mk-form-grid">
        <div class="mk-field">
            <label for="username">نام کاربری <em>*</em></label>
            <input type="text" name="username" id="username" class="form-control"
                   value="{{ old('username', $user->username ?? '') }}" required autocomplete="off">
            <small>حروف کوچک انگلیسی، عدد و نقطه.</small>
        </div>

        <div class="mk-field">
            <label for="email">ایمیل <em>*</em></label>
            <input type="email" name="email" id="email" class="form-control" dir="ltr"
                   value="{{ old('email', $user->email ?? '') }}" required autocomplete="off">
        </div>

        <div class="mk-field">
            <label for="first_name">نام</label>
            <input type="text" name="first_name" id="first_name" class="form-control"
                   value="{{ old('first_name', $user->first_name ?? '') }}">
        </div>

        <div class="mk-field">
            <label for="last_name">نام خانوادگی</label>
            <input type="text" name="last_name" id="last_name" class="form-control"
                   value="{{ old('last_name', $user->last_name ?? '') }}">
        </div>

        <div class="mk-field">
            <label for="phone_number">شماره موبایل</label>
            <div class="mk-input-group">
                <span class="mk-input-prefix" dir="ltr">+98</span>
                <input type="tel" name="phone_number" id="phone_number" class="form-control" dir="ltr"
                       minlength="10" maxlength="10" placeholder="9121234567"
                       value="{{ old('phone_number', $user->phone_number ?? '') }}">
            </div>
            <small>بدون صفر ابتدایی؛ ۱۰ رقم.</small>
        </div>

        <div class="mk-field">
            <label for="password">
                رمز عبور
                @if($isEdit)
                    <span class="mk-field-hint">(خالی = بدون تغییر)</span>
                @else
                    <em>*</em>
                @endif
            </label>
            <input type="password" name="password" id="password" class="form-control"
                   autocomplete="new-password" @required(! $isEdit)
            <small>حداقل ۶ کاراکتر.</small>
        </div>

        <div class="mk-field">
            <label for="is_active">وضعیت حساب</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" @selected((string) old('is_active', $user ? $user->getRawOriginal('is_active') : '1') === '1')>
                    فعال — امکان ورود دارد
                </option>
                <option value="0" @selected((string) old('is_active', $user ? $user->getRawOriginal('is_active') : '1') === '0')>
                    غیرفعال — ورود مسدود است
                </option>
            </select>
        </div>

        <div class="mk-field">
            <label for="created_at">تاریخ عضویت</label>
            <input type="text" id="created_at" class="form-control" disabled
                   value="{{ $isEdit ? verta($user->created_at)->format('%d %B %Y') : 'پس از ساخت ثبت می‌شود' }}">
        </div>
    </div>

    <div class="mk-form-actions">
        <button type="submit" class="btn btn-info">
            {{ $isEdit ? 'ذخیره تغییرات' : 'ایجاد کاربر' }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">انصراف</a>
    </div>
</form>
