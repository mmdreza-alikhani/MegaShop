@extends('admin.layout.master')

@section('title', 'کدهای تخفیف')

@section('content')
    @php
        $query = array_filter([
            'q' => request('q'),
            'type' => request('type'),
            'status' => request('status'),
            'sort' => request('sort'),
        ], fn ($value) => $value !== null && $value !== '');
    @endphp

    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>کدهای تخفیف</h3>
                    <p>{{ number_format($coupons->total()) }} کد تخفیف ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    @can('coupons-create')
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-plus mr-1"></i> کد تخفیف جدید
                        </a>
                    @endcan
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-receipt mr-1"></i> سفارش‌ها
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-ticket-alt"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل کدها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">ثبت‌شده در فروشگاه</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">فعال</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['active']) }}</strong>
                        <span class="mk-kpi-hint">قابل استفاده در خرید</span>
                    </div>
                </div>
                <div class="mk-kpi {{ $stats['expired'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-hourglass-end"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">منقضی‌شده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['expired']) }}</strong>
                        <span class="mk-kpi-hint">تاریخ پایان گذشته</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-shopping-bag"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">استفاده‌شده</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['used']) }}</strong>
                        <span class="mk-kpi-hint">حداقل یک سفارش</span>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.coupons.index') }}" class="mk-adm-filters">
                <label class="mk-adm-field">
                    <span>نوع</span>
                    <select name="type" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="amount" @selected(request('type') === 'amount')>مبلغی</option>
                        <option value="percentage" @selected(request('type') === 'percentage')>درصدی</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>وضعیت</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="active" @selected(request('status') === 'active')>فعال</option>
                        <option value="expired" @selected(request('status') === 'expired')>منقضی</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>غیرفعال</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>ترتیب</span>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="">جدیدترین</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>قدیمی‌ترین</option>
                        <option value="expiry" @selected(request('sort') === 'expiry')>نزدیک‌ترین انقضا</option>
                        <option value="used" @selected(request('sort') === 'used')>بیشترین استفاده</option>
                    </select>
                </label>

                @if(request()->filled('q'))
                    <span class="mk-adm-filter-chip">
                        جستجو: {{ request('q') }}
                        <a href="{{ route('admin.coupons.index', array_diff_key($query, ['q' => ''])) }}"
                           aria-label="حذف جستجو">&times;</a>
                    </span>
                @endif

                @if(count($query) > 0)
                    <a href="{{ route('admin.coupons.index') }}" class="mk-adm-filter-clear">پاک کردن فیلترها</a>
                @endif
            </form>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست کدهای تخفیف</h4>
                        <p>صفحه {{ $coupons->currentPage() }} از {{ $coupons->lastPage() }}</p>
                    </div>
                </header>

                @if($coupons->isEmpty())
                    <p class="mk-adm-empty">کد تخفیفی با این فیلترها پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان و کد</th>
                                <th>نوع</th>
                                <th>مقدار</th>
                                <th>انقضا</th>
                                <th>استفاده</th>
                                <th>وضعیت</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupons as $key => $coupon)
                                @php
                                    $isExpired = $coupon->expired_at && $coupon->expired_at->isPast();
                                    $isActive = (bool) $coupon->getRawOriginal('is_active');
                                @endphp
                                <tr>
                                    <td class="mk-muted">{{ $coupons->firstItem() + $key }}</td>
                                    <td>
                                        <strong class="mk-attr-title">{{ $coupon->title }}</strong>
                                        <span class="mk-code" dir="ltr">{{ $coupon->code }}</span>
                                    </td>
                                    <td>
                                        <span class="mk-chip">{{ $coupon->getRawOriginal('type') === 'amount' ? 'مبلغی' : 'درصدی' }}</span>
                                    </td>
                                    <td>
                                        @if($coupon->getRawOriginal('type') === 'amount')
                                            {{ number_format((int) $coupon->amount) }}
                                            <small class="mk-muted">تومان</small>
                                        @else
                                            {{ number_format((int) $coupon->percentage) }}٪
                                            @if($coupon->max_percentage_amount)
                                                <small class="mk-muted">تا {{ number_format((int) $coupon->max_percentage_amount) }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="mk-muted">
                                        {{ $coupon->expired_at ? verta($coupon->expired_at)->format('%d %B %Y') : '—' }}
                                    </td>
                                    <td>
                                        <span class="mk-pill {{ $coupon->orders_count > 0 ? 'is-ok' : 'is-wait' }}">
                                            {{ number_format($coupon->orders_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(! $isActive)
                                            <span class="mk-pill is-bad">غیرفعال</span>
                                        @elseif($isExpired)
                                            <span class="mk-pill is-wait">منقضی</span>
                                        @else
                                            <span class="mk-pill is-ok">فعال</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات کد تخفیف">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                @can('coupons-edit')
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.coupons.edit', ['coupon' => $coupon->id]) }}">
                                                        <i class="fas fa-pen"></i><span>ویرایش</span>
                                                    </a>
                                                @endcan
                                                <a class="dropdown-item" href="{{ route('admin.orders.index', ['q' => $coupon->code]) }}">
                                                    <i class="fas fa-receipt"></i><span>سفارش‌های این کد</span>
                                                </a>
                                                @can('coupons-delete')
                                                    <form action="{{ route('admin.coupons.destroy', ['coupon' => $coupon->id]) }}"
                                                          method="POST" class="m-0"
                                                          onsubmit="return confirm('این کد تخفیف حذف شود؟')">
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

                    <div class="mk-adm-pagination">{{ $coupons->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection
