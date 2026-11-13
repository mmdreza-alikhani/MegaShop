<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class SeededDemoSmokeTest extends ShopTestCase
{
    public function test_storefront_pages_render_with_seeded_demo_data(): void
    {
        Artisan::call('db:seed', ['--force' => true]);

        $product = Product::query()->where('is_active', 1)->firstOrFail();
        $category = \App\Models\Category::query()->where('parent_id', '!=', 0)->firstOrFail();
        $post = \App\Models\Post::query()->firstOrFail();

        $urls = [
            '/',
            '/products',
            '/products?sortBy=lowest',
            '/products?discount=true',
            '/search?q=سامسونگ',
            '/posts',
            '/posts/'.$post->slug,
            '/contact',
            '/about-us',
            '/categories/'.$category->slug,
            '/products/'.$product->slug,
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_pages_render_with_seeded_demo_data(): void
    {
        Artisan::call('db:seed', ['--force' => true]);

        $admin = User::where('username', 'admin')->firstOrFail();
        $product = Product::query()->firstOrFail();

        $urls = [
            '/management',
            '/management/products',
            '/management/products/create',
            '/management/products/'.$product->slug,
            '/management/products/'.$product->slug.'/edit',
            '/management/users',
            '/management/orders',
            '/management/categories',
            '/management/categories/create',
            '/management/comments',
            '/management/coupons',
            '/management/banners',
            '/management/posts',
            '/management/brands',
            '/management/platforms',
            '/management/attributes',
            '/management/tags',
            '/management/roles',
            '/management/permissions',
        ];

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }
}
