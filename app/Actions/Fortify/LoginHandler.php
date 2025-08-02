<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;

class LoginHandler
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('اطلاعات وارد شده اشتباه است.')
            ])->errorBag('login');
        }

        $request->session()->regenerate();

        return app(LoginResponse::class)->toResponse($request);
    }
}
