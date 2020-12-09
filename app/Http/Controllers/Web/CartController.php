<?php

namespace App\Http\Controllers\Web;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function store(Request $request, Cart $cart)
    {
        $cart->add($request->input('id'), $request->input('count'));
        
        return $cart;
    }
}
