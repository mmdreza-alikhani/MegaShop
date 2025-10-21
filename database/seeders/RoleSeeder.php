<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ پاک کردن roles قبلی
        Role::query()->delete();

        // ✅ تعریف نقش‌ها و دسترسی‌هایشان
        $roles = [
            [
                'name' => 'super-admin',
                'display_name' => 'مدیر کل',
                'permissions' => 'all', // همه دسترسی‌ها
            ],
            [
                'name' => 'attribute-manager',
                'display_name' => 'مدیر ویژگی‌ها',
                'permissions' => ['attributes-index', 'attributes-create', 'attributes-edit', 'attributes-delete'],
            ],
            [
                'name' => 'banner-manager',
                'display_name' => 'مدیر بنرها',
                'permissions' => ['banners-index', 'banners-create', 'banners-edit', 'banners-delete'],
            ],
            [
                'name' => 'brand-manager',
                'display_name' => 'مدیر برندها',
                'permissions' => ['brands-index', 'brands-create', 'brands-edit', 'brands-delete'],
            ],
            [
                'name' => 'category-manager',
                'display_name' => 'مدیر دسته‌بندی‌ها',
                'permissions' => ['categories-index', 'categories-create', 'categories-edit', 'categories-delete'],
            ],
            [
                'name' => 'coupon-manager',
                'display_name' => 'مدیر کدهای تخفیف',
                'permissions' => ['coupons-index', 'coupons-create', 'coupons-edit', 'coupons-delete'],
            ],
            [
                'name' => 'platform-manager',
                'display_name' => 'مدیر پلتفرم‌ها',
                'permissions' => ['platforms-index', 'platforms-create', 'platforms-edit', 'platforms-delete'],
            ],
            [
                'name' => 'post-manager',
                'display_name' => 'مدیر پست‌ها',
                'permissions' => ['posts-index', 'posts-create', 'posts-edit', 'posts-delete'],
            ],
            [
                'name' => 'product-manager',
                'display_name' => 'مدیر محصولات',
                'permissions' => ['products-index', 'products-create', 'products-edit', 'products-delete'],
            ],
            [
                'name' => 'tag-manager',
                'display_name' => 'مدیر تگ‌ها',
                'permissions' => ['tags-index', 'tags-create', 'tags-edit', 'tags-delete'],
            ],
            [
                'name' => 'user-manager',
                'display_name' => 'مدیر کاربران',
                'permissions' => ['users-index', 'users-create', 'users-edit', 'users-delete'],
            ],
            [
                'name' => 'order-manager',
                'display_name' => 'مدیر سفارشات',
                'permissions' => ['orders-index'],
            ],
            [
                'name' => 'comment-moderator',
                'display_name' => 'مدیر نظرات',
                'permissions' => ['comments-index', 'comments-edit', 'comments-delete'],
            ],
        ];

        // ✅ ایجاد roles و اختصاص permissions
        foreach ($roles as $roleData) {
            $role = Role::create([
                'name' => $roleData['name'],
                'display_name' => $roleData['display_name'],
                'guard_name' => 'web',
            ]);

            if ($roleData['permissions'] === 'all') {
                $role->givePermissionTo(Permission::all());
            } else {
                $role->givePermissionTo($roleData['permissions']);
            }

            $this->command->info("✅ نقش '{$role->display_name}' با " . $role->permissions->count() . " دسترسی ایجاد شد");
        }

        $this->command->info("\n🎉 تمام نقش‌ها با موفقیت ایجاد شدند!");
    }
}
