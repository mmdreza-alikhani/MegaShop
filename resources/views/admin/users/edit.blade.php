@extends('admin.layout.master')

@section('title', 'ویرایش کاربر')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ویرایش کاربر</h3>
                    <p>{{ '@'.$user->username }} — {{ trim($user->first_name.' '.$user->last_name) ?: 'بدون نام' }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.orders.index', ['q' => $user->username]) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-receipt mr-1"></i> سفارش‌های کاربر
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            <div class="mk-dash-grid">
                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>اطلاعات حساب</h4>
                            <p>تغییرات پس از ذخیره اعمال می‌شود</p>
                        </div>
                    </header>

                    @include('admin.users.partials.form', ['user' => $user])
                </section>

                <aside class="mk-dash-side">
                    <section class="mk-adm-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>خلاصه کاربر</h4>
                                <p>وضعیت فعلی حساب</p>
                            </div>
                        </header>

                        <div class="mk-user-summary">
                            <img src="{{ Storage::url(config('upload.user_avatar_path').'/'.$user->avatar) }}"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                                 alt="{{ $user->username }}">

                            <div>
                                <strong>{{ trim($user->first_name.' '.$user->last_name) ?: $user->username }}</strong>
                                <small>{{ $user->email }}</small>
                            </div>
                        </div>

                        <ul class="mk-adm-list mk-adm-tips">
                            <li><span class="mk-adm-list-body"><strong>شناسه</strong><small>#{{ $user->id }}</small></span></li>
                            <li><span class="mk-adm-list-body"><strong>عضویت</strong><small>{{ verta($user->created_at)->format('%d %B %Y') }}</small></span></li>
                            <li>
                                <span class="mk-adm-list-body">
                                    <strong>نقش‌ها</strong>
                                    <small>{{ $user->roles->pluck('display_name')->filter()->implode('، ') ?: 'بدون نقش' }}</small>
                                </span>
                            </li>
                            <li>
                                <span class="mk-adm-list-body">
                                    <strong>سفارش‌ها</strong>
                                    <small>{{ number_format($user->orders()->count()) }} سفارش</small>
                                </span>
                            </li>
                        </ul>
                    </section>
                </aside>
            </div>
        </div>
    </main>
@endsection
