<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Cart\AddRequest;
use App\Http\Requests\Home\Cart\UpdateRequest;
use App\Models\UserAddress;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(AddRequest $request): RedirectResponse
    {
        $result = cartService()->add(
            $request->integer('product_id'),
            $request->integer('variation_id'),
            $request->integer('quantity')
        );

        if ($result['status'] === 'success') {
            flash()->success($result['message']);
        } elseif ($result['status'] === 'warning') {
            flash()->warning($result['message']);
        } else {
            flash()->error($result['message']);
        }

        return redirect()->back();
    }

    /**
     * حذف یک خط سبد خرید بر اساس شناسه ردیف سبد (cart_items.id).
     */
    public function remove(int $itemId): RedirectResponse
    {
        try {
            cartService()->remove($itemId);
            flash()->success('محصول مورد نظر با موفقیت از سبد خرید حذف شد!');
        } catch (Exception $e) {
            report($e);
            flash()->error('خطا در حذف محصول از سبد خرید!');
        }

        return redirect()->back();
    }

    public function clear(): RedirectResponse
    {
        try {
            cartService()->clear();
            flash()->success('سبد خرید با موفقیت خالی شد!');
        } catch (Exception $e) {
            report($e);
            flash()->error('خطا در حذف سبد خرید!');
        }

        return redirect()->back();
    }

    public function index(): View|Application|Factory
    {
        return view('home.cart.index');
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $result = cartService()->updateQuantities($request->input('products', []));

        foreach ($result['success'] as $message) {
            flash()->success($message);
        }

        foreach ($result['warning'] as $message) {
            flash()->warning($message);
        }

        return redirect()->back();
    }

    public function checkout(): View|Application|Factory|RedirectResponse
    {
        $user = auth()->user();

        if (! $this->hasCompleteProfile($user)) {
            flash()->warning('لطفا مشخصات خود را در حساب کاربری تکمیل کنید');

            return redirect()->route('home.profile.info');
        }

        if (isCartEmpty()) {
            flash()->warning('سبد خرید شما خالی است!');

            return redirect()->route('home.cart.index');
        }

        if (cartService()->hasUnavailableItems()) {
            flash()->warning('برخی از محصولات سبد خرید شما موجود نیستند. لطفا سبد خرید را اصلاح کنید.');

            return redirect()->route('home.cart.index');
        }

        $addresses = UserAddress::userById($user->id)->get();

        return view('home.cart.checkout', compact('addresses'));
    }

    public function checkCoupon(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code|max:255',
        ]);

        if (! auth()->check()) {
            flash()->warning('لطفا وارد حساب خود شوید!');

            return redirect()->back();
        }

        $result = checkCoupon($request->input('code'));

        if (array_key_exists('error', $result)) {
            flash()->warning($result['error']);
        } else {
            flash()->success($result['success']);
        }

        return redirect()->back();
    }

    /**
     * تکمیل مشخصات فردی برای تسویه (نام، نام خانوادگی و موبایل).
     */
    private function hasCompleteProfile($user): bool
    {
        return (bool) ($user->first_name && $user->last_name && $user->phone_number);
    }
}
