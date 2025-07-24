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
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            Coupon::create([
                'title' => $request->input('title'),
                'code' => $request->input('code'),
                'type' => $request->input('type'),
                'amount' => $request->input('amount'),
                'percentage' => $request->input('percentage'),
                'max_percentage_amount' => $request->input('max_percentage_amount'),
                'expired_at' => convertToGregorianDate($request->input('expired_at')),
                'description' => $request->input('description'),
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage() . 'مشکلی پیش آمد!');
            return redirect()->back();
        }

        toastr()->success('با موفقیت اضافه شد!');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $coupon->update([
                'title' => $request->input('title'),
                'code' => $request->input('code'),
                'type' => $request->input('type'),
                'amount' => $request->has('amount') ? $request->input('amount') : null,
                'percentage' => $request->has('percentage') ? $request->input('percentage') : null,
                'max_percentage_amount' => $request->has('max_percentage_amount') ? $request->input('max_percentage_amount') : null,
                'expired_at' => convertToGregorianDate($request->input('expired_at')),
                'description' => $request->input('description'),
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!',$ex->getMessage());
            return redirect()->back();
        }

        toastr()->success('با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $coupons = Coupon::search('title', trim(request()->keyword))->latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        toastr()->success('با موفقیت حذف شد!');
        return redirect()->back();
    }
}
