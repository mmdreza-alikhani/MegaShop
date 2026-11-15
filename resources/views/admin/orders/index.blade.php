@extends('admin.layout.master')

@section('title', 'سفارشات')

@section('content')
    @php
        $query = array_filter([
            'q' => request('q'),
            'status' => request('status'),
            'payment' => request('payment'),
            'sort' => request('sort'),
        ], fn ($value) => $value !== null && $value !== '');

        $pillClass = fn (int $status) => match ($status) {
            -1 => 'is-bad',
            0 => 'is-wait',
            1 => 'is-info',
            2 => 'is-info',
            3 => 'is-ok',
            default => 'is-wait',
        };
    @endphp

    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>سفارشات</h3>
                    <p>{{ number_format($orders->total()) }} سفارش ثبت شده است</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.orders.index', ['status' => 0]) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-clock mr-1"></i> در انتظار پرداخت
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-ticket-alt mr-1"></i> کدهای تخفیف
                    </a>
                </div>
            </div>

            <div class="mk-kpi-grid mk-kpi-grid--4">
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-receipt"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">کل سفارش‌ها</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['total']) }}</strong>
                        <span class="mk-kpi-hint">از ابتدا تاکنون</span>
                    </div>
                </div>
                <div class="mk-kpi {{ $stats['pending'] > 0 ? 'is-alert' : '' }}">
                    <span class="mk-kpi-icon"><i class="fas fa-hourglass-half"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">در انتظار پرداخت</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['pending']) }}</strong>
                        <span class="mk-kpi-hint">نیازمند پیگیری</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-box-open"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">در حال پردازش</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['processing']) }}</strong>
                        <span class="mk-kpi-hint">پرداخت‌شده</span>
                    </div>
                </div>
                <div class="mk-kpi">
                    <span class="mk-kpi-icon"><i class="fas fa-wallet"></i></span>
                    <div class="mk-kpi-body">
                        <span class="mk-kpi-label">درآمد</span>
                        <strong class="mk-kpi-value">{{ number_format($stats['revenue']) }}</strong>
                        <span class="mk-kpi-hint">تومان — سفارش‌های پرداخت‌شده</span>
                    </div>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}" class="mk-adm-filters">
                <label class="mk-adm-field">
                    <span>وضعیت سفارش</span>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">همه</option>
                        @foreach(\App\Models\Order::statuses() as $value => $label)
                            <option value="{{ $value }}" @selected((string) request('status') === (string) $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>پرداخت</span>
                    <select name="payment" onchange="this.form.submit()">
                        <option value="">همه</option>
                        <option value="1" @selected(request('payment') === '1')>پرداخت شده</option>
                        <option value="0" @selected(request('payment') === '0')>پرداخت نشده</option>
                    </select>
                </label>

                <label class="mk-adm-field">
                    <span>ترتیب</span>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="">جدیدترین</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>قدیمی‌ترین</option>
                        <option value="amount" @selected(request('sort') === 'amount')>بیشترین مبلغ</option>
                    </select>
                </label>

                @if(request()->filled('q'))
                    <span class="mk-adm-filter-chip">
                        جستجو: {{ request('q') }}
                        <a href="{{ route('admin.orders.index', array_diff_key($query, ['q' => ''])) }}">&times;</a>
                    </span>
                @endif

                @if(count($query) > 0)
                    <a href="{{ route('admin.orders.index') }}" class="mk-adm-filter-clear">پاک کردن فیلترها</a>
                @endif
            </form>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>لیست سفارشات</h4>
                        <p>صفحه {{ $orders->currentPage() }} از {{ $orders->lastPage() }}</p>
                    </div>
                </header>

                @if($orders->isEmpty())
                    <p class="mk-adm-empty">سفارشی با این فیلترها پیدا نشد.</p>
                @else
                    <div class="mk-adm-table-wrap">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th>شماره</th>
                                <th>کاربر</th>
                                <th>مبلغ پرداختی</th>
                                <th>وضعیت</th>
                                <th>پرداخت</th>
                                <th>تاریخ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                @php($status = (int) $order->getRawOriginal('status'))
                                <tr>
                                    <td>
                                        <strong class="mk-attr-title" dir="ltr">#{{ $order->id }}</strong>
                                        @if($order->coupon)
                                            <span class="mk-pill is-role" dir="ltr">{{ $order->coupon->code }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mk-user-cell">
                                            <span>
                                                <strong>{{ $order->user?->username ? '@' . $order->user->username : 'کاربر حذف‌شده' }}</strong>
                                                <small>{{ $order->items_count ?? $order->items()->count() }} قلم کالا</small>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ number_format((int) $order->paying_amount) }}</strong>
                                        <small class="mk-muted">تومان</small>
                                    </td>
                                    <td><span class="mk-pill {{ $pillClass($status) }}">{{ \App\Models\Order::statusLabel($status) }}</span></td>
                                    <td>
                                        <span class="mk-pill {{ $order->getRawOriginal('payment_status') ? 'is-ok' : 'is-wait' }}">
                                            {{ $order->getRawOriginal('payment_status') ? 'پرداخت شده' : 'پرداخت نشده' }}
                                        </span>
                                    </td>
                                    <td class="mk-muted">{{ verta($order->created_at)->format('%d %B %Y') }}</td>
                                    <td>
                                        <div class="mk-adm-drop" data-adm-drop>
                                            <button type="button" class="mk-adm-icon-link" data-adm-drop-trigger
                                                    aria-haspopup="true" aria-expanded="false" aria-label="تنظیمات سفارش">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="mk-adm-dropdown" data-adm-drop-menu>
                                                <a class="dropdown-item" href="{{ route('admin.orders.show', ['order' => $order->id]) }}">
                                                    <i class="far fa-eye"></i><span>جزئیات سفارش</span>
                                                </a>
                                                @if($order->user)
                                                    <a class="dropdown-item" href="{{ route('admin.users.edit', ['user' => $order->user->id]) }}">
                                                        <i class="fas fa-user"></i><span>پروفایل کاربر</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mk-adm-pagination">{{ $orders->links('vendor.pagination.bootstrap-4') }}</div>
                @endif
            </section>
        </div>
    </main>
@endsection
