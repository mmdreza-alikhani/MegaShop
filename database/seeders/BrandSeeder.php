<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * برندهای معتبر فروشگاه.
     */
    public function run(): void
    {
        $brands = [
            'اپل',
            'سامسونگ',
            'شیائومی',
            'هواوی',
            'آنر',
            'سونی',
            'جی‌بی‌ال',
            'انکر',
            'بیسوس',
            'ایسوس',
            'لنوو',
            'اچ‌پی',
            'لاجیتک',
            'ریزر',
            'مایکروسافت',
            'نینتندو',
            'وان‌پلاس',
            'امیزفیت',
            'گارمین',
            'دل',
        ];

        $created = 0;
        foreach ($brands as $title) {
            $brand = Brand::firstOrCreate(
                ['title' => $title],
                ['status' => 1, 'is_active' => true]
            );
            $created += $brand->wasRecentlyCreated ? 1 : 0;
        }

        $this->command->info("✅ برندها آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
