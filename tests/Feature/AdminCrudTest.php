<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCrudTest extends ShopTestCase
{
    private function admin(): User
    {
        $this->seed([
            \Database\Seeders\PermissionSeeder::class,
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\AdminUserSeeder::class,
        ]);

        return User::where('username', 'admin')->firstOrFail();
    }

    private function productPayload(string $title): array
    {
        $platform = Platform::create(['title' => 'پلتفرم '.uniqid(), 'is_active' => 1]);
        $category = Category::create(['title' => 'دسته '.uniqid(), 'is_active' => 1]);
        $brand = Brand::create(['title' => 'برند '.uniqid(), 'is_active' => 1]);
        $filterAttribute = Attribute::create(['title' => 'فیلتر '.uniqid()]);
        $variationAttribute = Attribute::create(['title' => 'مدل '.uniqid()]);

        // دسته باید ویژگی «تنوع» داشته باشد تا محصول بتواند variation بگیرد.
        $category->attributes()->attach($variationAttribute->id, ['type' => 'variation']);
        $category->attributes()->attach($filterAttribute->id, ['type' => 'filter']);

        return [
            'title' => $title,
            'is_active' => 1,
            'brand_id' => $brand->id,
            'platform_id' => $platform->id,
            'category_id' => $category->id,
            'description' => 'توضیحات',
            'delivery_amount' => 0,
            'delivery_amount_per_product' => 1500,
            'filters_value' => [$filterAttribute->id => 'مقدار تست'],
            'variation_values' => [
                ['value' => 'قرمز', 'price' => 100000, 'quantity' => 3, 'sku' => 'SKU-'.uniqid()],
            ],
            'tag_ids' => [],
            'primary_image' => UploadedFile::fake()->image('primary.jpg'),
            'other_images' => [UploadedFile::fake()->image('gallery1.jpg'), UploadedFile::fake()->image('gallery2.jpg')],
        ];
    }

    public function test_admin_can_create_a_product_with_gallery_and_missing_optional_fields(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $payload = $this->productPayload('محصول جدید ادمین');

        $this->actingAs($admin)
            ->post('/management/products', $payload)
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('title', 'محصول جدید ادمین')->firstOrFail();

        $this->assertSame(1500, (int) $product->delivery_amount_per_product);
        $this->assertSame(2, $product->images()->count(), 'همه تصاویر گالری باید ذخیره شوند');
        $this->assertCount(1, $product->variations);
    }

    public function test_admin_can_update_a_product_without_any_faq(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $payload = $this->productPayload('محصول قابل ویرایش');

        $this->actingAs($admin)->post('/management/products', $payload);
        $product = Product::where('title', 'محصول قابل ویرایش')->firstOrFail();
        $variation = $product->variations()->firstOrFail();

        $update = $this->productPayload('محصول ویرایش شده');
        // فرم ویرایش، تنوع‌ها را با شناسه خودشان می‌فرستد.
        $update['variation_values'] = [
            $variation->id => [
                'value' => 'آبی',
                'price' => 120000,
                'quantity' => 4,
                'sku' => $variation->sku,
            ],
        ];

        $this->actingAs($admin)
            ->put('/management/products/'.$product->slug, $update)
            ->assertRedirect(route('admin.products.edit', $product->fresh()->slug));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'محصول ویرایش شده',
        ]);

        $this->assertSame('آبی', $variation->fresh()->value);
    }

    public function test_admin_can_delete_a_product(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $product = $this->makeProduct();

        $this->actingAs($admin)
            ->delete('/management/products/'.$product->slug)
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = $this->admin();

        $secret = Str::random(12);

        $payload = [
            'username' => 'new-user',
            'first_name' => 'کاربر',
            'last_name' => 'جدید',
            'email' => 'new-user@example.com',
            'phone_number' => '9121112233',
            'is_active' => 1,
        ];
        $payload['password'] = $secret;

        $this->actingAs($admin)
            ->post('/management/users', $payload)
            ->assertRedirect();

        $user = User::where('username', 'new-user')->firstOrFail();

        $this->assertSame('new-user@example.com', $user->email);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check($secret, $user->password));
    }

    public function test_updating_a_user_without_password_keeps_the_old_password(): void
    {
        $admin = $this->admin();
        $this->seed(\Database\Seeders\UserSeeder::class);
        $target = User::where('username', 'sara.mohammadi')->firstOrFail();
        $before = $target->password;

        $this->actingAs($admin)->put('/management/users/'.$target->id, [
            'username' => $target->username,
            'first_name' => 'سارا',
            'last_name' => 'محمدی',
            'email' => $target->email,
            'phone_number' => '9123456789',
            'is_active' => 1,
        ])->assertRedirect();

        $target->refresh();

        $this->assertSame($before, $target->password);
        $this->assertSame('سارا', $target->first_name);
    }

    public function test_admin_panel_pages_load(): void
    {
        $admin = $this->admin();
        $product = $this->makeProduct();

        $this->actingAs($admin)->get('/management')->assertOk();
        $this->actingAs($admin)->get('/management/products')->assertOk();
        $this->actingAs($admin)->get('/management/products/create')->assertOk();
        $this->actingAs($admin)->get('/management/products/'.$product->slug.'/edit')->assertOk();
        $this->actingAs($admin)->get('/management/users')->assertOk();
    }
}
