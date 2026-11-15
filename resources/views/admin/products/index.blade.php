@extends('admin.layout.master')

@section('title', 'محصولات')

@php
    $query = array_filter([
        'q' => request('q'),
        'category' => request('category'),
        'status' => request('status'),
        'sort' => request('sort'),
    ], fn ($v) => $v !== null && $v !== '');
@endphp

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>محصولات</h3>
                    <p>{{ number_format($products->total()) }} محصول در کاتالوگ</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> محصول جدید
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-layer-group mr-1"></i> دسته‌بندی‌ها
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-box-open"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل محصولات</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">در کاتالوگ فروشگاه</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل نمایش و خرید</span>
                    </div>
                </div>
                <div class="mk-kpi {{ $stats['out_of_stock'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">ناموجود</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['out_of_stock']) }}</strong>
                        <span class="mk-kpi-hint">موجودی صفر</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-tags"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">تخفیف‌دار</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['discounted']) }}</strong>
                        <span class="mk-kpi-hint">حداقل یک تنوع با تخفیف</span>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.products.index') }}" class="mk-adm-filters">
                <label class="mk-adm-field">
                    <span>دسته‌بندی</span>
                    <select name="category" onchange="this.form.submit()">
                        <option value="">همه دسته‌ها</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>وضعیت</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="active" @selected(request('status') === 'active')>فعال</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>غیرفعال</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>ترتیب</span>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="">جدیدترین</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>قدیمی‌ترین</option>
                        <option value="cheap" @selected(request('sort') === 'cheap')>ارزان‌ترین</option>
                        <option value="expensive" @selected(request('sort') === 'expensive')>گران‌ترین</option>
                    </select>
                </label>

                @if(request()->filled('q'))
                    <span class="mk-adm-filter-chip">
                        جستجو: {{ request('q') }}
                        <a href="{{ route('admin.products.index', array_diff_key($query, ['q' => ''])) }}">&times;</a>
                    </span>
                @endif

                @if(count($query) > 0)
                    <a href="{{ route('admin.products.index') }}" class="mk-adm-filter-clear">پاک کردن فیلترها</a>
                @endif
            </form>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست محصولات</h4>
                        <p>صفحه {{ $products->currentPage() }} از {{ $products->lastPage() }}</p>
                    </div>
                </header>

                @if($products->isEmpty())
                    <p class="mk-adm-empty">محصولی با این فیلترها پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0 mk-products-table">
                            <thead>
                            <tr>
                                <th>محصول</th>
                                <th>دسته</th>
                                <th>قیمت</th>
                                <th>موجودی</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                @php
                                    $variation = $product->variations->first();
                                    $stock = (int) $product->variations->sum('quantity');
                                    $isActive = (bool) $product->getRawOriginal('is_active');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="mk-user-cell">
                                            <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $product->primary_image) }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                                 alt="{{ $product->title }}">
                                            <span>
                                                <strong>{{ Str::limit($product->title, 42) }}</strong>
                                                <small>{{ $product->brand?->title ?? 'بدون برند' }}</small>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="mk-muted">{{ $product->category?->title ?? '—' }}</td>
                                    <td>
                                        @if($variation)
                                            {{ number_format($variation->price) }}
                                            <small class="mk-muted">تومان</small>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $stock > 0 ? 'is-ok' : 'is-bad' }}">
                                            {{ number_format($stock) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $isActive ? 'is-ok' : 'is-bad' }}">
                                            {{ $isActive ? 'فعال' : 'غیرفعال' }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($product->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات محصول">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.products.show', ['product' => $product->slug]) }}">
                                                    <i class="far fa-eye"></i><span>نمایش</span>
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.products.edit', ['product' => $product->slug]) }}">
                                                    <i class="fas fa-pen"></i><span>ویرایش</span>
                                                </a>
                                                <a class="dropdown-item" href="{{ route('home.products.show', ['product' => $product->slug]) }}"
                                                   target="_blank" rel="noopener">
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

                    <div class="mk-adm-pagination">{{ $products->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection
