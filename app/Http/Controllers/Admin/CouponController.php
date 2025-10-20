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
        $this->middleware('permission:coupons-create', ['only' => ['store']]);
        $this->middleware('permission:coupons-edit', ['only' => ['update']]);
        $this->middleware('permission:coupons-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Coupon::query();
        if ($request->input('q')) {
            $query->search('title', trim(request()->input('q')));
        }
        $coupons = $query->latest()->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        try {
            Coupon::create($request->validated());

            flash()->success(config('flasher.coupon.created'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.coupon.create_failed'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        // need some review in the validation and storing data
        // store method too
        try {
            DB::beginTransaction();

            $coupon->update([
                ...$request->validated(),
                'amount' => $request->has('amount') ? $request->input('amount') : null,
                'percentage' => $request->has('percentage') ? $request->input('percentage') : null,
                'max_percentage_amount' => $request->has('max_percentage_amount') ? $request->input('max_percentage_amount') : null,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            toastr()->error(config('flasher.coupon.update_failed'));
            return redirect()->back();
        }

        toastr()->success(config('flasher.coupon.updated'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        try {
            if ($coupon->orders()->count() > 0) {
                toastr()->warning('کد تخفیف غیرقابل حذف است!');
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
