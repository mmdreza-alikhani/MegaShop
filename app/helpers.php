<?php

use App\Models\Coupon;
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

/**
 * @throws Exception
 */
function removeFromCartById(int $itemable_id, $user_id): void
{
    try {
        $cart = Cart::where('user_id', $user_id)->firstOrFail();

        $cart->items()
            ->where('itemable_id', $itemable_id)
            ->where('itemable_type', 'App\Models\Product')
            ->first()
            ->delete();

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
            $cart->delete();
            $cart->items()->delete();
        }

    } catch (Exception $e) {
        logger()->error('خطا در حذف سبد خرید: ' . $e->getMessage());
        throw $e;
    }
}

function isCartEmpty(): bool
{
    if (auth()->check()) {
        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);

        if (!$cart) {
            return true;
        }

        return $cart->items->isEmpty();
    }else{
        return false;
    }
}

function cartItems(): Collection
{
    $cart = Cart::with('items.itemable')->firstOrCreate(['user_id' => auth()->id()]);

    // Collect all variation_ids from options
    $variationIds = $cart->items->map(function ($item) {
        $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
        return $options['variation_id'] ?? null;
    })->filter()->unique();

    // Load all variations in one query with their products
    $variations = ProductVariation::with('product')
        ->whereIn('id', $variationIds)
        ->get()
        ->keyBy('id');

    // Map items and attach variation + options with validation
    return $cart->items->map(function ($item) use ($cart, $variations) {
        $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;

        $variation = $variations[$options['variation_id']] ?? null;

        if ($variation) {
            $availableQty = $variation->quantity;

            // Validate quantity BEFORE attaching extra properties
            if ($item->quantity > $availableQty || $availableQty >= 0) {
                $item->update(['quantity' => $availableQty]);
            }

            if ($availableQty <= 0) {
                removeFromCartById($item->id, auth()->id());
                return null; // Mark for filtering
            }
        } else {
            removeFromCartById($item->id, auth()->id());
            return null; // Mark for filtering
        }

        // NOW attach display properties AFTER all database operations
        foreach ($options as $key => $value) {
            $item->$key = $value;
        }
        $item->variation = $variation;

        return $item;
    })->filter(); // Remove null items (deleted ones)
}

function isItemInCart($itemable_id): bool
{
    if (auth()->check()) {
        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
        $existingItem = $cart->items()
            ->where('itemable_id', $itemable_id)
            ->first();

        return $existingItem !== null;
    }else{
        return false;
    }
}

function cartTotalAmount(): float|int
{
    $cartTotalSaleAmount = 0;
    foreach (cartItems() as $item) {
        $options = json_decode($item->options, true);
        $variation = ProductVariation::find($options['variation_id'])->first();
        $cartTotalSaleAmount += $item->quantity * $variation->best_price;
    }

    return $cartTotalSaleAmount;
}

function cartDeliveryAmount()
{
    $cartDeliveryAmount = 0;
    foreach (cartItems() as $item) {
        $product = Product::find($item->itemable_id)->first();
        if ($product->delivery_amount != null) {
            $cartDeliveryAmount += $product->delivery_amount;
        }
    }
    return $cartDeliveryAmount;
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
    if ($coupon->getRawOriginal('type') == 'amount') {
        session()->put('coupon', ['code' => $coupon->code, 'id' => $coupon->id, 'amount' => $coupon->amount]);
    } else {
        $totalAmount = cartTotalAmount();
        $amount = min((($totalAmount * $coupon->percentage) / 100), $coupon->max_percentage_amount);
        session()->put('coupon', ['code' => $coupon->code, 'id' => $coupon->id, 'amount' => $amount]);
    }

    return ['success' => 'کد تخفیف برای شما اعمال شد'];
}

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function cartPayingAmount(): int
{
    $totalAmount = cartTotalAmount();
    $deliveryAmount = cartDeliveryAmount();
    $couponAmount = session()->has('coupon') ? session()->get('coupon.amount') : null;
    return $totalAmount + $deliveryAmount - $couponAmount;
}

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function cartAmounts(): array
{
    return [
        'totalAmount' => cartTotalAmount(),
        'deliveryAmount' => cartDeliveryAmount(),
        'couponAmount' => session()->has('coupon') ? session()->get('coupon.amount') : null,
        'payingAmount' => cartPayingAmount()
    ];
}
