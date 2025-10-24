<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasAnyPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->getAllPermissions()->isEmpty()) {
            abort(403, '.شما اجازه دسترسی به این بخش را ندارید');
        }
        return $next($request);
    }
}
