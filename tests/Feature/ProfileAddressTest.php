<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Province;
use App\Models\User;

class ProfileAddressTest extends ShopTestCase
{
    private function payload(array $overrides = []): array
    {
        $province = Province::firstOrCreate(['name' => 'تهران']);
        $city = City::forceCreate(['name' => 'تهران', 'province_id' => $province->id]);

        return array_merge([
            'title' => 'خانه',
            'postal_code' => '1234567890',
            'phone_number' => '9121234567',
            'province_id' => $province->id,
            'city_id' => $city->id,
            'address' => 'خیابان آزادی، پلاک ۱',
        ], $overrides);
    }

    public function test_two_users_can_each_have_an_address_named_home(): void
    {
        $first = $this->makeUser();
        $second = $this->makeUser('sara.mohammadi');
        $payload = $this->payload();

        $this->actingAs($first)->post('/profile/addresses', $payload)->assertRedirect();
        $this->actingAs($second)->post('/profile/addresses', $payload)->assertRedirect();

        $this->assertDatabaseHas('user_addresses', ['user_id' => $first->id, 'title' => 'خانه']);
        $this->assertDatabaseHas('user_addresses', ['user_id' => $second->id, 'title' => 'خانه']);
    }

    public function test_same_user_cannot_reuse_an_address_title(): void
    {
        $user = $this->makeUser();
        $payload = $this->payload();

        $this->actingAs($user)->post('/profile/addresses', $payload)->assertRedirect();
        $this->actingAs($user)->post('/profile/addresses', $payload)->assertSessionHasErrors('title', null, 'storeAddress');

        $this->assertSame(1, $user->addresses()->where('title', 'خانه')->count());
    }

    public function test_address_can_be_updated_without_changing_the_title(): void
    {
        $user = $this->makeUser();
        $payload = $this->payload();

        $this->actingAs($user)->post('/profile/addresses', $payload);
        $address = $user->addresses()->firstOrFail();

        $this->actingAs($user)
            ->put('/profile/addresses/'.$address->id, array_merge($payload, ['address' => 'نشانی جدید پلاک ۲']))
            ->assertRedirect();

        $this->assertSame('نشانی جدید پلاک ۲', $address->fresh()->address);
    }

    public function test_address_used_by_an_order_cannot_be_deleted(): void
    {
        $user = $this->makeUser();
        $address = $this->makeAddress($user);

        \App\Models\Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'total_amount' => 1000,
            'delivery_amount' => 0,
            'coupon_amount' => 0,
            'paying_amount' => 1000,
            'payment_type' => 'online',
        ]);

        $this->actingAs($user)->delete('/profile/addresses/'.$address->id)->assertRedirect();

        $this->assertDatabaseHas('user_addresses', ['id' => $address->id]);
    }
}
