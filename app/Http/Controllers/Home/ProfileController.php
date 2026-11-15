<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Profile\UpdateRequest;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\WishList;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function info(): Factory|View|RedirectResponse
    {
        $user = Auth::user();
        return view('home.profile.info', compact('user'));
    }

    public function orders(): Factory|View
    {
        $user = Auth::user();
        $orders = Order::where('user_id', auth()->id())->latest()->paginate();

        return view('home.profile.orders.index', compact('user', 'orders'));
    }

    public function showOrder(Order $order): Factory|View|RedirectResponse
    {
        if ($order->user_id != \auth()->id()) {
            return redirect()->back();
        }
        $user = Auth::user();
        $transaction = Transaction::where('order_id', $order->id)->first();

        return view('home.profile.orders.show', compact('user', 'order', 'transaction'));
    }

    public function wishlist(): Factory|View
    {
        $user = Auth::user();
        $wishlist = WishList::userId(auth()->id())->with('product')->get();
        return view('home.profile.wishlist', compact('user', 'wishlist'));
    }

    public function comments(): Factory|View
    {
        $user = Auth::user();
        $comments = Comment::userId(auth()->id())->with('commentable', 'user.rates')->orderBy('commentable_type')->get();
        return view('home.profile.comments', compact('user', 'comments'));
    }

    public function addresses() {

    }

    public function resetPassword(): Factory|View|RedirectResponse
    {
        $user = Auth::user();
        return view('home.profile.resetPassword', compact('user'));
    }

    public function resetPasswordCheck(Request $request): RedirectResponse
    {
        $request->validate([
            'currentPassword' => 'sometimes|required|string|min:8|max:255',
            'newPassword' => 'required|string|min:8|max:255|different:currentPassword',
            'confirmNewPassword' => 'required|string|same:newPassword',
        ]);
        $user = Auth::user();

        if ($user->provider_name == 'manual'){
            if (Hash::check($request->input('currentPassword'), $user->password)) {
                $newPassword = Hash::make($request->newPassword);
                $user->update([
                    'password' => $newPassword,
                ]);
                flash()->success('کلمه عبور با موفقیت تغییر یافت.');
            } else {
                flash()->error('کلمه عبور فعلی وارد شده درست نیست.');
            }
        }else{
            $newPassword = Hash::make($request->input('newPassword'));
            $user->update([
                'password' => $newPassword,
                'provider_name' => 'manual'
            ]);
            flash()->success('کلمه عبور با موفقیت تغییر یافت.');
        }

        return redirect()->back();
    }

    public function verifyEmail(): Factory|View|RedirectResponse
    {
        $user = Auth::user();
        if ($user->email_verified_at == null) {
            return view('home.profile.verifyEmail', compact('user'));
        } else {
            flash()->info(('ایمیل شما قبلا تایید شده است.'));
            return redirect()->back();
        }
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();
        return redirect()->back();
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            $user->fill([
                'username'     => $request->input('username'),
                'first_name'   => $request->input('first_name'),
                'last_name'    => $request->input('last_name'),
                'phone_number' => $request->input('phone_number'),
                'email'        => $request->input('email'),
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            flash()->error('مشکلی پیش آمد!', $ex->getMessage());

            return redirect()->back();
        }

        flash()->success('با موفقیت اطلاعات ویرایش شد.');
        return redirect()->back();
    }

    /**
     * ذخیرهٔ آواتار انتخاب‌شده (خروجی برش تصویر).
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:' . config('upload.max_file_kb'),
        ], [
            'avatar.max' => 'حجم تصویر نباید بیشتر از ' . config('upload.max_file_kb') . ' کیلوبایت باشد.',
            'avatar.image' => 'فایل انتخاب‌شده باید تصویر باشد.',
            'avatar.mimes' => 'فرمت تصویر باید jpg، png یا webp باشد.',
        ]);

        $user = Auth::user();
        $file = $request->file('avatar');
        $directory = config('upload.user_avatar_path');
        $fileName = generateFileName($file->getClientOriginalName());

        $file->storeAs($directory, $fileName, 'public');

        $previous = $user->avatar;
        $user->update(['avatar' => $fileName]);

        if ($previous && $previous !== 'avatar.png' && Storage::disk('public')->exists($directory . '/' . $previous)) {
            Storage::disk('public')->delete($directory . '/' . $previous);
        }

        return response()->json([
            'success' => true,
            'message' => 'تصویر پروفایل با موفقیت به‌روزرسانی شد.',
            'url' => Storage::url($directory . '/' . $fileName),
        ]);
    }
}
