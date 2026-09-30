<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserAddress;
use Database\Seeders\Support\SeederLookup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    use SeederLookup;

    /**
     * سفارش‌های نمونه به همراه اقلام و تراکنش‌های آن‌ها.
     */
    public function run(): void
    {
        // این سیدر قابل اجرای مجدد است؛ برای کاربری که سفارش دارد، سفارش تازه ساخته نمی‌شود.
        // در صورت نالابل نبودن coupon_id، از این کد برای سفارش‌های بدون تخفیف استفاده می‌شود
        $couponIdNullable = (bool) (collect(Schema::getColumns('orders'))->firstWhere('name', 'coupon_id')['nullable'] ?? false);
        $fallbackCoupon = Coupon::where('code', 'WELCOME10')->first();

        $orders = [
            [
                'user' => 'sara.mohammadi', 'days_ago' => 18, 'type' => 'online', 'paid' => true,
                'coupon' => 'MEGA25', 'description' => null,
                'items' => [['iPhone 15 Pro Max', 0, 1], ['AirPods Pro', 0, 1]],
            ],
            [
                'user' => 'amir.karimi', 'days_ago' => 32, 'type' => 'online', 'paid' => true,
                'coupon' => 'OFF100', 'description' => null,
                'items' => [['MacBook Air', 0, 1], ['G413', 0, 1]],
            ],
            [
                'user' => 'reza.ahmadi', 'days_ago' => 12, 'type' => 'online', 'paid' => true,
                'coupon' => null, 'description' => null,
                'items' => [['PlayStation 5', 0, 1]],
            ],
            [
                'user' => 'narges.rezaei', 'days_ago' => 2, 'type' => 'online', 'paid' => false,
                'coupon' => null, 'description' => 'در صورت موجود شدن، سفارش سریع ارسال شود.',
                'items' => [['Watch Series 9', 0, 1]],
            ],
            [
                'user' => 'mohammad.ghasemi', 'days_ago' => 25, 'type' => 'online', 'paid' => true,
                'coupon' => 'MOBILE15', 'description' => null,
                'items' => [['Galaxy S24 Ultra', 0, 1], ['WH-1000XM5', 0, 1]],
            ],
            [
                'user' => 'elham.nouri', 'days_ago' => 55, 'type' => 'cash', 'paid' => true,
                'coupon' => 'NOWRUZ', 'description' => 'تحویل حضوری در فروشگاه انجام شد.',
                'items' => [['Redmi Note 13 Pro', 0, 2]],
            ],
            [
                'user' => 'hossein.mousavi', 'days_ago' => 9, 'type' => 'online', 'paid' => true,
                'coupon' => 'STUDENT20', 'description' => null,
                'items' => [['Tab S9 FE', 0, 1], ['Flip 6', 0, 1]],
            ],
            [
                'user' => 'fatemeh.hosseini', 'days_ago' => 4, 'type' => 'online', 'paid' => false,
                'coupon' => null, 'description' => 'پرداخت اول با خطا مواجه شد؛ در انتظار پرداخت مجدد.',
                'items' => [['Smart Band 8', 0, 2], ['PowerCore', 0, 1]],
            ],
        ];

        $created = 0;

        foreach ($orders as $spec) {
            $user = User::where('username', $spec['user'])->first();
            if (! $user || Order::where('user_id', $user->id)->exists()) {
                continue;
            }

            $address = UserAddress::where('user_id', $user->id)->orderBy('id')->first();
            if (! $address) {
                continue;
            }

            $coupon = isset($spec['coupon']) ? Coupon::where('code', $spec['coupon'])->first() : null;
            if (! $coupon && ! $couponIdNullable) {
                $coupon = $fallbackCoupon;
            }

            // محاسبه مبالغ مطابق منطق cartAmounts()
            $totalAmount = 0;
            $deliveryAmount = 0;
            $orderItems = [];

            foreach ($spec['items'] as [$fragment, $variationIndex, $quantity]) {
                $product = $this->findProductByFragment($fragment);
                if (! $product) {
                    continue 2;
                }

                $variations = $product->variations()->orderBy('id')->get();
                $variation = $variations[$variationIndex] ?? $variations->first();
                if (! $variation) {
                    continue 2;
                }

                $price = (int) $variation->best_price;
                $subtotal = $price * $quantity;

                $totalAmount += $subtotal;
                $deliveryAmount += (int) $product->delivery_amount;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_variation_id' => $variation->id,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            if (empty($orderItems)) {
                continue;
            }

            $couponAmount = 0;
            if ($coupon) {
                if ($coupon->getRawOriginal('type') === 'amount') {
                    $couponAmount = (int) $coupon->amount;
                } else {
                    $couponAmount = (int) min(($totalAmount * $coupon->percentage) / 100, $coupon->max_percentage_amount);
                }
            }

            $payingAmount = max(0, $totalAmount + $deliveryAmount - $couponAmount);

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'coupon_id' => $coupon?->id,
                'status' => $spec['paid'] ? 1 : 0,
                'total_amount' => $totalAmount,
                'delivery_amount' => $deliveryAmount,
                'coupon_amount' => $couponAmount,
                'paying_amount' => $payingAmount,
                'payment_type' => $spec['type'],
                'payment_status' => $spec['paid'] ? 1 : 0,
                'description' => $spec['description'],
            ]);

            $createdAt = now()->subDays($spec['days_ago']);

            foreach ($orderItems as $item) {
                OrderItem::create(['order_id' => $order->id] + $item);
            }

            $transactionId = null;
            if ($spec['type'] === 'online') {
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'amount' => $payingAmount,
                    'ref_id' => $spec['paid'] ? (string) random_int(100000000, 999999999) : null,
                    'token' => 'A' . strtoupper(Str::random(35)),
                    'description' => $spec['paid'] ? 'پرداخت موفق از طریق درگاه زرین‌پال' : 'در انتظار تکمیل پرداخت',
                    'gateway_name' => 'zarinpal',
                    'status' => $spec['paid'] ? 1 : 0,
                ]);
                $transactionId = $transaction->id;
            }

            // تاریخ‌های واقع‌گرایانه
            DB::table('orders')->where('id', $order->id)->update([
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            DB::table('order_items')->where('order_id', $order->id)->update([
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            if ($transactionId) {
                DB::table('transactions')->where('id', $transactionId)->update([
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            $created++;
        }

        $this->command->info("✅ سفارش‌های نمونه آماده شدند ({$created} سفارش جدید ثبت شد).");
    }
}
