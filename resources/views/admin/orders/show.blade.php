@extends('admin.layout.master')

@section('title', 'سفارش #' . $order->id)

@php
    $status = (int) $order->getRawOriginal('status');
    $isPaid = (bool) $order->getRawOriginal('payment_status');
    $statusClass = match ($status) {
        -1 => 'is-bad',
        0 => 'is-wait',
        3 => 'is-ok',
        default => 'is-info',
    };
@endphp

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>سفارش #{{ $order->id }}</h3>
                    <p>{{ verta($order->created_at)->format('%d %B %Y — H:i') }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            <section class="mk-order-hero">
                <div class="mk-order-hero-main">
                    <div class="mk-order-badges">
                        <span class="mk-pill {{ $statusClass }}">{{ \App\Models\Order::statusLabel($status) }}</span>
                        <span class="mk-pill {{ $isPaid ? 'is-ok' : 'is-wait' }}">
                            {{ $isPaid ? 'پرداخت شده' : 'پرداخت نشده' }}
                        </span>
                        @if($order->payment_type)
                            <span class="mk-chip">{{ $order->payment_type }}</span>
                        @endif
                        @if($order->coupon)
                            <span class="mk-chip" dir="ltr">{{ $order->coupon->code }}</span>
                        @endif
                    </div>

                    <strong class="mk-order-total">{{ number_format((int) $order->paying_amount) }}</strong>
                    <span class="mk-order-total-unit">تومان — مبلغ پرداختی</span>
                </div>

                <div class="mk-order-hero-side">
                    <div class="mk-stat">
                        <span>کاربر</span>
                        <strong>
                            @if($order->user)
                                <a class="mk-adm-link" href="{{ route('admin.users.edit', ['user' => $order->user->id]) }}">
                                    {{ '@' . $order->user->username }}
                                </a>
                            @else
                                —
                            @endif
                        </strong>
                    </div>
                    <div class="mk-stat">
                        <span>اقلام</span>
                        <strong>{{ number_format($order->items->count()) }}</strong>
                    </div>
                    <div class="mk-stat">
                        <span>هزینه ارسال</span>
                        <strong>{{ number_format((int) $order->delivery_amount) }}</strong>
                    </div>
                    <div class="mk-stat">
                        <span>تخفیف</span>
                        <strong>{{ number_format((int) $order->coupon_amount) }}</strong>
                    </div>
                </div>
            </section>

            <div class="mk-product-grid">
                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>اقلام سفارش</h4>
                            <p>{{ number_format($order->items->count()) }} قلم کالا</p>
                        </div>
                    </header>

                    <div class="mk-var-table">
                        <table class="table mb-0">
                            <thead>
                            <tr>
                                <th>محصول</th>
                                <th>تنوع</th>
                                <th>قیمت واحد</th>
                                <th>تعداد</th>
                                <th>جمع</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @if($item->product)
                                            <a class="mk-adm-link" href="{{ route('admin.products.show', ['product' => $item->product->slug]) }}">
                                                {{ Str::limit($item->product->title, 48) }}
                                            </a>
                                        @else
                                            <span class="mk-muted">محصول حذف شده</span>
                                        @endif
                                    </td>
                                    <td class="mk-muted">{{ $item->productVariation?->value ?? '—' }}</td>
                                    <td>{{ number_format((int) $item->price) }}</td>
                                    <td>{{ number_format((int) $item->quantity) }}</td>
                                    <td><strong>{{ number_format((int) $item->subtotal) }}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <ul class="mk-order-totals">
                        <li><span>جمع کالاها</span><strong>{{ number_format((int) $order->total_amount) }} تومان</strong></li>
                        <li><span>هزینه ارسال</span><strong>{{ number_format((int) $order->delivery_amount) }} تومان</strong></li>
                        @if((int) $order->coupon_amount > 0)
                            <li class="is-discount"><span>تخفیف</span><strong>− {{ number_format((int) $order->coupon_amount) }} تومان</strong></li>
                        @endif
                        <li class="is-total"><span>مبلغ پرداختی</span><strong>{{ number_format((int) $order->paying_amount) }} تومان</strong></li>
                    </ul>
                </section>

                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>تغییر وضعیت</h4>
                            <p>وضعیت جدید در تاریخچه سفارش ثبت می‌شود</p>
                        </div>
                    </header>

                    <form action="{{ route('admin.orders.status', ['order' => $order->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mk-field">
                            <label for="status">وضعیت جدید</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected($status === (int) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mk-field">
                            <label for="note">یادداشت (اختیاری)</label>
                            <input type="text" name="note" id="note" class="form-control" maxlength="255"
                                   placeholder="مثلاً: ارسال با پست پیشتاز">
                        </div>

                        <button type="submit" class="btn btn-info btn-block mt-2">
                            <i class="fas fa-sync-alt"></i> ثبت تغییر وضعیت
                        </button>
                    </form>
                </section>

                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>اطلاعات پرداخت</h4>
                            <p>تراکنش و وضعیت مالی سفارش</p>
                        </div>
                    </header>

                    @if($transaction)
                        <div class="mk-filter-row"><span>درگاه</span><strong>{{ strtoupper($transaction->gateway_name) }}</strong></div>
                        <div class="mk-filter-row"><span>مبلغ</span><strong>{{ number_format((int) $transaction->amount) }} تومان</strong></div>
                        <div class="mk-filter-row"><span>کد پیگیری</span><strong dir="ltr">{{ $transaction->ref_id ?: '—' }}</strong></div>
                        <div class="mk-filter-row"><span>شماره کارت</span><strong dir="ltr">{{ $transaction->card_pan ?: '—' }}</strong></div>
                        <div class="mk-filter-row">
                            <span>وضعیت</span>
                            <strong>{{ $transaction->status ? 'موفق' : 'ناموفق' }}</strong>
                        </div>
                    @else
                        <p class="mk-adm-empty">تراکنشی برای این سفارش ثبت نشده است.</p>
                    @endif
                </section>

                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>آدرس تحویل</h4>
                            <p>اطلاعات گیرنده سفارش</p>
                        </div>
                    </header>

                    @if($order->address)
                        <div class="mk-filter-row"><span>عنوان</span><strong>{{ $order->address->title }}</strong></div>
                        <div class="mk-filter-row"><span>استان / شهر</span>
                            <strong>{{ $order->address->province?->name ?? '—' }} — {{ $order->address->city?->name ?? '—' }}</strong>
                        </div>
                        <div class="mk-filter-row"><span>نشانی</span><strong>{{ $order->address->address }}</strong></div>
                        <div class="mk-filter-row"><span>کد پستی</span><strong dir="ltr">{{ $order->address->postal_code }}</strong></div>
                        <div class="mk-filter-row"><span>تلفن</span><strong dir="ltr">{{ $order->address->phone_number }}</strong></div>
                    @else
                        <p class="mk-adm-empty">آدرسی برای این سفارش ثبت نشده است.</p>
                    @endif
                </section>

                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>تاریخچه وضعیت</h4>
                            <p>{{ number_format($order->histories->count()) }} تغییر ثبت شده است</p>
                        </div>
                    </header>

                    @if($order->histories->isEmpty())
                        <p class="mk-adm-empty">تاریخچه‌ای برای این سفارش ثبت نشده است.</p>
                    @else
                        <ol class="mk-timeline">
                            @foreach($order->histories->sortByDesc('created_at') as $history)
                                <li>
                                    <span class="mk-timeline-dot"></span>
                                    <div>
                                        <strong>{{ \App\Models\Order::statusLabel((int) $history->to_status) }}</strong>
                                        <small>
                                            از {{ \App\Models\Order::statusLabel($history->from_status) }}
                                            — {{ verta($history->created_at)->format('%d %B %Y H:i') }}
                                            @if($history->user) توسط {{ '@' . $history->user->username }} @endif
                                        </small>
                                        @if($history->note)
                                            <p>{{ $history->note }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>
            </div>
        </div>
    </main>
@endsection
