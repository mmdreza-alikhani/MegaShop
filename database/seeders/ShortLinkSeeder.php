<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Product;
use App\Models\ShortLink;
use Database\Seeders\Support\SeederLookup;
use Illuminate\Database\Seeder;

class ShortLinkSeeder extends Seeder
{
    use SeederLookup;

    /**
     * لینک‌های کوتاه نمونه برای محصولات و مقاله‌ها.
     */
    public function run(): void
    {
        $productTargets = [
            'iPhone 15 Pro Max',
            'iPhone 15 ظرفیت',
            'Galaxy S24 Ultra',
            'Redmi Note 13 Pro',
            'Xiaomi 14',
            'MacBook Air',
            'iPad Air',
            'Watch Series 9',
            'AirPods Pro',
            'Flip 6',
            'PlayStation 5',
            'DualSense',
        ];

        $postTargets = [
            'راهنمای کامل خرید',
            'عمر باتری آیفون',
            'مقایسه گلکسی',
            'مکبوک ایر',
        ];

        $created = 0;

        foreach ($productTargets as $fragment) {
            $product = $this->findProductByFragment($fragment);
            if (! $product) {
                continue;
            }

            if (ShortLink::where('type', 'product')->where('target_id', $product->id)->exists()) {
                continue;
            }

            ShortLink::create([
                'code' => generateUniqueShortCode(),
                'type' => 'product',
                'target_id' => $product->id,
            ]);
            $created++;
        }

        foreach ($postTargets as $fragment) {
            $post = $this->findPostByFragment($fragment);
            if (! $post) {
                continue;
            }

            if (ShortLink::where('type', 'post')->where('target_id', $post->id)->exists()) {
                continue;
            }

            ShortLink::create([
                'code' => generateUniqueShortCode(),
                'type' => 'post',
                'target_id' => $post->id,
            ]);
            $created++;
        }

        $this->command->info("✅ لینک‌های کوتاه آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
