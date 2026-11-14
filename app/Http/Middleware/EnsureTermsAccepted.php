<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * ثبت‌نام فقط با پذیرش قوانین و مقررات انجام می‌شود.
 */
class EnsureTermsAccepted
{
    public function handle(Request $request, Closure $next): Response
    {
        $isRegisterRequest = $request->isMethod('post') && $request->routeIs('register.store');

        if ($isRegisterRequest && ! $request->boolean('terms')) {
            throw ValidationException::withMessages([
                'terms' => 'برای ساخت حساب باید قوانین و مقررات را بپذیرید.',
            ])->errorBag('register');
        }

        return $next($request);
    }
}
