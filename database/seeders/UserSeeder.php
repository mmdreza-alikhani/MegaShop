<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Support\PlaceholderImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * مشتریان نمونه فروشگاه.
     */
    public function run(): void
    {
        $users = [
            ['username' => 'amir.karimi', 'first_name' => 'امیرحسین', 'last_name' => 'کریمی', 'phone' => '09121234567', 'email' => 'amir.karimi@gmail.com', 'days_ago' => 260],
            ['username' => 'sara.mohammadi', 'first_name' => 'سارا', 'last_name' => 'محمدی', 'phone' => '09123456789', 'email' => 'sara.mohammadi@gmail.com', 'days_ago' => 240],
            ['username' => 'reza.ahmadi', 'first_name' => 'رضا', 'last_name' => 'احمدی', 'phone' => '09131112233', 'email' => 'reza.ahmadi@yahoo.com', 'days_ago' => 210],
            ['username' => 'narges.rezaei', 'first_name' => 'نرگس', 'last_name' => 'رضایی', 'phone' => '09132223344', 'email' => 'narges.rezaei@gmail.com', 'days_ago' => 190],
            ['username' => 'mohammad.ghasemi', 'first_name' => 'محمد', 'last_name' => 'قاسمی', 'phone' => '09151234567', 'email' => 'm.ghasemi@gmail.com', 'days_ago' => 170],
            ['username' => 'elham.nouri', 'first_name' => 'الهام', 'last_name' => 'نوری', 'phone' => '09159876543', 'email' => 'elham.nouri@outlook.com', 'days_ago' => 150],
            ['username' => 'hossein.mousavi', 'first_name' => 'حسین', 'last_name' => 'موسوی', 'phone' => '09161234567', 'email' => 'hossein.mousavi@gmail.com', 'days_ago' => 130],
            ['username' => 'fatemeh.hosseini', 'first_name' => 'فاطمه', 'last_name' => 'حسینی', 'phone' => '09165554433', 'email' => 'fatemeh.hosseini@gmail.com', 'days_ago' => 110],
            ['username' => 'ali.akbari', 'first_name' => 'علی', 'last_name' => 'اکبری', 'phone' => '09171234567', 'email' => 'ali.akbari@gmail.com', 'days_ago' => 95],
            ['username' => 'maryam.sharifi', 'first_name' => 'مریم', 'last_name' => 'شریفی', 'phone' => '09172223344', 'email' => 'maryam.sharifi@yahoo.com', 'days_ago' => 80],
            ['username' => 'sina.tehrani', 'first_name' => 'سینا', 'last_name' => 'تهرانی', 'phone' => '09183334455', 'email' => 'sina.tehrani@gmail.com', 'days_ago' => 60],
            ['username' => 'negar.kazemi', 'first_name' => 'نگار', 'last_name' => 'کاظمی', 'phone' => '09194445566', 'email' => 'negar.kazemi@gmail.com', 'days_ago' => 40],
        ];

        // آواتار پیش‌فرض
        PlaceholderImage::make(
            'users/avatars/avatar.png',
            320,
            320,
            'MegaShop',
            '',
            11,
            'png'
        );

        $created = 0;
        foreach ($users as $index => $data) {
            $user = User::firstOrCreate(
                ['username' => $data['username']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone_number' => $data['phone'],
                    'email' => $data['email'],
                    'password' => 'password',
                    'provider_name' => 'manual',
                    'status' => 1,
                    'is_active' => true,
                    'email_verified_at' => now()->subDays($data['days_ago'] - 1),
                ]
            );

            if (! $user->wasRecentlyCreated) {
                continue;
            }

            $created++;

            // چند کاربر آواتار اختصاصی داشته باشند
            if ($index < 4) {
                $parts = explode('.', $data['username']);
                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1] ?? '', 0, 1));

                $avatar = PlaceholderImage::make(
                    "users/avatars/{$data['username']}.png",
                    320,
                    320,
                    $initials,
                    '',
                    PlaceholderImage::paletteIndex($data['username']),
                    'png'
                );
                $user->forceFill(['avatar' => $avatar])->save();
            }

            // تاریخ عضویت واقع‌گرایانه
            DB::table('users')->where('id', $user->id)->update([
                'created_at' => now()->subDays($data['days_ago']),
                'updated_at' => now()->subDays($data['days_ago']),
            ]);
        }

        $this->command->info("✅ کاربران نمونه آماده شدند ({$created} مورد جدید ثبت شد). رمز عبور همه: password");
    }
}
