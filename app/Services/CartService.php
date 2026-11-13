<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Illuminate\Support\Collection;

/**
 * تنها مرجع خواندن/نوشتن سبد خرید.
 *
 * قواعد مهم:
 *  - متدهای خواندن (items/amounts) هیچ نوشتنی در دیتابیس انجام نمی‌دهند.
 *  - قیمت هر خط از همان variation لود‌شده خوانده می‌شود (بدون کوئری اضافه).
 *  - تعداد بیشتر از موجودی در حافظه محدود می‌شود و به کاربر گزارش می‌شود.
 */
class CartService
{
    private ?Cart $cart = null;

    private ?Collection $items = null;

    private ?int $totalAmount = null;

    private ?int $deliveryAmount = null;

    public function cart(bool $create = true): ?Cart
    {
        if ($this->cart !== null) {
            return $this->cart;
        }

        $userId = auth()->id();

        if (! $userId) {
            return null;
        }

        $query = Cart::query()->where('user_id', $userId);

        $this->cart = $create
            ? $query->firstOrCreate(['user_id' => $userId])
            : $query->first();

        return $this->cart;
    }

    /**
     * آیتم‌های سبد همراه با variation، قیمت واحد و قیمت خط.
     */
    public function items(): Collection
    {
        if ($this->items !== null) {
            return $this->items;
        }

        $cart = $this->cart();

        if (! $cart) {
            return $this->items = collect();
        }

        $cart->loadMissing(['items.itemable']);

        $variationIds = $cart->items
            ->map(fn (CartItem $item) => (int) ($item->getOptions()['variation_id'] ?? 0))
            ->filter()
            ->unique()
            ->values();

        $variations = $variationIds->isEmpty()
            ? collect()
            : ProductVariation::query()
                ->with(['attribute:id,title', 'product:id,title,slug,primary_image,delivery_amount,is_active'])
                ->whereIn('id', $variationIds)
                ->get()
                ->keyBy('id');

        return $this->items = $cart->items
            ->map(function (CartItem $item) use ($variations) {
                $options = $item->getOptions() ?: [];
                $variation = $variations->get((int) ($options['variation_id'] ?? 0));

                // variation حذف‌شده یا نامعتبر: این خط قابل فروش نیست.
                if (! $variation) {
                    return null;
                }

                $available = max(0, (int) $variation->quantity);
                $requested = (int) $item->quantity;
                $effective = min($requested, $available);
                $unitPrice = (int) $variation->best_price;

                foreach ($options as $key => $value) {
                    $item->{$key} = $value;
                }

                $item->variation = $variation;
                $item->available_quantity = $available;
                $item->requested_quantity = $requested;
                $item->is_available = $available > 0;
                $item->is_quantity_adjusted = $requested !== $effective;
                $item->unit_price = $unitPrice;
                $item->total_price = $effective * $unitPrice;
                // تعداد مؤثر (محدود به موجودی) مبنای همه محاسبات است.
                $item->quantity = $effective;

                return $item;
            })
            ->filter()
            ->values();
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }

    public function hasUnavailableItems(): bool
    {
        return $this->items()->contains(fn (CartItem $item) => ! $item->is_available);
    }

    public function has(int $productId, ?int $variationId = null): bool
    {
        return $this->items()->contains(function (CartItem $item) use ($productId, $variationId) {
            if ((int) $item->itemable_id !== $productId) {
                return false;
            }

            return $variationId === null || (int) ($item->variation->id ?? 0) === $variationId;
        });
    }

    public function totalAmount(): int
    {
        return $this->totalAmount ??= (int) $this->items()->sum('total_price');
    }

    public function deliveryAmount(): int
    {
        return $this->deliveryAmount ??= (int) $this->items()
            ->filter(fn (CartItem $item) => $item->is_available)
            ->sum(fn (CartItem $item) => (int) ($item->itemable->delivery_amount ?? 0));
    }

    public function couponAmount(): ?int
    {
        if (! session()->has('coupon.amount')) {
            return null;
        }

        // تخفیف هرگز بیش از مبلغ سبد نمی‌شود.
        return (int) min((int) session()->get('coupon.amount'), $this->totalAmount());
    }

    public function payingAmount(): int
    {
        return max(0, $this->totalAmount() + $this->deliveryAmount() - (int) $this->couponAmount());
    }

