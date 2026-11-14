@extends('admin.layout.master')

@section('title', 'داشبورد')

@section('content')
    @php
        $w = 600;
        $h = 160;
        $padY = 12;

        $points = $chart->values();
        $step = $points->count() > 1 ? $w / ($points->count() - 1) : $w;

        $coords = $points->map(function ($point, $index) use ($step, $h, $padY, $chartMax) {
            $x = round($index * $step, 1);
            $y = round($h - $padY - (($point['value'] ?? 0) / $chartMax) * ($h - $padY * 2), 1);

            return ['x' => $x, 'y' => $y];
        });

        $linePath = $coords->map(fn ($c) => $c['x'].','.$c['y'])->implode(' ');
        $areaPath = '0,'.$h.' '.$linePath.' '.$w.','.$h;
    @endphp

    <main class="bmd-layout-content">
        <div class="container-fluid">
            {{-- سرصفحه --}}
            <div class="mk-dash-head">
                <div>
                    <h3>داشبورد</h3>
                    <p>خلاصه وضعیت فروشگاه — {{ verta(now())->format('%d %B %Y') }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> محصول جدید
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-receipt mr-1"></i> سفارش‌ها
                    </a>
                </div>
            </div>

            {{-- شاخص‌های کلیدی --}}
            <div class="mk-kpi-grid">
                @foreach($kpis as $kpi)
                    <div class="mk-kpi {{ !empty($kpi['alert']) ? 'is-alert' : '' }}">
                        <span class="mk-kpi-icon"><i class="{{ $kpi['icon'] }}"></i></span>

                        <div class="mk-kpi-body">
                            <span class="mk-kpi-label">{{ $kpi['label'] }}</span>
                            <strong class="mk-kpi-value">
                                {{ $kpi['value'] }}
                                <small>{{ $kpi['unit'] }}</small>
                            </strong>
                            <span class="mk-kpi-hint">{{ $kpi['hint'] }}</span>
                        </div>

                        @if(!empty($kpi['growth']))
                            <span class="mk-kpi-delta is-{{ $kpi['growth']['dir'] }}">
                                <i class="fas fa-{{ $kpi['growth']['dir'] === 'up' ? 'arrow-up' : ($kpi['growth']['dir'] === 'down' ? 'arrow-down' : 'minus') }}"></i>
                                {{ $kpi['growth']['percent'] }}%
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mk-dash-grid">
                <div class="mk-dash-main">
                    {{-- نمودار درآمد --}}
                    <section class="mk-adm-panel mk-chart-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>درآمد ۱۴ روز اخیر</h4>
                                <p>فقط سفارش‌های پرداخت‌شده</p>
                            </div>
                            <strong class="mk-chart-total">{{ number_format($chartTotal) }} <small>تومان</small></strong>
                        </header>

                        <div class="mk-chart">
                            <svg viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="none" class="mk-chart-svg" role="img"
                                 aria-label="نمودار درآمد دو هفته اخیر">
                                <defs>
                                    <linearGradient id="mkChartFill" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#dd163b" stop-opacity=".38"/>
                                        <stop offset="100%" stop-color="#dd163b" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>

                                <polygon points="{{ $areaPath }}" fill="url(#mkChartFill)"/>
                                <polyline points="{{ $linePath }}" fill="none" stroke="#dd163b" stroke-width="2.5"
                                          stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke"/>
                            </svg>
                        </div>

                        <div class="mk-chart-axis">
                            <span>{{ $points->first()['label'] ?? '' }}</span>
                            <span>{{ $points->get((int) floor($points->count() / 2))['label'] ?? '' }}</span>
                            <span>{{ $points->last()['label'] ?? '' }}</span>
                        </div>
                    </section>

                    {{-- آخرین سفارش‌ها --}}
                    <section class="mk-adm-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>آخرین سفارش‌ها</h4>
                                <p>۶ سفارش تازه</p>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="mk-adm-link">همه سفارش‌ها</a>
                        </header>

                        @if($recentOrders->isEmpty())
                            <p class="mk-adm-empty">هنوز سفارشی ثبت نشده است.</p>
                        @else
                            <div class="mk-adm-table-wrap">
                                <table class="table table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th>سفارش</th>
                                        <th>کاربر</th>
                                        <th>تاریخ</th>
                                        <th>مبلغ</th>
                                        <th>وضعیت</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recentOrders as $order)
                                        @php($paid = (bool) $order->getRawOriginal('payment_status'))
                                        <tr>
                                            <td class="font-weight-bold">#{{ $order->id }}</td>
                                            <td>{{ $order->user?->username ?? '—' }}</td>
                                            <td>{{ verta($order->created_at)->format('%d %B') }}</td>
                                            <td>{{ number_format($order->paying_amount) }}</td>
                                            <td>
                                                <span class="mk-pill {{ $paid ? 'is-ok' : 'is-wait' }}">
                                                    {{ $paid ? 'پرداخت شده' : 'در انتظار' }}
                                                </span>
                                            </td>
                                            <td class="text-left">
                                                <a href="{{ route('admin.orders.show', ['order' => $order->id]) }}"
                                                   class="mk-adm-icon-link" aria-label="جزئیات سفارش">
                                                    <i class="fas fa-angle-left"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>
                </div>

                <aside class="mk-dash-side">
                    {{-- وضعیت سفارش‌ها --}}
                    <section class="mk-adm-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>وضعیت سفارش‌ها</h4>
                                <p>مجموع {{ number_format($statusBreakdown->sum('count')) }} سفارش</p>
                            </div>
                        </header>

                        <div class="mk-status-list">
                            @foreach($statusBreakdown as $row)
                                @php($percent = (int) round(($row['count'] / $statusTotal) * 100))
                                <div class="mk-status-row">
                                    <div class="mk-status-row-head">
                                        <span>{{ $row['label'] }}</span>
                                        <span>{{ number_format($row['count']) }}</span>
                                    </div>
                                    <div class="mk-status-bar">
                                        <span style="width: {{ $percent }}%"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- محصولات پرفروش --}}
                    <section class="mk-adm-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>پرفروش‌ترین محصولات</h4>
                                <p>بر اساس تعداد فروش</p>
                            </div>
                        </header>

                        @if($topProducts->isEmpty())
                            <p class="mk-adm-empty">هنوز فروشی ثبت نشده است.</p>
                        @else
                            <ul class="mk-adm-list">
                                @foreach($topProducts as $row)
                                    <li>
                                        <a href="{{ route('admin.products.edit', ['product' => $row->product->id]) }}">
                                            <img src="{{ Storage::url(config('upload.product_primary_path').'/'.$row->product->primary_image) }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                                 alt="{{ $row->product->title }}" loading="lazy">
                                            <span class="mk-adm-list-body">
                                                <span class="mk-adm-list-title">{{ Str::limit($row->product->title, 46) }}</span>
                                                <small>{{ number_format($row->sold) }} فروش • {{ number_format($row->amount) }} تومان</small>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>

                    {{-- نظرات در انتظار --}}
                    <section class="mk-adm-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>نظرات در انتظار</h4>
                                <p>{{ number_format($pendingComments) }} نظر</p>
                            </div>
                            <a href="{{ route('admin.comments.index') }}" class="mk-adm-link">مدیریت</a>
                        </header>

                        @if($pendingCommentList->isEmpty())
                            <p class="mk-adm-empty">نظر جدیدی در انتظار تایید نیست.</p>
                        @else
                            <ul class="mk-adm-comments">
                                @foreach($pendingCommentList as $comment)
                                    <li>
                                        <span class="mk-adm-avatar">
                                            <img src="{{ Storage::url(config('upload.user_avatar_path').'/'.($comment->user?->avatar ?? 'avatar.png')) }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/avatar-placeholder.svg') }}'"
                                                 alt="{{ $comment->user?->username }}">
                                        </span>
                                        <span class="mk-adm-list-body">
                                            <strong>{{ $comment->user?->username ?? 'کاربر' }}</strong>
                                            <small>{{ Str::limit(strip_tags($comment->text), 60) }}</small>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>

                    {{-- کم‌موجود --}}
                    <section class="mk-adm-panel">
                        <header class="mk-adm-panel-head">
                            <div>
                                <h4>کم‌موجود</h4>
                                <p>موجودی ۵ عدد یا کمتر</p>
                            </div>
                        </header>

                        @if($lowStock->isEmpty())
                            <p class="mk-adm-empty">همه محصولات موجودی کافی دارند.</p>
                        @else
                            <ul class="mk-adm-list">
                                @foreach($lowStock as $product)
                                    <li>
                                        <a href="{{ route('admin.products.edit', ['product' => $product->id]) }}">
                                            <img src="{{ Storage::url(config('upload.product_primary_path').'/'.$product->primary_image) }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                                 alt="{{ $product->title }}" loading="lazy">
                                            <span class="mk-adm-list-body">
                                                <span class="mk-adm-list-title">{{ Str::limit($product->title, 46) }}</span>
                                                <small>موجودی: {{ number_format((int) $product->stock) }} عدد</small>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                </aside>
            </div>

            {{-- دسترسی سریع --}}
            <div class="mk-admin-quick">
                <a href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box-open"></i>
                    <span>مدیریت محصولات</span>
                </a>
                <a href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>سفارش‌های جدید</span>
                </a>
                <a href="{{ route('admin.comments.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>نظرات در انتظار</span>
                </a>
                <a href="{{ route('home.index') }}" target="_blank" rel="noopener">
                    <i class="fas fa-store"></i>
                    <span>مشاهده فروشگاه</span>
                </a>
            </div>
        </div>
    </main>
@endsection
