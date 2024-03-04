<div class="nk-modal modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body login-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="ion-android-close"></span>
                </button>

                <h4 class="mb-0 text-right"><span class="text-main-1"></span>ورود</h4>
                <div class="nk-gap-1"></div>

                <div class="nk-gap-1"></div>
                <form method="POST" action="{{ route('home.login') }}" class="nk-form text-white" style="direction: rtl">
                    @csrf
                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <p class="text-right">مشخصات خود را وارد کنید:</p>

                            <input type="email" value="" name="email" class=" form-control" placeholder="ایمیل">

                            <div class="nk-gap"></div>
                            <input type="password" value="" name="password" class="required form-control" placeholder="رمز عبور">
                        </div>
                        <div class="col-md-6 text-right">
                            ورود با اکانت گوگل:

                            <div class="nk-gap"></div>

                            <a href="{{ route('home.redirectToProvider', ['provider' => 'google']) }}" class="btn btn-info btn-block">Google | <i class="fa fa-google-plus-circle"></i></a>

                            <div class="nk-gap-2"></div>
                            @error('email')
                            <div class="alert alert-danger text-center mt-1">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="nk-gap-1"></div>

                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <button href="#" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block" type="submit">
                                    ورود
                            </button>
                        </div>
                        <div class="col-md-6 text-right" style="direction: rtl">
                            <div class="mnt-5">
                                <small><a href="#" class="forget_password">رمز عبورت رو یادت نیست؟</a></small>
                            </div>
                            <div class="mnt-5">
                                <small>عضو نیستی؟</small>
                                <small><a href="#" class="register">ثبت نام</a></small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body register-modal" style="display: none">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="ion-android-close"></span>
                </button>

                <h4 class="mb-0 text-right"><span class="text-main-1"> ثبت</span> نام</h4>
                <div class="nk-gap-1"></div>
                <form action="{{ route('home.register') }}" method="post" class="nk-form text-white" style="direction: rtl">
                    @csrf
                    <div class="row vertical-gap">
                        <div class="col-md-12">

                            <p class="text-right">مشخصات خود را وارد کنید:</p>

                            <input type="text" value="{{ old('username') }}" name="username" class="form-control" placeholder="نام کاربری">
                            @error('username')
                            <div class="alert alert-danger text-center">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="nk-gap"></div>
                            <input type="email" value="{{ old('registerEmail') }}" name="registerEmail" class="form-control" placeholder="ایمیل">
                            @error('registerEmail')
                                <div class="alert alert-danger text-center">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="nk-gap"></div>
                            <input type="password" value="{{ old('registerPassword') }}" name="registerPassword" class="form-control" placeholder="رمز عبور">
                            @error('registerPassword')
                            <div class="alert alert-danger text-center">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="nk-gap"></div>
                            <input type="password" value="{{ old('password_confirmation') }}" name="password_confirmation" class="form-control" placeholder="تکرار رمز عبور">
                            @error('password_confirmation')
                            <div class="alert alert-danger text-center">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="nk-gap"></div>

                        </div>
                    </div>

                    <div class="nk-gap-1"></div>

                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <button class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block" type="submit">ثبت نام</button>
                        </div>
                        <div class="col-md-6 text-right" style="direction: rtl">
                            <a href="#" class="back_to_login nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-block">بازگشت</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body forget-password-modal" style="display: none">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="ion-android-close"></span>
                </button>

                <h4 class="mb-0 text-right"><span class="text-main-1">بازیابی </span>رمز عبور</h4>
                <div class="nk-gap-1"></div>

                <div class="nk-gap-1"></div>
                <form method="POST" action="{{ route('password.email') }}" class="nk-form text-white" style="direction: rtl">
                    @csrf
                    <div class="row vertical-gap">
                        <div class="col-md-12">
                            @if(session('status'))
                                <p class="text-success mb-3">{{ session('status')}}</p>
                            @endif

                            <p class="text-right">ایمیل خود را وارد کنید:</p>

                            <input type="email" name="email" class="form-control" placeholder="ایمیل خود را وارد کنید..">
                            <div class="nk-gap-2"></div>
                            @error('email')
                                <div class="alert alert-danger text-center">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="nk-gap"></div>

                        </div>
                    </div>

                    <div class="nk-gap-1"></div>

                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <button class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block" type="submit">ادامه</button>
                        </div>
                        <div class="col-md-6 text-right" style="direction: rtl">
                            <a href="#" class="back_to_login nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-block">بازگشت</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @if($errors->has('email') || $errors->has('password'))
        <script>
            $(function() {
                $('#modalLogin').modal({
                    show: true
                });
            });
        </script>
    @endif
    @if($errors->has('username') || $errors->has('registerEmail') || $errors->has('registerPassword') || $errors->has('password_confirmation'))
        <script>
            $(function() {
                $('#modalLogin').modal({
                    show: true
                });
            });
            $(function() {
                $(".register-modal").css("display", "block");
                $(".login-modal").css("display", "none");
            });
        </script>
    @endif
    @if(session('status'))
        @php
            toastr()->success(session('status'));
        @endphp
    @endif
@endsection