    public function amounts(): array
    {
        return [
            'totalAmount' => $this->totalAmount(),
            'deliveryAmount' => $this->deliveryAmount(),
            'couponAmount' => $this->couponAmount(),
            'payingAmount' => $this->payingAmount(),
        ];
    }

    /**
     * افزودن محصول به سبد؛ اگر همان variation قبلاً اضافه شده باشد تعداد جمع می‌شود.
     *
     * @return array{status: string, message: string}
     */
    public function add(int $productId, int $variationId, int $quantity): array
    {
        $variation = ProductVariation::query()
            ->where('product_id', $productId)
            ->find($variationId);

        if (! $variation || ! Product::whereKey($productId)->exists()) {
            return ['status' => 'error', 'message' => 'محصول مورد نظر یافت نشد!'];
        }

        $quantity = max(1, $quantity);
        $available = max(0, (int) $variation->quantity);

        if ($available <= 0) {
            return ['status' => 'warning', 'message' => 'این محصول در حال حاضر موجود نیست!'];
        }

        $cart = $this->cart();
        $existing = $cart->items()
            ->where('itemable_id', $productId)
            ->where('itemable_type', Product::class)
            ->get()
            ->first(fn (CartItem $item) => (int) ($item->getOptions()['variation_id'] ?? 0) === $variationId);

        $current = (int) ($existing->quantity ?? 0);

        if ($current + $quantity > $available) {
            return [
                'status' => 'warning',
                'message' => "حداکثر تعداد قابل سفارش برای این محصول $available عدد است!",
            ];
        }

        if ($existing) {
            $existing->update(['quantity' => $current + $quantity]);
        } else {
            $cart->items()->save(new CartItem([
                'itemable_id' => $productId,
                'itemable_type' => Product::class,
                'quantity' => $quantity,
                'options' => json_encode(['variation_id' => $variationId]),
            ]));
        }

        $this->flush();

        return ['status' => 'success', 'message' => 'با موفقیت به سبد خرید شما اضافه شد!'];
    }

    /**
     * بروزرسانی گروهی تعداد آیتم‌ها. ورودی: [productId => [variationId => quantity]]
     *
     * @return array{success: array<int, string>, warning: array<int, string>}
     */
    public function updateQuantities(array $products): array
    {
        $success = $warning = [];
        $cart = $this->cart();

        foreach ($products as $productId => $variations) {
            if (! is_array($variations)) {
                continue;
            }

            foreach ($variations as $variationId => $quantity) {
                $variation = ProductVariation::query()
                    ->where('product_id', $productId)
                    ->find($variationId);

                if (! $variation) {
                    $warning[] = 'یکی از محصولات سبد خرید دیگر موجود نیست.';

                    continue;
                }

                $quantity = (int) $quantity;
                $available = max(0, (int) $variation->quantity);

                if ($quantity < 1) {
                    $warning[] = 'تعداد وارد شده برای «'.$variation->product?->title.'» معتبر نیست.';

                    continue;
                }

                if ($quantity > $available) {
                    $warning[] = 'تعداد محصول «'.$variation->product?->title.'» بیش از موجودی ('.$available.' عدد) است.';

                    continue;
                }

                $item = $cart->items()
                    ->where('itemable_id', $productId)
                    ->get()
                    ->first(fn (CartItem $cartItem) => (int) ($cartItem->getOptions()['variation_id'] ?? 0) === (int) $variationId);

                if (! $item) {
                    $warning[] = 'یکی از محصولات سبد خرید یافت نشد.';

                    continue;
                }

                $item->update(['quantity' => $quantity]);
                $success[] = 'تعداد محصول «'.$variation->product?->title.'» بروزرسانی شد.';
            }
        }

        $this->flush();

        return ['success' => $success, 'warning' => $warning];
    }

    public function remove(int $cartItemId): void
    {
        $cart = $this->cart(false);

        if (! $cart) {
            return;
        }

        $cart->items()->whereKey($cartItemId)->delete();

        $this->flush();
    }

    public function clear(): void
    {
        $cart = $this->cart(false);

        if (! $cart) {
            return;
        }

        $cart->items()->delete();
        $cart->delete();

        $this->flush();
    }

    private function flush(): void
    {
        $this->cart = null;
        $this->items = null;
        $this->totalAmount = null;
        $this->deliveryAmount = null;
    }
}
