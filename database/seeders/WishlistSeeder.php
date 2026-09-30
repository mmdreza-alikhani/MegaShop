<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\Support\SeederLookup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    use SeederLookup;

    /**
     * علاقه‌مندی‌های نمونه کاربران.
     */
    public function run(): void
    {
        $wishlists = [
            'sara.mohammadi' => ['Galaxy S24 Ultra', 'PlayStation 5'],
            'amir.karimi' => ['MacBook Air', 'DualSense'],
            'reza.ahmadi' => ['iPhone 15 Pro Max', 'ROG Strix'],
            'narges.rezaei' => ['AirPods Pro', 'Watch Series 9'],
            'mohammad.ghasemi' => ['Galaxy Watch 6', 'Switch OLED'],
            'elham.nouri' => ['iPhone 15 ظرفیت', 'Flip 6'],
            'ali.akbari' => ['Xbox Series X', 'WH-1000XM5'],
            'fatemeh.hosseini' => ['Smart Band 8', 'Redmi Note 13 Pro'],
        ];

        $created = 0;

        foreach ($wishlists as $username => $fragments) {
            $user = User::where('username', $username)->first();
            if (! $user) {
                continue;
            }

            foreach ($fragments as $fragment) {
                $product = $this->findProductByFragment($fragment);
                if (! $product) {
                    continue;
                }

                $inserted = DB::table('wishlists')->insertOrIgnore([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'created_at' => now()->subDays(3 + $created),
                    'updated_at' => now()->subDays(3 + $created),
                ]);

                $created += $inserted;
            }
        }

        $this->command->info("✅ علاقه‌مندی‌ها آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
