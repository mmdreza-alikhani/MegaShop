<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Models\Coupon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coupons-index', ['only' => ['index']]);
        $this->middleware('permission:coupons-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:coupons-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:coupons-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Coupon::query()->withCount('orders');

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $status = $request->input('status');

        if ($status === 'active') {
            $query->where('is_active', 1)->where('expired_at', '>', now());
        } elseif ($status === 'expired') {
            $query->where('expired_at', '<=', now());
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        match ($request->input('sort')) {
            'oldest' => $query->oldest(),
            'expiry' => $query->orderBy('expired_at'),
            'used' => $query->orderByDesc('orders_count'),
            default => $query->latest(),
        };

        $coupons = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::query()->where('is_active', 1)->where('expired_at', '>', now())->count(),
            'expired' => Coupon::query()->where('expired_at', '<=', now())->count(),
            'used' => Coupon::has('orders')->count(),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        try {
            Coupon::create([
                ...$request->validated(),
                'amount' => $request->input('type') === 'amount' ? $request->input('amount') : null,
                'percentage' => $request->input('type') === 'percentage' ? $request->input('percentage') : null,
                'max_percentage_amount' => $request->input('type') === 'percentage' ? $request->input('max_percentage_amount') : null,
                'is_active' => $request->boolean('is_active', true),
            ]);

            flash()->success(config('flasher.coupon.created'));

            return redirect()->route('admin.coupons.index');
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.coupon.create_failed'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon): View|Application|Factory
    {
        $coupon->loadCount('orders');

        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $coupon->update([
                ...$request->validated(),
                'amount' => $request->input('type') === 'amount' ? $request->input('amount') : null,
                'percentage' => $request->input('type') === 'percentage' ? $request->input('percentage') : null,
                'max_percentage_amount' => $request->input('type') === 'percentage' ? $request->input('max_percentage_amount') : null,
                'is_active' => $request->boolean('is_active', $coupon->is_active),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.coupon.update_failed'));

            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.coupon.updated'));

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        try {
            if ($coupon->orders()->count() > 0) {
                flash()->warning('کد تخفیف در سفارش‌ها استفاده شده و قابل حذف نیست؛ آن را غیرفعال کنید.');

                return redirect()->back();
            }

            $coupon->delete();

            flash()->success(config('flasher.coupon.deleted'));

            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.coupon.delete_failed'));

            return redirect()->back();
        }
    }
}
