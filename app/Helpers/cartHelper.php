<?php

use App\Models\Coupon;
use App\Models\Order;
use Binafy\LaravelCart\Models\Cart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @throws Exception
 */
function removeFromCartById($itemable_id, $itemable_type, $user_id): void
{
    try {
        $cart = Cart::where('user_id', $user_id)->firstOrFail();

        $deleted = $cart->items()
            ->where('itemable_id', $itemable_id)
            ->where('itemable_type', $itemable_type)
            ->delete();

        if ($deleted === 0) {
            throw new Exception('محصول مورد نظر در سبد خرید یافت نشد یا قبلاً حذف شده است.');
        }
    } catch (Exception $e) {
        logger()->error('خطا در حذف آیتم از سبد خرید: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * @throws Exception
 */
function clearCart($user_id): void
{
    try {
        $cart = Cart::where('user_id', $user_id)->firstOrFail();
        if (!$cart) {
            throw new Exception('سبد خرید یافت نشد یا قبلاً حذف شده است.');
        }else{
            $cart->items()->delete();
            $cart->delete();
        }

    } catch (Exception $e) {
        logger()->error('خطا در حذف سبد خرید: ' . $e->getMessage());
        throw $e;
    }
}

function isCartEmpty(): bool
{
    $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);

    if (!$cart) {
        return true;
    }

    return $cart->items->isEmpty();
}

function cartItems(): HasMany
{
    return Cart::with('items.itemable.filters')->firstOrCreate(['user_id' => auth()->id()])->items();
}

function itemIsInCart($itemable_id): bool
{
    $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
    $existingItem = $cart->items()
        ->where('itemable_id', $itemable_id)
        ->first();

    return $existingItem !== null;
}

function cartTotalSaleAmount(): float|int
{
    $cartTotalSaleAmount = 0;
    foreach (cartItems() as $item) {
        $options = json_decode($item->options, true);
        $cartTotalSaleAmount += $item->quantity * $options->sale_price;
    }

    return $cartTotalSaleAmount;
}

function cartTotalDeliveryAmount()
{
    $cartTotalDeliveryAmount = 0;
    foreach (cartItems() as $item) {
        $cartTotalDeliveryAmount += $item->itemable->delivery_amount;
    }

    return $cartTotalDeliveryAmount;
}

function checkCoupon($code): array
{
    $coupon = Coupon::where('code', $code)->where('expired_at', '>', Carbon::now())->first();
    if ($coupon == null) {
        session()->forget('coupon');

        return ['error' => 'کد تخفیف وارد شده صحیح نمیباشد!'];
    }
    if (Order::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->where('payment_status', 1)->exists()) {
        session()->forget('coupon');

        return ['error' => 'شما قبلا از این کد تخفیف استفاده کرده اید!'];
    }
    if ($coupon->getRawOriginal('type') == 'amount') {
        session()->put('coupon', ['code' => $coupon->code, 'id' => $coupon->id, 'amount' => $coupon->amount]);
    } else {
        $total = \Cart::getTotal();
        $amount = (($total * $coupon->percentage) / 100) > $coupon->max_percentage_amount ? $coupon->max_percentage_amount : (($total * $coupon->percentage) / 100);
        session()->put('coupon', ['code' => $coupon->code, 'id' => $coupon->id, 'amount' => $amount]);
    }

    return ['success' => 'کد تخفیف برای شما اعمال شد'];
}

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function cartTotalAmount()
{
    if (session()->has('coupon')) {
        if (session()->get('coupon.amount') > (\Cart::getTotal() + cartTotalDeliveryAmount())) {
            return 0;
        } else {
            return (\Cart::getTotal() + cartTotalDeliveryAmount()) - session()->get('coupon.amount');
        }
    } else {
        return \Cart::getTotal() + cartTotalDeliveryAmount();
    }
}
