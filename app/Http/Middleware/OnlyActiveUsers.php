<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OnlyActiveUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->status !== User::STATUS_ACTIVE) {
            return new JsonResponse([
                'message' => 'User not active.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
