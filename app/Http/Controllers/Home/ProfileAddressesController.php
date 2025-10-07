<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Profile\Address\StoreRequest;
use App\Http\Requests\Home\Profile\Address\UpdateRequest;
use App\Models\City;
use App\Models\Province;
use App\Models\UserAddress;
use App\Models\UserAddresses;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileAddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        $user = Auth::user();
        $provinces = Province::pluck('name', 'id');
        $cities = City::all();
        dd($cities);
        return view('home.profile.addresses.index', compact('user', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        UserAddress::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'phone_number' => $request->input('phone_number'),
            'postal_code' => $request->integer('postal_code'),
            'province_id' => $request->integer('province_id'),
            'city_id' => $request->integer('city_id'),
            'address' => $request->input('address'),
        ]);

        flash()->success('آدرس مورد نظر با موفقیت ثبت شد');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, UserAddress $userAddress): Factory|View
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->where('id', $request->address)->first();
        $cities = City::where('province_id', $address->province_id)->get();
        return view('home.profile.addresses.edit', compact('user', 'address', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, UserAddress $address): RedirectResponse
    {
        $address->update([
            'title' => $request->input('title'),
            'phone_number' => $request->integer('phone_number'),
            'postal_code' => $request->integer('postal_code'),
            'province_id' => $request->integer('province_id'),
            'city_id' => $request->integer('city_id'),
            'address' => $request->string('address'),
        ]);

        toastr()->success('آدرس مورد نظر با موفقیت ویرایش شد');
        return redirect()->route('home.profile.addresses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddress $address): RedirectResponse
    {
        $address->delete();

        toastr()->success('آدرس مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }

    public function get_province_cities_list(Province $province)
    {
        return City::where('province_id', $province->id)->pluck('name', 'id');
    }
}
