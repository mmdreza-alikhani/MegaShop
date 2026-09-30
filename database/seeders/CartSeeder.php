<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Binafy\LaravelCart\Models\Cart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * سبدهای خرید نیمه‌کاره چند کاربر نمونه.
     */
    public function run(): void
    {
        $carts = [
            'sara.mohammadi' => [
                ['iPhone 13', 0, 1],
            ],
            'amir.karimi' => [
                ['Galaxy S24 Ultra', 0, 1],
                ['IdeaPad Slim', 0, 1],
            ],
            'negar.kazemi' => [
                ['Tune Buds', 0, 1],
                ['Smart Band 8', 1, 1],
            ],
        ];

        $created = 0;

        foreach ($carts as $username => $items) {
            $user = User::where('username', $username)->first();
            if (! $user) {
                continue;
            }

            $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

            if ($cart->items()->count() > 0) {
                continue;
            }

            foreach ($items as [$fragment, $variationIndex, $quantity]) {
                $product = Product::where('title', 'like', "%{$fragment}%")->first();
                if (! $product) {
                    continue;
                }

                $variations = $product->variations()->orderBy('id')->get();
                $variation = $variations[$variationIndex] ?? $variations->first();
                if (! $variation) {
                    continue;
                }

                DB::table('cart_items')->insert([
                    'cart_id' => $cart->id,
                    'itemable_type' => Product::class,
                    'itemable_id' => $product->id,
                    'quantity' => $quantity,
                    'options' => json_encode(['variation_id' => $variation->id], JSON_UNESCAPED_UNICODE),
                    'created_at' => now()->subHours(6),
                    'updated_at' => now()->subHours(2),
                ]);
                $created++;
            }
        }

        $this->command->info("✅ سبدهای خرید نمونه آماده شدند ({$created} قلم جدید ثبت شد).");
    }
}
