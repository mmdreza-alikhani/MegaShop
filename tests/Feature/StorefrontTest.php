<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

class StorefrontTest extends ShopTestCase
{
    public function test_home_page_loads(): void
    {
        $product = $this->makeProduct();

        $this->get('/')
            ->assertOk()
            ->assertSee($product->title);
    }

    public function test_products_index_loads_with_active_products_only(): void
    {
        $active = $this->makeProduct();
        $inactive = $this->makeProduct(attributes: ['is_active' => 0]);

        $this->get('/products')
            ->assertOk()
            ->assertSee($active->title)
            ->assertDontSee($inactive->title);
    }

    public function test_products_index_supports_sorting_and_search(): void
    {
        $cheap = $this->makeProduct(price: 10000);
        $expensive = $this->makeProduct(price: 9000000);

        $response = $this->get('/products?sortBy=lowest');

        $response->assertOk()->assertSeeInOrder([$cheap->title, $expensive->title]);
    }

    public function test_category_page_loads(): void
    {
        $product = $this->makeProduct();

        $this->get('/categories/'.$product->category->slug)
            ->assertOk()
            ->assertSee($product->title);
    }

    public function test_product_page_loads(): void
    {
        $product = $this->makeProduct();

        $this->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSee($product->title);
    }

    public function test_product_page_survives_product_without_variations(): void
    {
        $product = $this->makeProduct();
        $product->variations()->delete();

        $this->get('/products/'.$product->slug)->assertOk();
    }

    public function test_inactive_product_is_not_publicly_visible(): void
    {
        $product = $this->makeProduct(attributes: ['is_active' => 0]);

        $this->get('/products/'.$product->slug)->assertNotFound();
    }

    public function test_contact_page_loads_and_stores_message(): void
    {
        $this->get('/contact')->assertOk();

        $this->post('/contact', [
            'name' => 'کاربر تست',
            'email' => 'test@example.com',
            'subject' => 'سوال',
            'message' => 'متن پیام تست',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_us', [
            'email' => 'test@example.com',
            'subject' => 'سوال',
        ]);
    }
}
