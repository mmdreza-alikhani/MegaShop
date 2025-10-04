<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Cart\AddRequest;
use App\Http\Requests\Home\Cart\UpdateRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\UserAddress;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
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
        $productVariation = ProductVariation::findOrFail($request->input('variation_id'));

        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
        $cartItem = new CartItem([
            'itemable_id'   => $request->integer('product_id'),
            'itemable_type' => Product::class,
            'quantity'      => $request->integer('quantity'),
            'options'       => json_encode([
                'variation_id'    => $request->integer('variation_id'),
            ]),
        ]);

        if ($request->integer('quantity') <= $productVariation->quantity) {
            $cart->items()->save($cartItem);
            flash()->success('با موفقیت به سبد خرید شما اضافه شد!');
        } else {
            flash()->warning('تعداد بیش از حد مجاز است!');
        }
        return redirect()->back();
    }

    public function remove($itemable_id): RedirectResponse
    {
        try {
            removeFromCartById($itemable_id, Product::class, auth()->id());
            flash()->success('محصول مورد نظر با موفقیت از سبد خرید حذف شد!');
        } catch (Exception $e) {
            flash()->error('خطا در حذف محصول از سبد خرید: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    public function clearCart(): RedirectResponse
    {
        try {
            clearCart(auth()->id());
            flash()->success('سبد خرید با حذف شد!');
        } catch (Exception $e) {
            flash()->error('خطا در حذف سبد خرید: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    public function index(): View|Application|Factory
    {
        return view('home.cart.cart');
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $productVariation = ProductVariation::findOrFail($request->input('variation_id'));

        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);

        $item = $cart->items()
            ->where('itemable_id', $request->integer('product_id'))
            ->whereJsonContains('options->variation_id', $request->integer('variation_id'))
            ->first();

        $requestedQty = $request->integer('quantity') + $item->quantity;
        $variationStock = $productVariation->quantity;

        if ($requestedQty <= $variationStock - 1) {
            $item->update(['quantity' => $requestedQty]);
            flash()->warning('تعداد محصولات انتخابی با موفقیت تغییر کرد!');
        }else{
            flash()->warning('تعداد محصولات انتخابی بیش از حد مجاز است!');
        }
        return redirect()->back();
    }

    public function checkout(): View|Application|Factory|RedirectResponse
    {
        $user = auth()->user();
        if ($user->is_validated) {
            flash()->warning('لطفا مشخصات خود را در حساب کاربری تکمیل کنید');

            return redirect()->route('home.profile.info');
        }

        if (isCartEmpty()) {
            flash()->warning('سبد خرید شما خالی است!');
            return redirect()->back();
        }

        $addresses = UserAddress::User($user->id)->get();
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
}
