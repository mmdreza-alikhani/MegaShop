<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $orders = Order::latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View|Application|Factory
    {
        $transaction = Transaction::where('order_id', $order->id)->first();

        return view('admin.orders.show', compact('order', 'transaction'));
    }

    public function search(): View|Application|Factory
    {
        $orders = Order::search('id', trim(request()->keyword))->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }
}
