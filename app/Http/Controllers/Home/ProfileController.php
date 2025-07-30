<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class ProfileController extends Controller
{
    public function info()
    {
        if (Auth::check()) {
            $user = Auth::user();

            return view('home.profile.info', compact('user'));
        }
        toastr()->error('لطفا ثبت نام کنید.');

        return redirect()->back();
    }

    public function orders()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $orders = Order::where('user_id', $user->id)->latest()->paginate();

            return view('home.profile.orders.index', compact('user', 'orders'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');

        return redirect()->back();
    }

    public function showOrder(Order $order)
    {
        if (Auth::check()) {
            if ($order->user_id != \auth()->id()) {
                return redirect()->back();
            }
            $user = Auth::user();
            $transaction = Transaction::where('order_id', $order->id)->first();

            return view('home.profile.orders.show', compact('user', 'order', 'transaction'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');

        return redirect()->back();
    }

    public function wishlist()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $wishlist = WishList::where('user_id', \auth()->id())->get();

            return view('home.profile.wishlist', compact('user', 'wishlist'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');

        return redirect()->back();
    }

    public function comments()
    {
        if (Auth::check()) {
            $user = Auth::user();

            return view('home.profile.comments', compact('user'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');

        return redirect()->back();
    }

    public function addresses() {}

    public function resetPassword()
    {
        if (Auth::check()) {
            $user = Auth::user();

            return view('home.profile.resetPassword', compact('user'));
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');

        return redirect()->back();
    }

    public function resetPasswordCheck(Request $request)
    {
        $request->validate([
            'currentPassword' => 'nullable',
            'newPassword' => 'required|min:8|max:32',
            'confirmNewPassword' => 'required|same:newPassword',
        ]);

        if (Hash::check($request->currentPassword, \auth()->user()->password)) {
            $newPassword = Hash::make($request->newPassword);
            $user = Auth::user();
            $user->update([
                'password' => $newPassword,
            ]);
            toastr()->success('کلمه عبور با موفقیت تغییر یافت.');

            return redirect()->back();
        } else {
            toastr()->error('کلمه عبور فعلی وارد شده درست نیست.');

            return redirect()->back();
        }
    }

    public function verifyEmail()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->email_verified_at == null) {
                return view('home.profile.verifyEmail', compact('user'));
            } else {
                toastr()->info(('ایمیل شما قبلا تایید شده است.'));

                return redirect()->back();
            }
        }
        toastr()->error('لطفا ابتدا ثبت نام کنید.');

        return redirect()->back();
    }

    public function logout()
    {
        if (Auth::check()) {
            auth()->logout();
        }

        return redirect()->route('home.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => ['required', Rule::unique('users')->ignore($request->user_id)],
            'first_name' => 'nullable|min:3|max:16',
            'last_name' => 'nullable|min:3|max:16',
            'phone_number' => ['nullable', Rule::unique('users')->ignore($request->user_id), 'min:10', 'max:10'],
            'avatar' => 'nullable|mimes:jpg,jpeg,png,svg',
            'email' => 'required|email',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $manager = new ImageManager(
                new Driver
            );

            if ($request->avatar) {
                $avatarName = generateFileName($request->avatar->getClientOriginalName());
                $resized = $manager->read($request->avatar)->resize(100, 100)->save(public_path(env('USER_AVATAR_UPLOAD_PATH')), $avatarName);
                //                $request->avatar->move(public_path(env('USER_AVATAR_UPLOAD_PATH')) , $avatarName);
            }

            $user->update([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'avatar' => $request->avatar ? $avatarName : $user->avatar,
                'email' => $request->email,
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error('مشکلی پیش آمد!', $ex->getMessage());

            return redirect()->back();
        }

        toastr()->success('با موفقیت اطلاعات ویرایش شد.');

        return redirect()->back();
    }
}
