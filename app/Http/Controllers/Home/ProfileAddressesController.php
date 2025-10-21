<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Profile\Address\StoreRequest;
use App\Http\Requests\Home\Profile\Address\UpdateRequest;
use App\Models\City;
use App\Models\Province;
use App\Models\UserAddress;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class ProfileAddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $user = auth()->user();

        // ✅ Cache برای provinces و cities (این داده‌ها کم تغییر می‌کنن)
        $provinces = Cache::remember('provinces_list', now()->addDay(), function () {
            return Province::pluck('name', 'id');
        });

        $cities = Cache::remember('cities_grouped_by_province', now()->addDay(), function () {
            return City::all(['id', 'name', 'province_id'])
                ->groupBy('province_id')
                ->map(fn($cities) => $cities->pluck('name', 'id'));
        });

        // ✅ Eager Loading برای جلوگیری از N+1 Query
        $user->load(['addresses.city', 'addresses.province']);

        return view('home.profile.addresses.index', compact('user', 'provinces', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        // ✅ Authorization check
        // اگه می‌خوای محدودیت تعداد آدرس داشته باشی:
        if (auth()->user()->addresses()->count() >= 10) {
            flash()->error('حداکثر تعداد آدرس‌های مجاز 10 عدد است');
            return redirect()->back();
        }

        // ✅ فقط validated data رو بگیر
        UserAddress::create([
            'user_id' => auth()->id(),
            ...$request->validated()
        ]);

        flash()->success('آدرس مورد نظر با موفقیت ثبت شد');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(UpdateRequest $request, UserAddress $address): RedirectResponse
    {
        $this->authorize('update', $address);

        $address->update($request->validated());

        flash()->success('آدرس مورد نظر با موفقیت ویرایش شد');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(UserAddress $address): RedirectResponse
    {
        // ✅ Authorization
        $this->authorize('delete', $address);

        // ✅ Soft delete در صورت نیاز
        $address->delete();

        flash()->success('آدرس مورد نظر با موفقیت حذف شد');
        return redirect()->back();
    }
}
