<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * برچسب‌های محصولات و مقاله‌ها.
     */
    public function run(): void
    {
        $tags = [
            'پرفروش',
            'پیشنهاد ویژه',
            'تخفیف‌دار',
            'جدید',
            'اورجینال',
            'گیمینگ',
            'دانشجویی',
            'هدیه',
        ];

        $created = 0;
        foreach ($tags as $title) {
            $tag = Tag::firstOrCreate(['title' => $title]);
            $created += $tag->wasRecentlyCreated ? 1 : 0;
        }

        $this->command->info("✅ برچسب‌ها آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
