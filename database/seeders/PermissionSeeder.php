<?php

// -----------------
// PermissionSeeder.php
// -----------------

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Permission::query()->delete();

        // ✅ لیست permissions
        $permissions = [
            // Attributes
            ['name' => 'attributes-index', 'display_name' => 'لیست ویژگی ها'],
            ['name' => 'attributes-create', 'display_name' => 'ایجاد ویژگی ها'],
            ['name' => 'attributes-edit', 'display_name' => 'ویرایش ویژگی ها'],
            ['name' => 'attributes-delete', 'display_name' => 'حذف ویژگی ها'],

            // Banners
            ['name' => 'banners-index', 'display_name' => 'لیست بنر ها'],
            ['name' => 'banners-create', 'display_name' => 'ایجاد بنر ها'],
            ['name' => 'banners-edit', 'display_name' => 'ویرایش بنر ها'],
            ['name' => 'banners-delete', 'display_name' => 'حذف بنر ها'],

            // Brands
            ['name' => 'brands-index', 'display_name' => 'لیست برند ها'],
            ['name' => 'brands-create', 'display_name' => 'ایجاد برند ها'],
            ['name' => 'brands-edit', 'display_name' => 'ویرایش برند ها'],
            ['name' => 'brands-delete', 'display_name' => 'حذف برند ها'],

            // Categories
            ['name' => 'categories-index', 'display_name' => 'لیست دسته بندی ها'],
            ['name' => 'categories-create', 'display_name' => 'ایجاد دسته بندی ها'],
            ['name' => 'categories-edit', 'display_name' => 'ویرایش دسته بندی ها'],
            ['name' => 'categories-delete', 'display_name' => 'حذف دسته بندی ها'],

            // Coupons
            ['name' => 'coupons-index', 'display_name' => 'لیست کد های تخفیف'],
            ['name' => 'coupons-create', 'display_name' => 'ایجاد کد های تخفیف'],
            ['name' => 'coupons-edit', 'display_name' => 'ویرایش کد های تخفیف'],
            ['name' => 'coupons-delete', 'display_name' => 'حذف کد های تخفیف'],

            // Platforms
            ['name' => 'platforms-index', 'display_name' => 'لیست پلتفرم ها'],
            ['name' => 'platforms-create', 'display_name' => 'ایجاد پلتفرم ها'],
            ['name' => 'platforms-edit', 'display_name' => 'ویرایش پلتفرم ها'],
            ['name' => 'platforms-delete', 'display_name' => 'حذف پلتفرم ها'],

            // Posts
            ['name' => 'posts-index', 'display_name' => 'لیست پست ها'],
            ['name' => 'posts-create', 'display_name' => 'ایجاد پست ها'],
            ['name' => 'posts-edit', 'display_name' => 'ویرایش پست ها'],
            ['name' => 'posts-delete', 'display_name' => 'حذف پست ها'],

            // Products
            ['name' => 'products-index', 'display_name' => 'لیست محصولات'],
            ['name' => 'products-create', 'display_name' => 'ایجاد محصولات'],
            ['name' => 'products-edit', 'display_name' => 'ویرایش محصولات'],
            ['name' => 'products-delete', 'display_name' => 'حذف محصولات'],

            // Tags
            ['name' => 'tags-index', 'display_name' => 'لیست تگ ها'],
            ['name' => 'tags-create', 'display_name' => 'ایجاد تگ ها'],
            ['name' => 'tags-edit', 'display_name' => 'ویرایش تگ ها'],
            ['name' => 'tags-delete', 'display_name' => 'حذف تگ ها'],

            // Users
            ['name' => 'users-index', 'display_name' => 'لیست کاربران'],
            ['name' => 'users-create', 'display_name' => 'ایجاد کاربران'],
            ['name' => 'users-edit', 'display_name' => 'ویرایش کاربران'],
            ['name' => 'users-delete', 'display_name' => 'حذف کاربران'],

            // Orders
            ['name' => 'orders-index', 'display_name' => 'لیست سفارشات'],

            // Comments
            ['name' => 'comments-index', 'display_name' => 'لیست نظرات'],
            ['name' => 'comments-edit', 'display_name' => 'بررسی نظرات'],
            ['name' => 'comments-delete', 'display_name' => 'حذف نظرات'],
        ];

        // ✅ ایجاد permissions
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('✅ ' . count($permissions) . ' دسترسی ایجاد شد!');
    }
}
