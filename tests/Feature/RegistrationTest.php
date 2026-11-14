<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;

class RegistrationTest extends ShopTestCase
{
    public function test_new_user_is_verified_and_can_reach_checkout_after_completing_profile(): void
    {
        $product = $this->makeProduct(price: 100000, quantity: 5);

        $secret = 'Aa1'.Str::random(12);
        $username = 'fresh'.Str::lower(Str::random(6));

        $this->post('/register', [
            'username' => $username,
            'email' => $username.'@example.com',
            'password' => $secret,
            'password_confirmation' => $secret,
            'terms' => 1,
        ])->assertRedirect();

        $user = User::where('username', $username)->firstOrFail();

        $this->assertNotNull($user->email_verified_at, 'حساب تازه باید بلافاصله تایید شود');

        // قبل از تکمیل مشخصات، تسویه کاربر را به پروفایل برمی‌گرداند.
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'variation_id' => $this->variationOf($product)->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->get('/cart/checkout')
            ->assertRedirect(route('home.profile.info'));

        // تکمیل مشخصات از صفحه پروفایل
        $this->actingAs($user)->post('/profile/update', [
            'username' => $user->username,
            'first_name' => 'نام',
            'last_name' => 'خانوادگی',
            'phone_number' => '9121234567',
            'email' => $user->email,
        ])->assertRedirect();

        $user->refresh();

        $this->assertSame('نام', $user->first_name);
        $this->assertSame('9121234567', $user->phone_number);

        $this->actingAs($user)->get('/cart/checkout')->assertOk();
    }
}
