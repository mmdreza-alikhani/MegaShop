<?php

namespace Tests\Feature;

class HttpVerbTest extends ShopTestCase
{
    public function test_logout_only_accepts_post(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get('/profile/logout')->assertStatus(405);

        $this->actingAs($user)->post('/profile/logout')->assertRedirect(route('home.index'));

        $this->assertGuest();
    }

    public function test_wishlist_toggle_uses_post_and_delete(): void
    {
        $user = $this->makeUser();
        $product = $this->makeProduct();

        $this->actingAs($user)
            ->post('/products/'.$product->slug.'/wishlist')
            ->assertRedirect();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->delete('/products/'.$product->slug.'/wishlist')
            ->assertRedirect();

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_cart_remove_and_clear_reject_get_requests(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get('/cart/clear')->assertStatus(405);
        $this->actingAs($user)->get('/cart/items/1')->assertStatus(405);
    }
}
