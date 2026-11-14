@extends('admin.layout.master')

@section('title', 'کاربر جدید')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>کاربر جدید</h3>
                    <p>یک حساب کاربری تازه برای فروشگاه بسازید</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت به لیست
                    </a>
                </div>
            </div>

            <div class="mk-dash-grid">
                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>اطلاعات حساب</h4>
                            <p>فیلدهای ستاره‌دار الزامی هستند</p>
                        </div>
                    </header>

                    @include('admin.users.partials.form', ['user' => null])
                </section>

                <aside class="mk-dash-side">
                    <section class="mk-adm-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>راهنما</h4>
                                <p>نکته‌های ساخت حساب</p>
                            </div>
                        </header>

                        <ul class="mk-adm-list mk-adm-tips">
                            <li>
                                <span class="mk-adm-list-body">
                                    <strong>نام کاربری</strong>
                                    <small>یکتا باشد و بعداً برای ورود استفاده می‌شود.</small>
                                </span>
                            </li>
                            <li>
                                <span class="mk-adm-list-body">
                                    <strong>رمز عبور</strong>
                                    <small>حداقل ۶ کاراکتر؛ بعداً از پروفایل قابل تغییر است.</small>
                                </span>
                            </li>
                            <li>
                                <span class="mk-adm-list-body">
                                    <strong>وضعیت</strong>
                                    <small>حساب غیرفعال نمی‌تواند وارد شود اما حذف نمی‌شود.</small>
                                </span>
                            </li>
                        </ul>
                    </section>
                </aside>
            </div>
        </div>
    </main>
@endsection
