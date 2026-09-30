<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * ویژگی‌های محصولات (برای تنوع‌ها و فیلترها).
     */
    public function run(): void
    {
        $attributes = [
            'رنگ',
            'حافظه داخلی',
            'رم',
            'نسخه',
            'سایز صفحه',
            'نوع اتصال',
            'ضدآب',
            'سازگار با شارژ سریع',
            'نوع کاربری',
        ];

        $created = 0;
        foreach ($attributes as $title) {
            $attribute = Attribute::firstOrCreate(['title' => $title]);
            $created += $attribute->wasRecentlyCreated ? 1 : 0;
        }

        $this->command->info("✅ ویژگی‌ها آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
