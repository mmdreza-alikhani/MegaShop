<?php

namespace Database\Seeders;

use App\Models\Banner;
use Database\Seeders\Support\PlaceholderImage;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * بنرهای اسلایدر صفحه اصلی (type = main/top-slider).
     */
    public function run(): void
    {
        $banners = [
            [
                'code' => 'banner-mobile-fest',
                'title' => 'جشنواره پایان فصل موبایل',
                'text' => 'تا ۲۵٪ تخفیف روی پرچم‌داران اپل و سامسونگ؛ فرصت محدود است!',
                'button_text' => 'مشاهده محصولات',
                'button_link' => '/products',
                'priority' => '10',
                'latin' => 'MOBILE FEST',
                'subtitle' => 'UP TO 25% OFF',
            ],
            [
                'code' => 'banner-gaming-week',
                'title' => 'هفته گیمینگ مگاشاپ',
                'text' => 'کنسول‌ها و لوازم گیمینگ نسل جدید با تخفیف ویژه',
                'button_text' => 'خرید کنسول',
                'button_link' => '/products',
                'priority' => '20',
                'latin' => 'GAMING WEEK',
                'subtitle' => 'CONSOLES & GEAR',
            ],
            [
                'code' => 'banner-audio-deals',
                'title' => 'تخفیف ویژه دنیای صدا',
                'text' => 'هدفون و اسپیکرهای اورجینال با قیمت‌های استثنایی',
                'button_text' => 'مشاهده پیشنهادها',
                'button_link' => '/products',
                'priority' => '30',
                'latin' => 'AUDIO DEALS',
                'subtitle' => 'HEADPHONES & SPEAKERS',
            ],
            [
                'code' => 'banner-best-sellers',
                'title' => 'پرفروش‌ترین‌های ماه',
                'text' => 'محبوب‌ترین گجت‌های مگاشاپ به انتخاب کاربران',
                'button_text' => 'مشاهده پرفروش‌ها',
                'button_link' => '/products',
                'priority' => '40',
                'latin' => 'BEST SELLERS',
                'subtitle' => 'TOP PICKS',
            ],
        ];

        $created = 0;
        foreach ($banners as $data) {
            $image = PlaceholderImage::make(
                "banners/images/{$data['code']}.jpg",
                1920,
                640,
                $data['latin'],
                $data['subtitle'],
                PlaceholderImage::paletteIndex($data['code'])
            );

            $banner = Banner::firstOrCreate(
                ['title' => $data['title'], 'type' => 'main/top-slider'],
                [
                    'image' => $image,
                    'text' => $data['text'],
                    'priority' => $data['priority'],
                    'button_text' => $data['button_text'],
                    'button_link' => $data['button_link'],
                    'status' => 1,
                    'is_active' => true,
                ]
            );

            $created += $banner->wasRecentlyCreated ? 1 : 0;
        }

        $this->command->info("✅ بنرها آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
