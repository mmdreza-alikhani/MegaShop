<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:orders-index', ['only' => ['index', 'show', 'updateStatus']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Order::query()->with(['transaction', 'user:id,username']);

        if ($request->filled('q')) {
            $keyword = trim((string) $request->input('q'));

            $query->where(function ($sub) use ($keyword) {
                $sub->where('id', $keyword)
                    ->orWhereHas('user', fn ($user) => $user->where('username', 'like', "%{$keyword}%"));
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View|Application|Factory
    {
        $order->load([
            'user:id,username',
            'address.province',
            'address.city',
            'coupon:id,code,amount',
            'items.product:id,title,slug',
            'items.productVariation:id,value',
            'histories.user:id,username',
            'transaction',
        ]);

        $transaction = $order->transaction ?? Transaction::where('order_id', $order->id)->first();
        $statuses = Order::statuses();

        return view('admin.orders.show', compact('order', 'transaction', 'statuses'));
    }

    /**
     * تغییر وضعیت سفارش + ثبت در تاریخچه.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', Rule::in(array_keys(Order::statuses()))],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $from = (int) $order->getRawOriginal('status');
        $to = (int) $validated['status'];

        if ($from === $to) {
            flash()->info('وضعیت سفارش تغییری نکرد.');

            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($order, $from, $to, $validated) {
                $order->update(['status' => $to]);

                $order->histories()->create([
                    'user_id' => auth()->id(),
                    'from_status' => $from,
                    'to_status' => $to,
                    'note' => $validated['note'] ?? null,
                ]);
            });

            flash()->success('وضعیت سفارش با موفقیت تغییر کرد.');
        } catch (\Throwable $e) {
            report($e);
            flash()->error('تغییر وضعیت سفارش با خطا مواجه شد.');
        }

        return redirect()->back();
    }
}
