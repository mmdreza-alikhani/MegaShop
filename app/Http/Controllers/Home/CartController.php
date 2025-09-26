<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Cart\AddRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(AddRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->input('product_id'));
        $productVariation = ProductVariation::findOrFail($request->input('variation_id'));

        if ($request->input('quantity') > $productVariation->quantity) {
            flash()->warning('تعداد محصولات انتخابی بیش از حد مجاز است!');

            return redirect()->back();
        }

        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = new CartItem([
            'itemable_id'   => $product->id,
            'itemable_type' => Product::class,
            'quantity'      => (int) $request->input('quantity'),
            'options'       => json_encode([
                'variation_id'    => (int) $request->input('variation_id'),
                'price'           => (int) $productVariation->price,
                'sale_price'      => (int) $productVariation->best_price,
                'sku'             => $productVariation->sku,
                'is_discounted'   => $productVariation->is_discounted,
                'delivery_amount' => $product->delivery_amount,
            ]),
        ]);

        $existingItem = $cart->items()
            ->where('itemable_id', $product->id)
            ->whereJsonContains('options->variation_id', (int) $request->input('variation_id'))
            ->first();

        $requestedQty = (int) $request->input('quantity');
        $variationStock = $productVariation->quantity;

        if ($existingItem) {
            $newQty = $existingItem->quantity + $requestedQty;

            if ($newQty <= $variationStock) {
                $existingItem->increment('quantity', $requestedQty);
            } else {
                flash()->warning('تعداد بیش از حد مجاز است!');
                return redirect()->back();
            }
        } else {
            if ($requestedQty <= $variationStock) {
                $cart->items()->save($cartItem);
            } else {
                flash()->warning('تعداد بیش از حد مجاز است!');
                return redirect()->back();
            }
        }


        flash()->success('با موفقیت به سبد خرید شما اضافه شد!');

        return redirect()->back();

    }

    public function remove($itemable_id): RedirectResponse
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        $cart->items()
            ->where('itemable_id', $itemable_id)
            ->where('itemable_type', Product::class)
            ->delete();


        flash()->success('محصول مورد نظر با موفقیت از سبد خرید حذف شد!');

        return redirect()->back();
    }

    public function clear(): RedirectResponse
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }else{
            flash()->warning('سبد خریدی وجود ندارد!');
            return redirect()->back();
        }

        flash()->warning('سبد خرید با موفقیت حذف شد!');
        return redirect()->back();
    }

    public function index(): View|Application|Factory
    {
        return view('home.cart.cart');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required',
        ]);
        foreach ($request->quantity as $rowId => $quantity) {
            $item = \Cart::get($rowId);
            if ($quantity > $item->attributes->quantity) {
                flash()->warning('تعداد محصولات انتخابی بیش از حد مجاز است!');

                return redirect()->back();
            }
            \Cart::update($rowId, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity,
                ],
            ]);
        }
        flash()->success('تعداد محصولات انتخابی با موفقیت ویرایش شد!');

        return redirect()->back();
    }

    public function checkout()
    {
        $user = auth()->user();
        if ($user->first_name == null || $user->last_name == null || $user->phone_number == null) {
            flash()->warning('لطفا مشخصات خود را در حساب کاربری تکمیل کنید');

            return redirect()->route('home.profile.info');
        } else {
            if (\Cart::isEmpty()) {
                flash()->warning('سبد خرید شما خالی است!');

                return redirect()->back();
            }
            $addresses = UserAddresses::where('user_id', $user->id)->get();

            return view('home.cart.checkout', compact('addresses'));
        }

    }

    public function checkCoupon(Request $request)
    {

        $request->validate([
            'code' => 'required',
        ]);

        if (! auth()->check()) {
            flash()->warning('اول ثبت نام کنید!');

            return redirect()->back();
        }

        $result = checkCoupon($request->code);
        if (array_key_exists('error', $result)) {
            flash()->warning($result['error']);

            return redirect()->back();
        } else {
            flash()->success($result['success']);

            return redirect()->back();
        }
    }
}
