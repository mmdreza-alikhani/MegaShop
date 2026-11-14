<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function mainPage(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();

        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $prevMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $prevMonthEnd = $monthStart->copy()->subSecond();

        /* ---------- شمارش‌ها ---------- */
        $usersCount = User::count();
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $postsCount = Post::count();
        $pendingComments = Comment::where('status', '1')->count();

        /* ---------- درآمد (سفارش‌های پرداخت‌شده) ---------- */
        $revenueBase = fn () => Order::query()->where('payment_status', 1);

        $revenueTotal = (int) $revenueBase()->sum('paying_amount');
        $revenueThisMonth = (int) $revenueBase()->where('created_at', '>=', $monthStart)->sum('paying_amount');
        $revenuePrevMonth = (int) $revenueBase()
            ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->sum('paying_amount');

        /* ---------- رشد ماهانه ---------- */
        $ordersThisMonth = Order::where('created_at', '>=', $monthStart)->count();
        $ordersPrevMonth = Order::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();

        $usersThisMonth = User::where('created_at', '>=', $monthStart)->count();
        $usersPrevMonth = User::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();

        /* ---------- نمودار ۱۴ روز اخیر ---------- */
        $chartDays = 14;
        $chartFrom = $now->copy()->subDays($chartDays - 1)->startOfDay();

        $dailyRevenue = Order::query()
            ->where('payment_status', 1)
            ->where('created_at', '>=', $chartFrom)
            ->selectRaw('DATE(created_at) as day, SUM(paying_amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $chart = collect(range(0, $chartDays - 1))->map(function (int $offset) use ($chartFrom, $dailyRevenue) {
            $date = $chartFrom->copy()->addDays($offset);
            $key = $date->toDateString();

            return [
                'date' => $date,
                'label' => verta($date)->format('%d %B'),
                'value' => (int) ($dailyRevenue[$key] ?? 0),
            ];
        });

        $chartMax = max(1, (int) $chart->max('value'));
        $chartTotal = (int) $chart->sum('value');

        /* ---------- وضعیت سفارش‌ها ---------- */
        $statusCounts = Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusBreakdown = collect(Order::statuses())
            ->map(fn (string $label, int $status) => [
                'status' => $status,
                'label' => $label,
                'count' => (int) ($statusCounts[$status] ?? 0),
            ])
            ->sortByDesc('count')
            ->values();

        $statusTotal = max(1, $statusBreakdown->sum('count'));

        /* ---------- آخرین سفارش‌ها ---------- */
        $recentOrders = Order::query()
            ->with('user:id,username')
            ->latest()
            ->take(6)
            ->get();

        /* ---------- پرفروش‌ترین محصولات ---------- */
        $topProducts = OrderItem::query()
            ->selectRaw('product_id, SUM(quantity) as sold, SUM(subtotal) as amount')
            ->groupBy('product_id')
            ->orderByDesc('sold')
            ->take(5)
            ->with('product:id,title,slug,primary_image')
            ->get()
            ->filter(fn ($row) => $row->product !== null);

        /* ---------- کم‌موجودترین محصولات ---------- */
        $lowStock = Product::query()
            ->active()
            ->withSum('variations as stock', 'quantity')
            ->orderBy('stock')
            ->take(5)
            ->get()
            ->filter(fn (Product $product) => (int) $product->stock <= 5);

        /* ---------- نظرات در انتظار ---------- */
        $pendingCommentList = Comment::query()
            ->where('status', '1')
            ->with('user:id,username,avatar')
            ->latest()
            ->take(4)
            ->get();

        /* ---------- درصد رشد ---------- */
        $growth = function (int $current, int $previous): array {
            if ($previous === 0) {
                $percent = $current > 0 ? 100 : 0;
            } else {
                $percent = (int) round((($current - $previous) / $previous) * 100);
            }

            return [
                'percent' => $percent,
                'dir' => $percent === 0 ? 'flat' : ($percent > 0 ? 'up' : 'down'),
            ];
        };

        $kpis = [
            [
                'key' => 'revenue',
                'label' => 'درآمد ماه',
                'value' => number_format($revenueThisMonth),
                'unit' => 'تومان',
                'hint' => 'کل: '.number_format($revenueTotal).' تومان',
                'icon' => 'fas fa-wallet',
                'growth' => $growth($revenueThisMonth, $revenuePrevMonth),
            ],
            [
                'key' => 'orders',
                'label' => 'سفارش‌های ماه',
                'value' => number_format($ordersThisMonth),
                'unit' => 'سفارش',
                'hint' => 'کل: '.number_format($ordersCount).' سفارش',
                'icon' => 'fas fa-shopping-cart',
                'growth' => $growth($ordersThisMonth, $ordersPrevMonth),
            ],
            [
                'key' => 'users',
                'label' => 'کاربران جدید',
                'value' => number_format($usersThisMonth),
                'unit' => 'کاربر',
                'hint' => 'کل: '.number_format($usersCount).' کاربر',
                'icon' => 'fas fa-user-plus',
                'growth' => $growth($usersThisMonth, $usersPrevMonth),
            ],
            [
                'key' => 'products',
                'label' => 'محصولات',
                'value' => number_format($productsCount),
                'unit' => 'محصول',
                'hint' => 'در کاتالوگ فروشگاه',
                'icon' => 'fas fa-box-open',
                'growth' => null,
            ],
            [
                'key' => 'posts',
                'label' => 'مقالات',
                'value' => number_format($postsCount),
                'unit' => 'مقاله',
                'hint' => 'منتشرشده',
                'icon' => 'fas fa-newspaper',
                'growth' => null,
            ],
            [
                'key' => 'comments',
                'label' => 'نظرات در انتظار',
                'value' => number_format($pendingComments),
                'unit' => 'نظر',
                'hint' => $pendingComments > 0 ? 'نیازمند بررسی' : 'همه بررسی شده',
                'icon' => 'fas fa-comments',
                'growth' => null,
                'alert' => $pendingComments > 0,
            ],
        ];

        return view('admin.index', compact(
            'user',
            'kpis',
            'chart',
            'chartMax',
            'chartTotal',
            'statusBreakdown',
            'statusTotal',
            'recentOrders',
            'pendingComments',
            'topProducts',
            'lowStock',
            'pendingCommentList',
            'revenueTotal',
        ));
    }
}
