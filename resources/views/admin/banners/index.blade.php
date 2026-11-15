@extends('admin.layout.master')

@section('title', 'بنرها')

@section('content')
    @php
        $query = array_filter([
            'q' => request('q'),
            'status' => request('status'),
            'type' => request('type'),
        ], fn ($value) => $value !== null && $value !== '');
    @endphp

    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>بنرها</h3>
                    <p>{{ number_format($banners->total()) }} بنر ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    @can('banners-create')
                        <a href="{{ route('admin.banners.create') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-plus mr-1"></i> بنر جدید
                        </a>
                    @endcan
                    <a href="{{ route('home.index') }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-external-link-alt mr-1"></i> مشاهده فروشگاه
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-images"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل بنرها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">در همه جایگاه‌ها</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-eye"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل نمایش در سایت</span>
                    </div>
                </div>
                <div class="mk-kpi {{ $stats['inactive'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-eye-slash"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">غیرفعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['inactive']) }}</strong>
                        <span class="mk-kpi-hint">پنهان از کاربران</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-layer-group"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">جایگاه‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['types']) }}</strong>
                        <span class="mk-kpi-hint">نوع بنر متفاوت</span>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.banners.index') }}" class="mk-adm-filters">
                <label class="mk-adm-field">
                    <span>وضعیت</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="1" @selected(request('status') === '1')>فعال</option>
                        <option value="0" @selected(request('status') === '0')>غیرفعال</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>جایگاه</span>
                    <select name="type" onchange="this.form.submit()">
                        <option value="">همه</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>

                @if(request()->filled('q'))
                    <span class="mk-adm-filter-chip">
                        جستجو: {{ request('q') }}
                        <a href="{{ route('admin.banners.index', array_diff_key($query, ['q' => ''])) }}">&times;</a>
                    </span>
                @endif

                @if(count($query) > 0)
                    <a href="{{ route('admin.banners.index') }}" class="mk-adm-filter-clear">پاک کردن فیلترها</a>
                @endif
            </form>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست بنرها</h4>
                        <p>صفحه {{ $banners->currentPage() }} از {{ $banners->lastPage() }}</p>
                    </div>
                </header>

                @if($banners->isEmpty())
                    <p class="mk-adm-empty">بنری با این فیلترها پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>تصویر</th>
                                <th>عنوان</th>
                                <th>جایگاه</th>
                                <th>اولویت</th>
                                <th>وضعیت</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banners as $key => $banner)
                                <tr>
                                    <td class="mk-muted">{{ $banners->firstItem() + $key }}</td>
                                    <td>
                                        <img class="mk-banner-thumb"
                                             src="{{ Storage::url(config('upload.banner_path') . '/' . $banner->image) }}"
                                             onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                                             alt="{{ $banner->title }}">
                                    </td>
                                    <td>
                                        <strong class="mk-attr-title">{{ $banner->title ?: 'بدون عنوان' }}</strong>
                                        @if($banner->text)
                                            <small class="mk-muted">{{ Str::limit($banner->text, 46) }}</small>
                                        @endif
                                    </td>
                                    <td><span class="mk-chip" dir="ltr">{{ $banner->type }}</span></td>
                                    <td class="mk-muted">{{ $banner->priority }}</td>
                                    <td>
                                        <span class="mk-pill {{ $banner->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                                            {{ $banner->is_active_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات بنر">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.banners.show', ['banner' => $banner->id]) }}">
                                                    <i class="far fa-eye"></i><span>نمایش</span>
                                                </a>
                                                @can('banners-edit')
                                                    <a class="dropdown-item" href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}">
                                                        <i class="fas fa-pen"></i><span>ویرایش</span>
                                                    </a>
                                                @endcan
                                                @can('banners-delete')
                                                    <form action="{{ route('admin.banners.destroy', ['banner' => $banner->id]) }}"
                                                          method="POST" class="m-0"
                                                          onsubmit="return confirm('این بنر حذف شود؟')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item is-danger">
                                                            <i class="fas fa-trash"></i><span>حذف</span>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mk-adm-pagination">{{ $banners->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection
