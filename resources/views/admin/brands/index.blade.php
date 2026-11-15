@extends('admin.layout.master')

@section('title', 'برندها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>برندها</h3>
                    <p>{{ number_format($brands->total()) }} برند ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> برند جدید
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-box-open mr-1"></i> محصولات
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-tags"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل برندها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">ثبت‌شده در فروشگاه</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل نمایش در سایت</span>
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
                        <h4>لیست برندها</h4>
                        <p>صفحه {{ $brands->currentPage() }} از {{ $brands->lastPage() }}</p>
                    </div>
                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">جستجو: {{ request('q') }}
                            <a href="{{ route('admin.brands.index') }}">&times;</a>
                        </span>
                    @endif
                </header>

                @if($brands->isEmpty())
                    <p class="mk-adm-empty">برندی با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان برند</th>
                                <th>محصولات</th>
                                <th>وضعیت</th>
                                <th>تاریخ ایجاد</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($brands as $key => $brand)
                                <tr>
                                    <td class="mk-muted">{{ $brands->firstItem() + $key }}</td>
                                    <td><strong class="mk-attr-title">{{ $brand->title }}</strong></td>
                                    <td>
                                        <span class="mk-pill {{ $brand->products_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($brand->products_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $brand->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                                            {{ $brand->is_active_label }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($brand->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات برند">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.brands.edit', ['brand' => $brand->id]) }}">
                                                    <i class="fas fa-pen"></i><span>ویرایش</span>
                                                </a>
                                                @if(Route::has('admin.brands.destroy'))
                                                    <form action="{{ route('admin.brands.destroy', ['brand' => $brand->id]) }}"
                                                          method="POST" class="m-0" onsubmit="return confirm('این برند حذف شود؟')">
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
                    <div class="mk-adm-pagination">{{ $brands->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection