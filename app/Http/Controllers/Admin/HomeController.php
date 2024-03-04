<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function mainPage()
    {
        $orders = \App\Models\Order::where('status', '1')->where('updated_at', '>=', Carbon::now()->subHours(24)->toDateTimeString())->get();
        $recentUsers = \App\Models\User::where('status', '1')->where('updated_at', '>=', Carbon::now()->subHours(24)->toDateTimeString())->get();
        $products = \App\Models\Product::where('status', '1')->where('updated_at', '>=', Carbon::now()->subHours(24)->toDateTimeString())->get();
        $user = Auth::user();

        return view('admin.index', compact('orders','recentUsers', 'user', 'products'));
    }
}
