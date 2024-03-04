<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\UserAddress;
use App\Models\UserAddresses;
use Faker\Provider\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileAddresses extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()){
            $user = Auth::user();
            return view('home.profile.addresses.index', compact('user'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::check()){
            $user = Auth::user();
            return view('home.profile.addresses.create', compact('user'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::check()){
            $request->validate([
                'title' => 'required|unique:user_addresses,title',
                'postalCode' => 'required|ir_postal_code',
                'phoneNumber' => 'required|ir_mobile',
                'province_id' => 'required',
                'city_id' => 'required',
                'address' => 'required',
            ]);
            UserAddresses::create([
                'user_id' => \auth()->id(),
                'title' => $request->title,
                'phone_number' => $request->phoneNumber,
                'postal_code' => $request->postalCode,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'address' => $request->address
            ]);

            toastr()->success('آدرس مورد نظر با موفقیت ثبت شد');
            return redirect()->route('home.profile.addresses.index');
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, UserAddresses $userAddresses)
    {
        if (Auth::check()){
            $user = Auth::user();
            $address = UserAddresses::where('user_id', $user->id)->where('id', $request->address)->first();
            $cities = City::where('province_id', $address->province_id)->get();
            return view('home.profile.addresses.edit', compact('user', 'address', 'cities'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAddress $address)
    {
        if (Auth::check()){
            $request->validate([
                'title' => 'required',
                'postalCode' => 'required|ir_postal_code',
                'phoneNumber' => 'required|ir_mobile',
                'province_id' => 'required',
//                'city_id' => 'required',
                'address' => 'required',
            ]);
            $address->update([
                'title' => $request->title,
                'phone_number' => $request->phoneNumber,
                'postal_code' => $request->postalCode,
                'province_id' => $request->province_id,
//                'city_id' => $request->city_id,
                'address' => $request->address
            ]);

            toastr()->success('آدرس مورد نظر با موفقیت ویرایش شد');
            return redirect()->route('home.profile.addresses.index');
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        UserAddresses::destroy($request->address);

        toastr()->success('آدرس مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }

    public function get_province_cities_list(Request $request){
        $cities = City::where('province_id', $request->province_id)->get();
        return $cities;
    }
}
