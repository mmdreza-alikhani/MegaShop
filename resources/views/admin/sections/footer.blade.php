@php
    use App\Models\Order;

    $pendingOrders = Order::where('status', 0)->count();
@endphp

<footer class="mk-adm-footer">
    <div class="mk-adm-footer-inner">
        <span class="mk-adm-footer-brand">
            <i class="fas fa-gamepad" aria-hidden="true"></i>
            <span>مگاشاپ — پنل مدیریت</span>
            <small>© {{ verta(now())->format('%Y') }}</small>
        </span>

        <nav class="mk-adm-footer-links" aria-label="دسترسی سریع فوتر">
            <a href="{{ route('admin.panel') }}">داشبورد</a>
            <a href="{{ route('admin.orders.index') }}">
                سفارش‌ها
                @if($pendingOrders > 0)
                    <span class="mk-adm-footer-badge">{{ $pendingOrders }}</span>
                @endif
            </a>
            <a href="{{ route('home.index') }}" target="_blank" rel="noopener">فروشگاه</a>
        </nav>

        <span class="mk-adm-footer-version">نسخه {{ config('app.version', '1.0.0') }}</span>
    </div>
</footer>
