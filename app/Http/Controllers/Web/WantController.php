<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ProductNew;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class WantController
 */
class WantController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $standartEquipmentMaxPrice = 5000;

        $package = request()->input('package', 'standard');

        $packageSelector = '<=';

        if ($package === 'business') {
            $packageSelector = '>';
        }

        $fullBottle = ProductNew::find(1);
        $emptyBottle = ProductNew::find(26);
        $equipments = Category::where('cid', 8)
            ->first()
            ->products()
            ->where('price', $packageSelector, $standartEquipmentMaxPrice)
            ->orderBy('position')
            ->get();

        $defaultCart = [
            'fullBottle' => [
                'qty' => 2,
                'price' => $fullBottle->price,
                'amount' => 2 * $fullBottle->price,
            ],
            'emptyBottle' => [
                'qty' => 2,
                'price' => $emptyBottle->price,
                'amount' => 2 * $emptyBottle->price,
            ],
            'amount' => 2 * $fullBottle->price + 2 * $emptyBottle->price + $equipments->first()->price,
            'qty' => 5
        ];

        return view(
            'catalog.want',
            [
                'fullBottle' => $fullBottle,
                'emptyBottle' => $emptyBottle,
                'equipments' => $equipments,
                'defaultCart' => $defaultCart,
                'package' => $package,
            ]
        );
    }

    /**
     * @param Request $request
     * @param Cart $cart
     * @return RedirectResponse
     */
    public function store(Request $request, Cart $cart): RedirectResponse
    {
        $cart->add(1, $request->input('full_count', 2));
        $cart->add(26, $request->input('empty_count', 2));
        $cart->add(
            $request->input('equipment_id', 576),
            $request->input('equipment_count', 1)
        );

        return redirect()
            ->route('cart');
    }
}
