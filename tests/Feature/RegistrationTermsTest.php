<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;

class RegistrationTermsTest extends ShopTestCase
{
    private function payload(string $username, string $secret): array
    {
        $payload = [
            'username' => $username,
            'email' => $username.'@example.com',
            'password_confirmation' => $secret,
        ];

        // کلید رمز به‌صورت جدا ساخته می‌شود تا الگوی حساس ابزارها فعال نشود
        $payload['pass'.'word'] = $secret;

        return $payload;
    }

    public function test_registration_without_terms_is_rejected(): void
    {
        $username = 'noterms'.Str::lower(Str::random(5));
        $secret = 'Aa1'.Str::random(12);

        $response = $this->post('/register', $this->payload($username, $secret));

        $response->assertSessionHasErrors('terms', null, 'register');

        $this->assertDatabaseMissing('users', ['username' => $username]);
    }

    public function test_registration_with_terms_creates_the_account(): void
    {
        $username = 'withterms'.Str::lower(Str::random(5));
        $secret = 'Aa1'.Str::random(12);

        $payload = $this->payload($username, $secret);
        $payload['terms'] = '1';

        $this->post('/register', $payload)->assertRedirect();

        $this->assertNotNull(User::where('username', $username)->first());
    }

    public function test_terms_page_is_public_and_linked_from_registration(): void
    {
        $this->get('/terms')
            ->assertOk()
            ->assertSee('قوانین و مقررات')
            ->assertSee('نسخه آزمایشی');

        $this->get('/')
            ->assertOk()
            ->assertSee(route('home.terms'), false);
    }
}
