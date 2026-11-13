<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;

class AdminOrderTest extends ShopTestCase
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

    private function orderFor(User $user): Order
    {
        $address = $this->makeAddress($user);

        return Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'total_amount' => 100000,
            'delivery_amount' => 0,
            'coupon_amount' => 0,
            'paying_amount' => 100000,
            'payment_type' => 'online',
        ]);
    }

    public function test_orders_index_and_show_load_without_transaction(): void
    {
        $admin = $this->admin();
        $customer = $this->makeUser();
        $order = $this->orderFor($customer);

        $this->actingAs($admin)->get('/management/orders')->assertOk()->assertSee((string) $order->id);
        $this->actingAs($admin)->get('/management/orders/'.$order->id)->assertOk();
    }

    public function test_admin_can_change_order_status_and_history_is_recorded(): void
    {
        $admin = $this->admin();
        $customer = $this->makeUser();
        $order = $this->orderFor($customer);

        $this->actingAs($admin)
            ->patch('/management/orders/'.$order->id.'/status', [
                'status' => 2,
                'note' => 'تحویل به پست',
            ])
            ->assertRedirect();

        $this->assertSame(2, (int) $order->fresh()->getRawOriginal('status'));

        $history = OrderStatusHistory::where('order_id', $order->id)->firstOrFail();

        $this->assertSame(0, (int) $history->from_status);
        $this->assertSame(2, (int) $history->to_status);
        $this->assertSame($admin->id, (int) $history->user_id);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $admin = $this->admin();
        $customer = $this->makeUser();
        $order = $this->orderFor($customer);

        $this->actingAs($admin)
            ->patch('/management/orders/'.$order->id.'/status', ['status' => 99])
            ->assertSessionHasErrors('status');

        $this->assertSame(0, (int) $order->fresh()->getRawOriginal('status'));
    }
}
