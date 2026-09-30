<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * تنظیمات عمومی فروشگاه (جدول تک‌ردیفی).
     */
    public function run(): void
    {
        if (DB::table('settings')->exists()) {
            $this->command->warn('ℹ️ تنظیمات قبلاً ثبت شده است؛ از ایجاد دوباره صرف‌نظر شد.');

            return;
        }

        DB::table('settings')->insert([
            'address' => 'تهران، خیابان ولیعصر، بالاتر از میدان ونک، برج نگین، طبقه ۵، واحد ۱۲',
            'telephone' => '021-88776655',
            'instagram' => 'https://instagram.com/megashop',
            'telegram' => 'https://t.me/megashop',
            'github' => 'https://github.com/MmdRexa/MegaShop',
            'linkedin' => 'https://linkedin.com/company/megashop',
            'delivery_amount' => 45000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ تنظیمات فروشگاه با موفقیت ثبت شد.');
    }
}
