<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * ترتیب اجرا مهم است: ابتدا داده‌های پایه (استان/شهر/دسترسی‌ها و کاربر ادمین)،
     * سپس دسته‌بندی و برند و ویژگی‌ها، بعد کاربران و محصولات و در پایان
     * سفارش‌ها، سبدها و محتوای تکمیلی.
     */
    public function run(): void
    {
        $this->call([
            // داده‌های پایه و دسترسی‌ها
            ProvinceSeeder::class,
            CitySeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,

            // دسته‌بندی، برند، پلتفرم، ویژگی‌ها و برچسب‌ها
            AttributeSeeder::class,
            CategorySeeder::class,
            CategoryAttributeSeeder::class,
            BrandSeeder::class,
            PlatformSeeder::class,
            TagSeeder::class,

            // کاربران و اطلاعات مرتبط
            UserSeeder::class,
            UserAddressSeeder::class,
            CouponSeeder::class,

            // کاتالوگ محصولات و محتوا
            ProductSeeder::class,
            BannerSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            FaqSeeder::class,
            ShortLinkSeeder::class,

            // تراکنش‌های خرید و داده‌های تکمیلی
            OrderSeeder::class,
            CartSeeder::class,
            WishlistSeeder::class,
            ContactUsSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
