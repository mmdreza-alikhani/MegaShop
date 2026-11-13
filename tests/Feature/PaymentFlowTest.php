<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentFlowTest extends ShopTestCase
{
    private function addToCart($user, $product, int $quantity = 2): void
    {
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'variation_id' => $this->variationOf($product)->id,
            'quantity' => $quantity,
        ]);
    }

    public function test_checkout_creates_order_and_redirects_to_simulator(): void
    {
        $user = $this->makeUser();
        $address = $this->makeAddress($user);
        $product = $this->makeProduct(price: 100000, quantity: 5);

        $this->addToCart($user, $product, 2);

        $response = $this->actingAs($user)->post('/payment', ['address_id' => $address->id]);

        $order = Order::where('user_id', $user->id)->firstOrFail();

        $response->assertRedirect(route('home.payment.simulator', ['order' => $order->id]));

        $this->assertSame(200000, (int) $order->paying_amount);
        $this->assertSame(200000, (int) $order->total_amount);

        $transaction = Transaction::where('order_id', $order->id)->firstOrFail();
        $this->assertNotEmpty($transaction->token);
        $this->assertSame(0, (int) $transaction->status);
    }

    public function test_successful_simulated_payment_marks_order_paid_and_decrements_stock(): void
    {
        $user = $this->makeUser();
        $address = $this->makeAddress($user);
        $product = $this->makeProduct(price: 100000, quantity: 5);
        $variation = $this->variationOf($product);

        $this->addToCart($user, $product, 2);
        $this->actingAs($user)->post('/payment', ['address_id' => $address->id]);

        $order = Order::where('user_id', $user->id)->firstOrFail();

        $this->actingAs($user)
            ->get(route('home.payment.simulator', ['order' => $order->id]))
            ->assertOk();

        $this->actingAs($user)
            ->post(route('home.payment.simulator.submit', ['order' => $order->id]))
            ->assertRedirect(route('home.profile.orders.index'));

        $order->refresh();
        $transaction = Transaction::where('order_id', $order->id)->firstOrFail();

        $this->assertSame(1, (int) $order->getRawOriginal('payment_status'));
        $this->assertSame(1, (int) $order->getRawOriginal('status'));
        $this->assertSame(1, (int) $transaction->status);
        $this->assertNotEmpty($transaction->ref_id);
        $this->assertSame(3, (int) $variation->fresh()->quantity);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_cancelled_simulated_payment_keeps_order_unpaid_and_returns_to_checkout(): void
    {
        $user = $this->makeUser();
        $address = $this->makeAddress($user);
        $product = $this->makeProduct(price: 100000, quantity: 5);

        $this->addToCart($user, $product, 2);
        $this->actingAs($user)->post('/payment', ['address_id' => $address->id]);

        $order = Order::where('user_id', $user->id)->firstOrFail();

        $this->actingAs($user)
            ->post(route('home.payment.simulator.submit', ['order' => $order->id]), ['action' => 'cancel'])
            ->assertRedirect(route('home.cart.checkout'));

        $order->refresh();

        $this->assertSame(0, (int) $order->getRawOriginal('payment_status'));
        $this->assertSame(-1, (int) $order->getRawOriginal('status'));
        $this->assertSame(2, (int) Transaction::where('order_id', $order->id)->firstOrFail()->status);
    }

    public function test_payment_cannot_use_another_users_address(): void
    {
        $user = $this->makeUser();
        $other = $this->makeUser('sara.mohammadi');
        $foreignAddress = $this->makeAddress($other);
        $product = $this->makeProduct(price: 100000, quantity: 5);

        $this->addToCart($user, $product, 1);

        $this->actingAs($user)
            ->post('/payment', ['address_id' => $foreignAddress->id])
            ->assertRedirect();

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_page_is_blocked_for_empty_cart(): void
    {
        $user = $this->makeUser();
        $this->makeAddress($user);

        $this->actingAs($user)
            ->get('/cart/checkout')
            ->assertRedirect(route('home.cart.index'));
    }

    public function test_paying_twice_does_not_create_duplicate_order(): void
    {
        $user = $this->makeUser();
        $address = $this->makeAddress($user);
        $product = $this->makeProduct(price: 100000, quantity: 5);

        $this->addToCart($user, $product, 1);

        $this->actingAs($user)->post('/payment', ['address_id' => $address->id]);
        $this->actingAs($user)->post('/payment', ['address_id' => $address->id]);

        $this->assertSame(1, Order::where('user_id', $user->id)->count());
    }
}
