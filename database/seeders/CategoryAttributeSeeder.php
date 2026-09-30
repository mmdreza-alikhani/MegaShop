<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryAttributeSeeder extends Seeder
{
    /**
     * اتصال ویژگی‌ها به دسته‌بندی‌ها (نوع variation یا filter).
     */
    public function run(): void
    {
        $map = [
            'گوشی موبایل اپل' => ['variations' => ['حافظه داخلی'], 'filters' => ['ضدآب', 'سازگار با شارژ سریع']],
            'گوشی موبایل سامسونگ' => ['variations' => ['حافظه داخلی'], 'filters' => ['ضدآب', 'سازگار با شارژ سریع']],
            'گوشی موبایل شیائومی' => ['variations' => ['حافظه داخلی'], 'filters' => ['ضدآب', 'سازگار با شارژ سریع']],
            'گوشی موبایل هواوی' => ['variations' => ['حافظه داخلی'], 'filters' => ['ضدآب', 'سازگار با شارژ سریع']],
            'لپ‌تاپ' => ['variations' => ['حافظه داخلی', 'رم'], 'filters' => ['سازگار با شارژ سریع', 'نوع کاربری']],
            'مانیتور' => ['variations' => ['سایز صفحه'], 'filters' => ['نوع کاربری']],
            'کیبورد و ماوس' => ['variations' => ['رنگ'], 'filters' => ['نوع اتصال', 'نوع کاربری']],
            'تبلت اپل' => ['variations' => ['حافظه داخلی'], 'filters' => ['ضدآب', 'سازگار با شارژ سریع']],
            'تبلت سامسونگ' => ['variations' => ['حافظه داخلی'], 'filters' => ['ضدآب', 'سازگار با شارژ سریع']],
            'ساعت هوشمند' => ['variations' => ['رنگ'], 'filters' => ['ضدآب', 'نوع اتصال']],
            'مچ‌بند هوشمند' => ['variations' => ['رنگ'], 'filters' => ['ضدآب']],
            'هدفون و هندزفری' => ['variations' => ['رنگ'], 'filters' => ['نوع اتصال', 'ضدآب']],
            'اسپیکر' => ['variations' => ['رنگ'], 'filters' => ['نوع اتصال', 'ضدآب']],
            'کنسول بازی' => ['variations' => ['نسخه'], 'filters' => []],
            'دسته بازی' => ['variations' => ['رنگ'], 'filters' => ['نوع اتصال']],
            'پاوربانک و شارژر' => ['variations' => ['رنگ'], 'filters' => ['سازگار با شارژ سریع']],
            'قاب و محافظ' => ['variations' => ['رنگ'], 'filters' => []],
        ];

        $rows = [];
        $now = now();

        foreach ($map as $categoryTitle => $definition) {
            $category = Category::where('title', $categoryTitle)->first();
            if (! $category) {
                continue;
            }

            foreach (['variations' => 'variation', 'filters' => 'filter'] as $key => $type) {
                foreach ($definition[$key] as $attributeTitle) {
                    $attribute = Attribute::where('title', $attributeTitle)->first();
                    if (! $attribute) {
                        continue;
                    }

                    $rows[] = [
                        'attribute_id' => $attribute->id,
                        'category_id' => $category->id,
                        'type' => $type,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        // جلوگیری از رکورد تکراری (کلید اصلی ترکیبی است)
        $inserted = 0;
        foreach ($rows as $row) {
            $inserted += DB::table('category_attributes')->insertOrIgnore([$row]);
        }

        $this->command->info("✅ اتصال ویژگی‌ها به دسته‌بندی‌ها انجام شد ({$inserted} ردیف جدید).");
    }
}
