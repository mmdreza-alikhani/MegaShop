@extends('admin.layout.master')

@section('title', 'نمایش بنر')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>نمایش بنر</h3>
                    <p>{{ $banner->title }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-pen mr-1"></i> ویرایش
                    </a>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            <div class="mk-product-grid">
                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>پیش‌نمایش</h4>
                            <p>همان‌طور که در سایت دیده می‌شود</p>
                        </div>
                        <span class="mk-pill {{ $banner->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                            {{ $banner->is_active_label }}
                        </span>
                    </header>

                    <div class="mk-banner-preview">
                        <img src="{{ Storage::url(config('upload.banner_path') . '/' . $banner->image) }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                             alt="{{ $banner->title }}">

                        @if($banner->title || $banner->text || $banner->button_text)
                            <div class="mk-banner-caption">
                                @if($banner->title)<strong>{{ $banner->title }}</strong>@endif
                                @if($banner->text)<span>{{ $banner->text }}</span>@endif
                                @if($banner->button_text)
                                    <em>{{ $banner->button_text }}</em>
                                @endif
                            </div>
                        @endif
                    </div>
                </section>

                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>جزئیات</h4>
                            <p>اطلاعات ثبت‌شده برای این بنر</p>
                        </div>
                    </header>

                    <div class="mk-filter-row"><span>جایگاه</span><strong dir="ltr">{{ $banner->type }}</strong></div>
                    <div class="mk-filter-row"><span>اولویت</span><strong>{{ $banner->priority }}</strong></div>
                    <div class="mk-filter-row"><span>متن دکمه</span><strong>{{ $banner->button_text ?: '—' }}</strong></div>
                    <div class="mk-filter-row"><span>لینک دکمه</span><strong dir="ltr">{{ $banner->button_link ?: '—' }}</strong></div>
                    <div class="mk-filter-row"><span>تاریخ ایجاد</span><strong>{{ verta($banner->created_at)->format('%d %B %Y') }}</strong></div>
                </section>
            </div>
        </div>
    </main>
@endsection
