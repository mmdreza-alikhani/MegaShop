<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:orders-index', ['only' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Order::query();
        if ($request->input('q')) {
            $query->search('id', trim(request()->input('q')));
        }
        $orders = $query->latest()->paginate(15)->withQueryString();

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
}
