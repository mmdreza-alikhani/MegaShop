<?php

namespace Database\Seeders;

use App\Models\Platform;
use Database\Seeders\Support\PlaceholderImage;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * پلتفرم‌های فروشگاه به همراه تصویر نمادین هرکدام.
     */
    public function run(): void
    {
        $platforms = [
            ['title' => 'iOS', 'code' => 'ios', 'label' => 'iOS'],
            ['title' => 'اندروید', 'code' => 'android', 'label' => 'Android'],
            ['title' => 'ویندوز', 'code' => 'windows', 'label' => 'Windows'],
            ['title' => 'macOS', 'code' => 'macos', 'label' => 'macOS'],
            ['title' => 'لینوکس', 'code' => 'linux', 'label' => 'Linux'],
            ['title' => 'پلی‌استیشن', 'code' => 'playstation', 'label' => 'PlayStation'],
            ['title' => 'ایکس‌باکس', 'code' => 'xbox', 'label' => 'Xbox'],
            ['title' => 'نینتندو', 'code' => 'nintendo', 'label' => 'Nintendo'],
        ];

        $created = 0;
        foreach ($platforms as $item) {
            $image = PlaceholderImage::make(
                "platforms/images/{$item['code']}.png",
                480,
                480,
                $item['label'],
                'PLATFORM',
                PlaceholderImage::paletteIndex($item['code']),
                'png'
            );

            $platform = Platform::firstOrCreate(
                ['title' => $item['title']],
                ['image' => $image, 'status' => 1, 'is_active' => true]
            );

            if (! $platform->wasRecentlyCreated && $platform->image !== $image) {
                $platform->update(['image' => $image]);
            }

            $created += $platform->wasRecentlyCreated ? 1 : 0;
        }

        $this->command->info("✅ پلتفرم‌ها آماده شدند ({$created} مورد جدید ثبت شد).");
    }
}
