<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductNew;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $categories = Category::orderBy('position')
            ->whereHas('products')
            ->withCount('products')
            ->get();

        $products = ProductNew::whereRaw('LOWER(name) like \'%'.mb_strtolower($request->input('q')).'%\'')
            ->paginate(12);

        return view(
            'catalog.index',
            [
                'categories' => $categories,
                'products' => $products,
                'title' => 'Результаты поиска "'.$request->input('q').'":',
                'filters' => [],
                'mainProduct' => ProductNew::find(1),
            ]
        );
    }
}
