@extends('admin.layout.master')

@section('title', 'بنرها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>بنرها</h3>
                    <p>{{ number_format($banners->total()) }} بنر ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.banners.create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> بنر جدید
                    </a>
                    <a href="{{ route('home.index') }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-external-link-alt mr-1"></i> دیدن در سایت
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-images"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل بنرها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">در همه بخش‌ها</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل نمایش</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-sliders-h"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">اسلایدر اصلی</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['slider']) }}</strong>
                        <span class="mk-kpi-hint">صفحه اصلی</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-eye-slash"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">غیرفعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['inactive']) }}</strong>
                        <span class="mk-kpi-hint">پنهان از سایت</span>
                    </div>
                </div>
            </div>

            @if($banners->isEmpty())
                <section class="mk-adm-panel">
                    <p class="mk-adm-empty">هنوز بنری ثبت نشده است.</p>
                </section>
            @else
                <div class="mk-banner-grid">
                    @foreach($banners as $banner)
                        @php($isActive = (bool) $banner->getRawOriginal('is_active'))
                        <article class="mk-banner-card {{ $isActive ? '' : 'is-off' }}">
                            <div class="mk-banner-media">
                                <img src="{{ Storage::url(config('upload.banner_path') . '/' . $banner->image) }}"
                                     onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                     alt="{{ $banner->title }}" loading="lazy">

                                <span class="mk-banner-type">{{ $banner->type === 'main/top-slider' ? 'اسلایدر اصلی' : $banner->type }}</span>
                                <span class="mk-banner-priority">اولویت {{ $banner->priority }}</span>
                            </div>

                            <div class="mk-banner-body">
                                <h4>{{ $banner->title }}</h4>
                                <p>{{ Str::limit($banner->text, 90) }}</p>

                                <div class="mk-banner-meta">
                                    <span class="mk-pill {{ $isActive ? 'is-ok' : 'is-bad' }}">
                                        {{ $isActive ? 'فعال' : 'غیرفعال' }}
                                    </span>

                                    @if($banner->button_link)
                                        <span class="mk-banner-link" title="{{ $banner->button_link }}">
                                            <i class="fas fa-link"></i>
                                            {{ Str::limit($banner->button_link, 24) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mk-banner-actions">
                                    <a href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-pen mr-1"></i> ویرایش
                                    </a>
                                    <a href="{{ route('admin.banners.show', ['banner' => $banner->id]) }}" class="btn btn-light btn-sm">
                                        <i class="far fa-eye mr-1"></i> نمایش
                                    </a>

                                    @if(Route::has('admin.banners.destroy'))
                                        <form action="{{ route('admin.banners.destroy', ['banner' => $banner->id]) }}"
                                              method="POST" class="m-0"
                                              onsubmit="return confirm('این بنر حذف شود؟')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm mk-banner-delete">
                                                <i class="fas fa-trash mr-1"></i> حذف
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mk-adm-pagination">{{ $banners->links('vendor.pagination.bootstrap-4') }}</div>
            @endif
        </div>
    </main>
@endsection
