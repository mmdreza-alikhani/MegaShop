<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index' , compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $transaction = Transaction::where('order_id', $order->id)->first();
        return view('admin.orders.show' , compact('order', 'transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $orders = Order::where('id', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.orders.index' , compact('orders'));
        }else{
            $orders = Order::latest()->paginate(10);
            return view('admin.orders.index' , compact('orders'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
