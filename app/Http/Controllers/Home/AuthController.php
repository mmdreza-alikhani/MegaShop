<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, DB};
use Laravel\Socialite\Facades\Socialite;
use Log;
use Random\RandomException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class AuthController extends Controller
{
    public function redirectToProvider(string $provider): SymfonyRedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, string $provider): RedirectResponse
    {
        try {
            $socialiteUser = Socialite::driver($provider)->stateless()->user();

            $user = DB::transaction(function () use ($socialiteUser, $provider) {
                $user = User::where('email', $socialiteUser->getEmail())->first();
                $isNewUser = !$user;

                if ($isNewUser) {
                    $user = $this->createUserFromSocialite($socialiteUser, $provider);
                }

                return ['user' => $user, 'isNewUser' => $isNewUser];
            });

            auth()->login($user['user']);
            $request->session()->regenerate();

            $message = $user['isNewUser']
                ? "! {$user['user']->username} خوش اومدی"
                : "! {$user['user']->username} خوش برگشتی";

            flash()->success($message);

            return redirect()->intended();

        } catch (Exception $e) {
            Log::error('Socialite authentication failed', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);

            flash()->error('ورود با مشکل مواجه شد. لطفاً دوباره تلاش کنید.');
            return redirect()->route('login');
        }
    }

    /**
     * @throws RandomException
     */
    private function createUserFromSocialite($socialiteUser, string $provider): User
    {
        $baseUsername = $socialiteUser->getName() ?? $socialiteUser->getNickname() ?? 'Guest';
        $username = $this->generateUniqueUsername($baseUsername);

        return User::create([
            'username' => $username,
            'first_name' => $socialiteUser->user['given_name'] ?? null,
            'last_name' => $socialiteUser->user['family_name'] ?? null,
            'email' => $socialiteUser->getEmail(),
            'avatar' => $socialiteUser->getAvatar(),
            'password' => Hash::make($socialiteUser->getId()),
            'provider_name' => $provider,
            'email_verified_at' => Carbon::now(),
        ]);
    }

    /**
     * @throws RandomException
     */
    private function generateUniqueUsername(string $baseUsername): string
    {
        if (!User::where('username', $baseUsername)->exists()) {
            return $baseUsername;
        }

        return $baseUsername . '-' . random_int(1000, 9999);
    }
}
