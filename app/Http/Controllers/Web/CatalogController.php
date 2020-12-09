<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductNew;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class CatalogController
 * @package App\Http\Controllers\Web
 */
class CatalogController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        if ($request->has('category')) {
            $category = Category::where('cid', $request->input('category'))->firstOrFail();
        } elseif ($request->has('tag')) {
            $tag = Tag::findOrFail($request->input('tag'));
        } else {
            $tag = Tag::notEmpty()->orderBy('position')->first();
        }

        if (isset($tag)) {
            $products = $tag->products()->orderBy('position')->paginate(16);
        } elseif (isset($category)) {
            $products = $category->products()->orderBy('position')->paginate(16);
        } else {
            $products = ProductNew::orderBy('position')->paginate(16);
        }

        $filters = [];

        if (isset($category)) {
            $filters['category'] = $category->cid;
        }
        if (isset($tag)) {
            $filters['tag'] = $tag->id;
        }

        $title = '';

        if (isset($category)) {
            $title = $category->name;
        } elseif (isset($tag)) {
            $title = $tag->name;
        }

        return view(
            'catalog.index',
            [
                'currentCategory' => isset($category) ? $category : null,
                'currentTag' => isset($tag) ? $tag : null,
                'products' => $products,
                'title' => $title,
                'filters' => $filters,
                'mainProduct' => ProductNew::find(1),
            ]
        );
    }

    /**
     * @param ProductNew $product
     * @return string
     * @throws \Throwable
     */
    public function show(ProductNew $product): string
    {
        $relatedProducts = $product->relatedProducts;

        return view(
            'catalog.product',
            [
                'product' => $product,
                'relatedProducts' => $relatedProducts,
            ]
        )->render();
    }
}
