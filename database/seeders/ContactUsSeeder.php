<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactUsSeeder extends Seeder
{
    /**
     * پیام‌های نمونه فرم «تماس با ما».
     */
    public function run(): void
    {
        if (DB::table('contact_us')->exists()) {
            $this->command->warn('ℹ️ پیام‌های تماس از قبل وجود دارند؛ از ایجاد دوباره صرف‌نظر شد.');

            return;
        }

        $messages = [
            [
                'name' => 'مریم شریفی',
                'email' => 'maryam.sharifi@yahoo.com',
                'subject' => 'پیگیری سفارش',
                'text' => 'سلام وقت بخیر. سفارش من سه روز است در وضعیت «در حال پردازش» قرار دارد؛ لطفاً اگر امکان دارد روند ارسال را پیگیری کنید. با تشکر.',
                'days_ago' => 9,
            ],
            [
                'name' => 'حسین موسوی',
                'email' => 'hossein.mousavi@gmail.com',
                'subject' => 'سوال درباره گارانتی',
                'text' => 'سلام. آیا گارانتی محصولات شامل خرابی باتری هم می‌شود؟ و اینکه گارانتی از تاریخ فاکتور محاسبه می‌شود یا از تاریخ فعال‌سازی دستگاه؟',
                'days_ago' => 6,
            ],
            [
                'name' => 'شرکت داده‌پردازان آریا',
                'email' => 'info@dadehparazaran.example',
                'subject' => 'همکاری در فروش',
                'text' => 'با سلام. ما در حوزه تأمین لوازم جانبی موبایل فعالیت می‌کنیم و علاقه‌مند به همکاری با فروشگاه شما هستیم. لطفاً در صورت تمایل با ما تماس بگیرید تا کاتالوگ محصولات را ارسال کنیم.',
                'days_ago' => 4,
            ],
            [
                'name' => 'نگار کاظمی',
                'email' => 'negar.kazemi@gmail.com',
                'subject' => 'مشکل در پرداخت',
                'text' => 'سلام. هنگام پرداخت با درگاه بانکی، مبلغ از حسابم کسر شد اما سفارش ثبت نشد. لطفاً بررسی کنید؛ شماره پیگیری تراکنش: ۸۹۴۵۶۲۳۱.',
                'days_ago' => 2,
            ],
            [
                'name' => 'سینا تهرانی',
                'email' => 'sina.tehrani@gmail.com',
                'subject' => 'پیشنهاد محصول',
                'text' => 'سلام. اگر امکان دارد تنوع قاب و محافظ گوشی‌های سامسونگ سری A را هم بیشتر کنید. ممنون از تیم پشتیبانی خوبتان.',
                'days_ago' => 1,
            ],
        ];

        foreach ($messages as $message) {
            DB::table('contact_us')->insert([
                'name' => $message['name'],
                'email' => $message['email'],
                'subject' => $message['subject'],
                'text' => $message['text'],
                'created_at' => now()->subDays($message['days_ago']),
                'updated_at' => now()->subDays($message['days_ago']),
            ]);
        }

        $this->command->info('✅ پیام‌های تماس با ما آماده شدند (' . count($messages) . ' پیام جدید ثبت شد).');
    }
}
