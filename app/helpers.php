<?php

use App\Models\Coupon;
use App\Services\CartService;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ShortLink;
use Binafy\LaravelCart\Models\Cart;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Random\RandomException;


function generateFileName($name): string
{
    $year = Carbon::now()->year;
    $month = Carbon::now()->month;
    $day = Carbon::now()->day;
    $hour = Carbon::now()->hour;
    $minute = Carbon::now()->minute;
    $second = Carbon::now()->second;

    return $year.'_'.$month.'_'.$day.'_'.$hour.'_'.$minute.'_'.$second.strtolower($name);
}

function convertToGregorianDate($date): ?string
{
    if ($date == null) {
        return null;
    }
    $pattern = "#[/\s]#";
    $splitedSolarDate = preg_split($pattern, $date);
    $gregorianFormat = Verta::jalaliToGregorian($splitedSolarDate[0], $splitedSolarDate[1], $splitedSolarDate[2]);

    return implode('/', $gregorianFormat).' '.$splitedSolarDate[3];
}

function removeTimeFromDate($date)
{
    if ($date == null) {
        return null;
    }
    $pattern = "#[\s]#";
    $splitedSolarDate = preg_split($pattern, $date);

    return $splitedSolarDate[0];
}

function convertPersianNumbersToEnglish($input): array|string
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '٤', '۵', '٥', '٦', '۶', '۷', '۸', '۹'];
    $english = [0,  1,  2,  3,  4,  4,  5,  5,  6,  6,  7,  8,  9];

    return str_replace($english, $persian, $input);
}

/**
 * @throws RandomException
 * @throws Exception
 */
function generateUniqueShortCode(int $length = 6): string
{
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789'; // بدون 0 و O و l
    $maxAttempts = 5;

    for ($i = 0; $i < $maxAttempts; $i++) {
        $code = '';
        for ($j = 0; $j < $length; $j++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        if (!ShortLink::where('code', $code)->exists()) {
            return $code;
        }
    }

    throw new Exception('کد یکتا تولید نشد. لطفاً دوباره تلاش کنید.');
}

// Cart

function cartService(): CartService
{
    return app(CartService::class);
}

function cartItems(): Collection
{
    return cartService()->items();
}

function isCartEmpty(): bool
{
    return cartService()->isEmpty();
}

function isItemInCart($itemable_id): bool
{
    return cartService()->has((int) $itemable_id);
}

function cartTotalAmount(): int
{
    return cartService()->totalAmount();
}

function cartDeliveryAmount(): int
{
    return cartService()->deliveryAmount();
}

/**
 * مبلغ کد تخفیف اعمال‌شده (یا null اگر کوپنی در session نیست).
 */
function cartCouponAmount(): ?int
{
    return cartService()->couponAmount();
}

function cartPayingAmount(): int
{
    return cartService()->payingAmount();
}

function cartAmounts(): array
{
    return cartService()->amounts();
}

/**
 * حذف یک خط سبد خرید بر اساس شناسه خود ردیف سبد (cart_items.id).
 *
 * @throws Exception
 */
function removeFromCartById(int $cartItemId, $user_id = null): void
{
    try {
        cartService()->remove($cartItemId);
    } catch (Exception $e) {
        logger()->error('خطا در حذف آیتم از سبد خرید: '.$e->getMessage());
        throw $e;
    }
}

/**
 * @throws Exception
 */
function clearCart($user_id = null): void
{
    try {
        cartService()->clear();
    } catch (Exception $e) {
        logger()->error('خطا در حذف سبد خرید: '.$e->getMessage());
        throw $e;
    }
}

function checkCoupon($code): array
{
    $coupon = Coupon::where('code', $code)->isAvailable()->first();

    if ($coupon == null) {
        session()->forget('coupon');

        return ['error' => 'کد تخفیف وارد شده صحیح نمیباشد!'];
    }

    if (Order::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->where('payment_status', 1)->exists()) {
        session()->forget('coupon');

        return ['error' => 'شما قبلا از این کد تخفیف استفاده کرده اید!'];
    }

    $totalAmount = cartTotalAmount();

    if ($totalAmount <= 0) {
        session()->forget('coupon');

        return ['error' => 'سبد خرید شما خالی است!'];
    }

    if ($coupon->getRawOriginal('type') == 'amount') {
        $amount = min((int) $coupon->amount, $totalAmount);
    } else {
        $amount = (int) (($totalAmount * $coupon->percentage) / 100);

        if ($coupon->max_percentage_amount) {
            $amount = min($amount, (int) $coupon->max_percentage_amount);
        }
    }

    session()->put('coupon', ['code' => $coupon->code, 'id' => $coupon->id, 'amount' => $amount]);

    return ['success' => 'کد تخفیف برای شما اعمال شد'];
}
