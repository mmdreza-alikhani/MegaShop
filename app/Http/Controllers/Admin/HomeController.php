<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    public function mainPage(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $orderCount = Order::where('status', '1')
            ->where('updated_at', '>=', Carbon::now()->subHours(24))
            ->count();

        $recentUserCount = User::where('status', '1')
            ->where('updated_at', '>=', Carbon::now()->subHours(24))
            ->count();

        $productCount = Product::where('status', '1')
            ->where('updated_at', '>=', Carbon::now()->subHours(24))
            ->count();
        $user = auth()->user();

        return view('admin.index', compact('orderCount','recentUserCount', 'productCount', 'user'));
    }
}
