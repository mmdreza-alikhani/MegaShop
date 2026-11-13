<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

abstract class ShopTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // بین تست‌ها، کش مجوزهای Spatie باید پاک شود (دیتابیس rollback می‌شود).
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * کاربر نمونه از seeder خود پروژه (بدون هاردکد کردن رمز در تست).
     */
    protected function makeUser(string $username = 'amir.karimi'): User
    {
        $this->seed(UserSeeder::class);

        return User::where('username', $username)->firstOrFail();
    }

    protected function makeAddress(User $user): UserAddress
    {
        $province = Province::create(['name' => 'تهران']);
        $city = City::forceCreate(['name' => 'تهران', 'province_id' => $province->id]);

        return UserAddress::create([
            'title' => 'خانه',
            'address' => 'خیابان آزادی',
            'postal_code' => '1234567890',
            'phone_number' => '09120000000',
            'user_id' => $user->id,
            'province_id' => $province->id,
            'city_id' => $city->id,
        ]);
    }

    protected function makeProduct(int $price = 100000, int $quantity = 5, array $attributes = []): Product
    {
        $category = Category::create(['title' => 'دسته '.uniqid(), 'is_active' => 1]);
        $brand = Brand::create(['title' => 'برند '.uniqid(), 'is_active' => 1]);
        $attribute = Attribute::create(['title' => 'رنگ '.uniqid()]);

        $product = Product::create(array_merge([
            'title' => 'محصول '.uniqid(),
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'primary_image' => 'test.jpg',
            'description' => 'توضیحات تست',
            'status' => 1,
            'is_active' => 1,
            'delivery_amount' => 0,
            'delivery_amount_per_product' => 0,
        ], $attributes));

        ProductVariation::create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => 'قرمز',
            'price' => $price,
            'quantity' => $quantity,
            'sku' => 'SKU-'.uniqid(),
            'sale_price' => null,
            'date_on_sale_from' => null,
            'date_on_sale_to' => null,
        ]);

        return $product->refresh();
    }

    protected function variationOf(Product $product): ProductVariation
    {
        return $product->variations()->firstOrFail();
    }
}
