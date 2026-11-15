@extends('admin.layout.master')

@section('title', 'پلتفرم‌ها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>پلتفرم‌ها</h3>
                    <p>{{ number_format($platforms->total()) }} پلتفرم ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.platforms.create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> پلتفرم جدید
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-gamepad"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل پلتفرم‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">پلتفرم‌های ثبت‌شده</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل انتخاب در محصول</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-link"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">دارای محصول</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['with_products']) }}</strong>
                        <span class="mk-kpi-hint">حداقل یک محصول</span>
                    </div>
                </div>
                <div class="mk-kpi {{ $stats['empty'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-unlink"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">بدون محصول</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['empty']) }}</strong>
                        <span class="mk-kpi-hint">نیازمند بررسی</span>
                    </div>
                </div>
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست پلتفرم‌ها</h4>
                        <p>صفحه {{ $platforms->currentPage() }} از {{ $platforms->lastPage() }}</p>
                    </div>
                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">جستجو: {{ request('q') }}
                            <a href="{{ route('admin.platforms.index') }}">&times;</a>
                        </span>
                    @endif
                </header>

                @if($platforms->isEmpty())
                    <p class="mk-adm-empty">پلتفرمی با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>لوگو</th>
                                <th>عنوان</th>
                                <th>محصولات</th>
                                <th>وضعیت</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($platforms as $key => $platform)
                                <tr>
                                    <td class="mk-muted">{{ $platforms->firstItem() + $key }}</td>
                                    <td>
                                        <img class="mk-platform-logo"
                                             src="{{ $platform->image ? Storage::url(config('upload.platform_path', 'platforms/images') . '/' . $platform->image) : asset('assets/images/media-placeholder.svg') }}"
                                             onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                             alt="{{ $platform->title }}">
                                    </td>
                                    <td><strong class="mk-attr-title">{{ $platform->title }}</strong></td>
                                    <td>
                                        <span class="mk-pill {{ $platform->products_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($platform->products_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $platform->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                                            {{ $platform->is_active_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات پلتفرم">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.platforms.edit', ['platform' => $platform->id]) }}"><i class="fas fa-pen"></i><span>ویرایش</span></a>
                                                @if(Route::has('admin.platforms.destroy'))
                                                    <form action="{{ route('admin.platforms.destroy', ['platform' => $platform->id]) }}"
                                                          method="POST" class="m-0" onsubmit="return confirm('این پلتفرم حذف شود؟')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item is-danger">
                                                            <i class="fas fa-trash"></i><span>حذف</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mk-adm-pagination">{{ $platforms->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection