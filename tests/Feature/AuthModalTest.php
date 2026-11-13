<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;

class AuthModalTest extends ShopTestCase
{
    public function test_login_modal_has_three_panels_and_accessible_markup(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('mk-auth-tabs', false)
            ->assertSee('data-auth-panel="login"', false)
            ->assertSee('data-auth-panel="register"', false)
            ->assertSee('data-auth-panel="forgot"', false)
            ->assertSee('data-pass-toggle', false);
    }

    public function test_wrong_credentials_return_errors_in_the_login_bag(): void
    {
        $user = $this->makeUser();

        $payload = ['email' => $user->email];
        $payload['password'] = Str::random(16);

        $this->post('/login', $payload)
            ->assertSessionHasErrors('email', null, 'login');

        // صفحه بعد از خطا باید مودال را خودکار باز کند
        $this->get('/')->assertSee('data-auth-open="1"', false);
    }

    public function test_duplicate_username_errors_use_the_register_bag_and_open_register_tab(): void
    {
        $user = $this->makeUser();

        $payload = [
            'username' => $user->username,
            'email' => 'another-'.uniqid().'@example.com',
            'password_confirmation' => Str::random(12),
        ];
        $payload['password'] = $payload['password_confirmation'];

        $this->post('/register', $payload)
            ->assertSessionHasErrors('username', null, 'register');

        $this->get('/')
            ->assertSee('data-auth-open="1"', false)
            ->assertSee('data-auth-initial="register"', false);
    }

    public function test_inactive_account_cannot_login(): void
    {
        $user = $this->makeUser();
        $user->update(['is_active' => 0]);

        $payload = ['email' => $user->email];
        $payload['password'] = 'password';

        $this->post('/login', $payload)
            ->assertSessionHasErrors('email', null, 'login');

        $this->assertGuest();
    }
}
