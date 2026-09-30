<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * کدهای تخفیف نمونه (فعال و منقضی).
     */
    public function run(): void
    {
        $coupons = [
            [
                'title' => 'تخفیف ۲۵ درصدی جشنواره پایان فصل',
                'code' => 'MEGA25',
                'type' => 'percentage',
                'percentage' => 25,
                'max_percentage_amount' => 500000,
                'days' => 45,
                'description' => 'حداکثر تخفیف ۵۰۰ هزار تومان؛ قابل استفاده روی همه محصولات فروشگاه.',
            ],
            [
                'title' => 'تخفیف اولین خرید',
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'percentage' => 10,
                'max_percentage_amount' => 200000,
                'days' => 30,
                'description' => 'به مناسبت خوش‌آمدگویی؛ ویژه کاربران جدید مگاشاپ.',
            ],
            [
                'title' => 'تخفیف ۱۰۰ هزار تومانی',
                'code' => 'OFF100',
                'type' => 'amount',
                'amount' => 100000,
                'days' => 20,
                'description' => 'با هر خرید بالای ۵ میلیون تومان از این کد استفاده کنید.',
            ],
            [
                'title' => 'جشنواره موبایل',
                'code' => 'MOBILE15',
                'type' => 'percentage',
                'percentage' => 15,
                'max_percentage_amount' => 1500000,
                'days' => 10,
                'description' => 'ویژه خرید گوشی‌های موبایل؛ حداکثر تا ۱.۵ میلیون تومان تخفیف.',
            ],
            [
                'title' => 'تخفیف ویژه دانشجویان',
                'code' => 'STUDENT20',
                'type' => 'percentage',
                'percentage' => 20,
                'max_percentage_amount' => 300000,
                'days' => 60,
                'description' => 'برای دانشجویان عزیز؛ با ارائه کارت دانشجویی معتبر.',
            ],
            [
                'title' => 'کد تخفیف نوروزی (منقضی شده)',
                'code' => 'NOWRUZ',
                'type' => 'amount',
                'amount' => 50000,
                'days' => -30,
                'description' => 'جشنواره نوروزی گذشته؛ این کد منقضی شده است.',
            ],
        ];

        $created = 0;
        foreach ($coupons as $data) {
            $coupon = Coupon::firstOrCreate(
                ['code' => $data['code']],
                [
                    'title' => $data['title'],
                    'type' => $data['type'],
                    'amount' => $data['amount'] ?? null,
                    'percentage' => $data['percentage'] ?? null,
                    'max_percentage_amount' => $data['max_percentage_amount'] ?? null,
                    'expired_at' => now()->addDays($data['days']),
                    'description' => $data['description'],
                    'status' => 1,
                    'is_active' => true,
                ]
            );

            $created += $coupon->wasRecentlyCreated ? 1 : 0;
        }

        $this->command->info("✅ کدهای تخفیف آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
