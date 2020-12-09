<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class Cart
{
    /**
     * @var \App\Models\Cart
     */
    private $cart;

    public function __construct(\App\Models\Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        View::share('cart', $this->cart);

        return $next($request);
    }
}
