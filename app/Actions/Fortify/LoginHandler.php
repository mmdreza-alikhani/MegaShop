<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;

class LoginHandler
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): ?Authenticatable
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('اطلاعات وارد شده اشتباه است.')
            ])->errorBag('login');
        }

        $request->session()->regenerate();

        $user = User::where('email', $request->email)->first();

        // حساب‌های غیرفعال اجازه ورود ندارند.
        if ($user && ! (int) $user->getRawOriginal('is_active')) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('حساب کاربری شما غیرفعال است. با پشتیبانی تماس بگیرید.'),
            ])->errorBag('login');
        }

        session()->flash('welcome', '! '.$user->username.' خوش اومدی');

        return Auth::user();
    }
}
