<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SimpleAdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = config('app.admin_hash');

        if (null === $key) {
            throw new AccessDeniedHttpException("Token not defined.");
        }

        $header = $request->header('Token', '');

        if (Str::startsWith($header, 'Bearer ')) {
            $token =  Str::substr($header, 7);
        } else {
            throw new AccessDeniedHttpException;
        }

        if ($key === $token) {
            return $next($request);
        }

        throw new AccessDeniedHttpException("Access denied");
    }
}
