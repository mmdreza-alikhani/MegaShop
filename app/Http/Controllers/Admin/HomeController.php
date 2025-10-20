<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function mainPage(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();

        $ordersCount = Cache::remember('orders_count', now()->addHour(), function () {
            return Order::count();
        });

        $usersCount = Cache::remember('users_count', now()->addHour(), function () {
            return User::count();
        });

        $productsCount = Cache::remember('products_count', now()->addHour(), function () {
            return Product::count();
        });

        $postsCount = Cache::remember('posts_count', now()->addHour(), function () {
            return Post::count();
        });


        return view('admin.index', compact('ordersCount', 'usersCount', 'productsCount', 'postsCount', 'user'));
    }
}
