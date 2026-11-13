@php
    $loginBag = $errors->getBag('login');
    $registerBag = $errors->getBag('register');

    $initialAuthTab = $registerBag->any() ? 'register' : 'login';
    $authShouldOpen = $loginBag->any() || $registerBag->any();
@endphp
<div class="nk-modal modal fade mk-auth-modal" id="modalLogin" tabindex="-1" role="dialog" aria-hidden="true"
     data-auth-initial="{{ $initialAuthTab }}" data-auth-open="{{ $authShouldOpen ? '1' : '0' }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content mk-auth">
            <button type="button" class="mk-auth-close" data-dismiss="modal" aria-label="بستن">&times;</button>

            <div class="mk-auth-head">
                <h3>ورود یا ثبت‌نام</h3>
                <p>برای ادامه خرید وارد شوید یا حساب بسازید.</p>
            </div>

            <div class="mk-auth-tabs" role="tablist" aria-label="ورود و ثبت‌نام">
                <button type="button" class="mk-auth-tab {{ $initialAuthTab === 'login' ? 'is-active' : '' }}"
                        data-auth-tab="login" role="tab"
                        aria-selected="{{ $initialAuthTab === 'login' ? 'true' : 'false' }}">
                    ورود
                </button>
                <button type="button" class="mk-auth-tab {{ $initialAuthTab === 'register' ? 'is-active' : '' }}"
                        data-auth-tab="register" role="tab"
                        aria-selected="{{ $initialAuthTab === 'register' ? 'true' : 'false' }}">
                    ثبت‌نام
                </button>
            </div>

            {{-- ورود --}}
            <div class="mk-auth-panel {{ $initialAuthTab === 'login' ? 'is-active' : '' }}" data-auth-panel="login">
                @include('home.sections.errors', ['errors' => $loginBag])

                <form method="POST" action="{{ route('login.store') }}" class="mk-auth-form">
                    @csrf

                    <div class="mk-field">
                        <label for="mk-login-email">ایمیل</label>
                        <div class="mk-field-control mk-field-ltr">
                            <span class="fa fa-envelope-o mk-field-icon"></span>
                            <input type="email" id="mk-login-email" class="mk-input" dir="ltr"
                                   value="{{ old('email') }}" placeholder="name@example.com"
                                   autocomplete="email" required name="email">
                        </div>
                    </div>

                    <div class="mk-field">
                        <label for="mk-login-password">رمز عبور</label>
                        <div class="mk-field-control">
                            <span class="fa fa-lock mk-field-icon"></span>
                            <input type="password" id="mk-login-password" class="mk-input" placeholder="رمز عبور"
                                   autocomplete="current-password" required name="password">
                            <button type="button" class="mk-field-toggle" data-pass-toggle="mk-login-password"
                                    aria-label="نمایش رمز عبور">
                                <span class="fa fa-eye"></span>
                            </button>
                        </div>
                    </div>

                    <div class="mk-auth-row">
                        <label class="mk-check">
                            <input type="checkbox" value="1" @checked(old('remember')) name="remember">
                            مرا به خاطر بسپار
                        </label>
                        <button type="button" class="mk-auth-link" data-auth-tab="forgot">
                            رمز عبور را فراموش کردم
                        </button>
                    </div>

                    <button type="submit" class="mk-btn mk-btn-primary mk-btn-block">ورود به حساب</button>

                    @if(config('demo.google_login_enabled'))
                        <div class="mk-auth-sep"><span>یا</span></div>
                        <a href="{{ route('home.redirectToProvider', ['provider' => 'google']) }}"
                           class="mk-btn mk-btn-outline mk-btn-block mk-btn-google">
                            <span class="fa fa-google"></span>
                            ورود با گوگل
                        </a>
                    @endif
                </form>

                <p class="mk-auth-foot">
                    حساب ندارید؟
                    <button type="button" class="mk-auth-link" data-auth-tab="register">همین حالا ثبت‌نام کنید</button>
                </p>
            </div>

            {{-- ثبت‌نام --}}
            <div class="mk-auth-panel {{ $initialAuthTab === 'register' ? 'is-active' : '' }}" data-auth-panel="register">
                @include('home.sections.errors', ['errors' => $registerBag])

                <form action="{{ route('register.store') }}" method="POST" class="mk-auth-form">
                    @csrf

                    <div class="mk-field">
                        <label for="mk-register-username">نام کاربری</label>
                        <div class="mk-field-control">
                            <span class="fa fa-user-o mk-field-icon"></span>
                            <input type="text" id="mk-register-username" class="mk-input"
                                   value="{{ old('username') }}" placeholder="نام کاربری" autocomplete="username"
                                   minlength="3" required name="username">
                        </div>
                    </div>

                    <div class="mk-field">
                        <label for="mk-register-email">ایمیل</label>
                        <div class="mk-field-control mk-field-ltr">
                            <span class="fa fa-envelope-o mk-field-icon"></span>
                            <input type="email" id="mk-register-email" class="mk-input" dir="ltr"
                                   value="{{ old('email') }}" placeholder="name@example.com"
                                   autocomplete="email" required name="email">
                        </div>
                    </div>

                    <div class="mk-field">
                        <label for="mk-register-password">رمز عبور</label>
                        <div class="mk-field-control">
                            <span class="fa fa-lock mk-field-icon"></span>
                            <input type="password" id="mk-register-password" class="mk-input"
                                   placeholder="حداقل ۸ کاراکتر" autocomplete="new-password" minlength="8" required
                                   name="password">
                            <button type="button" class="mk-field-toggle" data-pass-toggle="mk-register-password"
                                    aria-label="نمایش رمز عبور">
                                <span class="fa fa-eye"></span>
                            </button>
                        </div>
                    </div>

                    <div class="mk-field">
                        <label for="mk-register-password-confirm">تکرار رمز عبور</label>
                        <div class="mk-field-control">
                            <span class="fa fa-lock mk-field-icon"></span>
                            <input type="password" id="mk-register-password-confirm" class="mk-input"
                                   placeholder="تکرار رمز عبور" autocomplete="new-password" minlength="8" required
                                   name="password_confirmation">
                        </div>
                    </div>

                    <label class="mk-check mk-check-terms">
                        <input type="checkbox" required>
                        <span>با ثبت‌نام، قوانین و شرایط فروشگاه را می‌پذیرم.</span>
                    </label>

                    <button type="submit" class="mk-btn mk-btn-primary mk-btn-block">ساخت حساب</button>
                </form>

                <p class="mk-auth-foot">
                    قبلاً ثبت‌نام کرده‌اید؟
                    <button type="button" class="mk-auth-link" data-auth-tab="login">وارد شوید</button>
                </p>
            </div>

            {{-- بازیابی رمز --}}
            <div class="mk-auth-panel" data-auth-panel="forgot">
                @if(session('status'))
                    <div class="alert alert-success text-right">{{ session('status') }}</div>
                @endif

                @error('email')
                    <div class="alert alert-danger text-right">{{ $message }}</div>
                @enderror

                <form method="POST" action="{{ route('password.email') }}" class="mk-auth-form">
                    @csrf

                    <p class="mk-auth-hint">
                        ایمیل حساب خود را وارد کنید؛ لینک بازیابی برایتان ارسال می‌شود.
                    </p>

                    <div class="mk-field">
                        <label for="mk-forgot-email">ایمیل</label>
                        <div class="mk-field-control mk-field-ltr">
                            <span class="fa fa-envelope-o mk-field-icon"></span>
                            <input type="email" id="mk-forgot-email" class="mk-input" dir="ltr"
                                   value="{{ old('email') }}" placeholder="name@example.com"
                                   autocomplete="email" required name="email">
                        </div>
                    </div>

                    <button type="submit" class="mk-btn mk-btn-primary mk-btn-block">ارسال لینک بازیابی</button>
                    <button type="button" class="mk-btn mk-btn-outline mk-btn-block" data-auth-tab="login">
                        بازگشت به ورود
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('modalLogin');

            if (!modal || !window.jQuery) {
                return;
            }

            if (modal.getAttribute('data-auth-open') === '1') {
                window.jQuery('#modalLogin').modal('show');
            }
        });
    </script>
@endpush
