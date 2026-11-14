<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class ProfilePagesTest extends ShopTestCase
{
    private function demoUser(): User
    {
        Artisan::call('db:seed', ['--force' => true]);

        return User::where('username', 'amir.karimi')->firstOrFail();
    }

    public function test_profile_pages_render_for_a_user(): void
    {
        $user = $this->demoUser();

        $urls = [
            route('home.profile.info'),
            route('home.profile.orders.index'),
            route('home.profile.wishlist'),
            route('home.profile.comments'),
            route('home.profile.addresses.index'),
            route('home.profile.resetPassword'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    public function test_order_page_renders_for_its_owner(): void
    {
        $user = $this->demoUser();
        $order = Order::where('user_id', $user->id)->first();

        if (! $order) {
            $this->markTestSkipped('سفارشی برای این کاربر seed نشده است.');
        }

        $this->actingAs($user)
            ->get(route('home.profile.orders.show', ['order' => $order->id]))
            ->assertOk();
    }

    public function test_profile_and_mobile_nav_show_admin_shortcut(): void
    {
        $this->seed([
            \Database\Seeders\PermissionSeeder::class,
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\AdminUserSeeder::class,
        ]);

        $admin = User::where('username', 'admin')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('home.profile.info'))
            ->assertOk()
            ->assertSee('پنل مدیریت')
            ->assertSee(route('admin.panel'), false);

        $this->actingAs($admin)
            ->get('/')
            ->assertOk()
            ->assertSee('مدیریت');
    }
}