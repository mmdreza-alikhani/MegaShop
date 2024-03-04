<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'code' => 'required|unique:coupons,code',
           'type' => 'required',
           'amount' => 'required_if:type,=,amount',
           'percentage' => 'required_if:type,=,percentage',
           'max_percentage_amount' => 'required_if:type,=,percentage',
           'expired_at' => 'required|date',
        ]);

        Coupon::create([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'amount' => $request->amount,
            'percentage' => $request->percentage,
            'max_percentage_amount' => $request->max_percentage_amount,
            'expired_at' => convertToGregorianDate($request->expired_at),
            'description' => $request->description,
        ]);
        toastr()->success($request->name . ' ' . 'با موفقیت به کد های تخفیف اضافه شد');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show' , compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit' , compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $id = $request->all()['id'];
        $request->validate([
            'name' => ['required', Rule::unique('coupons')->ignore($id)],
            'code' => ['required', Rule::unique('coupons')->ignore($id)],
            'type' => 'required',
            'amount' => 'required_if:type,=,amount',
            'percentage' => 'required_if:type,=,percentage',
            'max_percentage_amount' => 'required_if:type,=,percentage',
            'expired_at' => 'required|date',
        ]);

        $coupon->update([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'amount' => $request->amount,
            'percentage' => $request->percentage,
            'max_percentage_amount' => $request->max_percentage_amount,
            'expired_at' => convertToGregorianDate($request->expired_at),
            'description' => $request->description,
        ]);
        toastr()->success($request->name . ' ' . 'با موفقیت ویرایش شد');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $coupons = Coupon::where('name', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.coupons.index' , compact('coupons'));
        }else{
            $coupons = Coupon::latest()->paginate(10);
            return view('admin.coupons.index' , compact('coupons'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
//        Coupon::destroy($request->coupon);
//
//        toastr()->success('کد تخفیف مورد نظر با موفقیت حذف شد!');
//        return redirect()->back();
    }
}
