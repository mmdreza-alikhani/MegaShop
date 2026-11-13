<?php

namespace Tests\Feature;

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends ShopTestCase
{
    public function test_adding_a_product_creates_a_cart_item(): void
    {
        $user = $this->makeUser();
        $product = $this->makeProduct(price: 100000, quantity: 5);
        $variation = $this->variationOf($product);

        $this->actingAs($user)
            ->post('/cart/add', [
                'product_id' => $product->id,
                'variation_id' => $variation->id,
                'quantity' => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cart_items', [
            'itemable_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_cart_quantity_is_not_overwritten_when_reading_the_cart(): void
    {
        $user = $this->makeUser();
        $product = $this->makeProduct(price: 100000, quantity: 10);
        $variation = $this->variationOf($product);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)->get('/cart')->assertOk();
        $this->actingAs($user)->get('/cart')->assertOk();

        $this->assertDatabaseHas('cart_items', [
            'itemable_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_cart_totals_use_the_price_of_the_selected_variation(): void
    {
        $user = $this->makeUser();
        $first = $this->makeProduct(price: 100000, quantity: 5);
        $second = $this->makeProduct(price: 250000, quantity: 5);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $first->id,
            'variation_id' => $this->variationOf($first)->id,
            'quantity' => 2,
        ]);
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $second->id,
            'variation_id' => $this->variationOf($second)->id,
            'quantity' => 1,
        ]);

        // ۲ × ۱۰۰٬۰۰۰ + ۱ × ۲۵۰٬۰۰۰
        $this->assertSame(450000, cartTotalAmount());
    }

    public function test_cart_quantity_can_be_updated(): void
    {
        $user = $this->makeUser();
        $product = $this->makeProduct(price: 100000, quantity: 5);
        $variation = $this->variationOf($product);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)->put('/cart/update', [
            'products' => [$product->id => [$variation->id => 4]],
        ])->assertRedirect();

        $this->assertDatabaseHas('cart_items', [
            'itemable_id' => $product->id,
            'quantity' => 4,
        ]);
    }

    public function test_cart_item_can_be_removed_by_its_cart_item_id(): void
    {
        $user = $this->makeUser();
        $product = $this->makeProduct(price: 100000, quantity: 5);
        $variation = $this->variationOf($product);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $cartItemId = Cart::where('user_id', $user->id)->firstOrFail()->items()->firstOrFail()->id;

        $this->actingAs($user)->delete('/cart/items/'.$cartItemId)->assertRedirect();

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
    }

    public function test_cart_can_be_cleared(): void
    {
        $user = $this->makeUser();
        $product = $this->makeProduct(price: 100000, quantity: 5);
        $variation = $this->variationOf($product);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)->delete('/cart/clear')->assertRedirect();

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_adding_more_than_stock_is_rejected(): void
    {
        $user = $this->makeUser();
        $product = $this->makeProduct(price: 100000, quantity: 2);
        $variation = $this->variationOf($product);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'quantity' => 5,
        ]);

        $this->assertDatabaseCount('cart_items', 0);
    }
}
