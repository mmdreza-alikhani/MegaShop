@extends('admin.layout.master')

@section('title', 'دسته‌بندی‌ها')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>دسته‌بندی‌ها</h3>
                    <p>{{ number_format($categories->total()) }} دسته‌بندی در این صفحه</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> دسته جدید
                    </a>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-star mr-1"></i> ویژگی‌ها
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-layer-group"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل دسته‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">درخت دسته‌بندی</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-sitemap"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">دسته‌های اصلی</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['parents']) }}</strong>
                        <span class="mk-kpi-hint">سطح اول</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-code-branch"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">زیردسته‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['children']) }}</strong>
                        <span class="mk-kpi-hint">سطح دوم</span>
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
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست دسته‌بندی‌ها</h4>
                        <p>صفحه {{ $categories->currentPage() }} از {{ $categories->lastPage() }}</p>
                    </div>
                    @if(request()->filled('q'))
                        <span class="mk-adm-filter-chip">جستجو: {{ request('q') }}
                            <a href="{{ route('admin.categories.index') }}">&times;</a>
                        </span>
                    @endif
                </header>

                @if($categories->isEmpty())
                    <p class="mk-adm-empty">دسته‌ای با این شرایط پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان</th>
                                <th>والد</th>
                                <th>زیردسته</th>
                                <th>محصولات</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $key => $category)
                                <tr>
                                    <td class="mk-muted">{{ $categories->firstItem() + $key }}</td>
                                    <td>
                                        <strong class="mk-attr-title">{{ $category->title }}</strong>
                                        <span class="mk-role-name">{{ $category->slug }}</span>
                                    </td>
                                    <td class="mk-muted">{{ $category->parent?->title ?? '—' }}</td>
                                    <td>
                                        <span class="mk-pill {{ $category->children_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($category->children_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $category->products_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($category->products_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $category->getRawOriginal('is_active') ? 'is-ok' : 'is-bad' }}">
                                            {{ $category->is_active }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($category->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات دسته">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.categories.edit', ['category' => $category->id]) }}">
                                                    <i class="fas fa-pen"></i><span>ویرایش</span>
                                                </a>
                                                @if(Route::has('admin.categories.show'))
                                                    <a class="dropdown-item" href="{{ route('admin.categories.show', ['category' => $category->id]) }}">
                                                        <i class="far fa-eye"></i><span>نمایش</span>
                                                    </a>
                                                @endif
                                                <a class="dropdown-item" href="{{ url('/categories/' . $category->slug) }}" target="_blank" rel="noopener">
                                                    <i class="fas fa-external-link-alt"></i><span>در فروشگاه</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mk-adm-pagination">{{ $categories->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection