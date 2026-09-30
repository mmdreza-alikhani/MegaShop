<?php

namespace Database\Seeders\Support;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * کمک‌کنندهٔ یافتن محصول/مقاله با تحمل تفاوت حروف عربی و فارسی (ي↔ی و ك↔ک).
 *
 * در عنوان‌های فارسی ممکن است حروف «ک» یا «ی» با کدهای یونیکد متفاوت
 * نوشته شوند؛ جست‌وجوی متنی ساده در این حالت شکست می‌خورد. این trait
 * ابتدا جست‌وجوی سریع SQL و در صورت ناموفق‌بودن، جست‌وجوی نرمال‌شده
 * در PHP انجام می‌دهد.
 */
trait SeederLookup
{
    protected function findProductByFragment(string $fragment): ?Product
    {
        /** @var ?Product $result */
        $result = $this->findByFragment(Product::class, $fragment);

        return $result;
    }

    protected function findPostByFragment(string $fragment): ?Post
    {
        /** @var ?Post $result */
        $result = $this->findByFragment(Post::class, $fragment);

        return $result;
    }

    private function findByFragment(string $modelClass, string $fragment): ?Model
    {
        $direct = $modelClass::query()->where('title', 'like', "%{$fragment}%")->first();
        if ($direct !== null) {
            return $direct;
        }

        $needle = $this->normalizePersianCharacters($fragment);

        return $modelClass::query()
            ->get(['id', 'title'])
            ->first(fn ($item) => str_contains($this->normalizePersianCharacters($item->title), $needle));
    }

    private function normalizePersianCharacters(?string $value): string
    {
        return str_replace(['ك', 'ي', 'ى'], ['ک', 'ی', 'ی'], (string) $value);
    }
}
