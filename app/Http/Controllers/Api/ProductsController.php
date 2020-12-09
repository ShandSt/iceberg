<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ProductsFindRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductNew;

class ProductsController extends Controller
{
    public function index(ProductsFindRequest $request): JsonResponse
    {
        $products = Product::where('status', '')->with('relatedProducts')->orderBy('id','desc');

        if ($request->has('category')) {
            $products->whereCategory($request->get('category'));
        }

        $products = $products->get();

        return response()->json($products);
    }

    public function special(): JsonResponse
    {
        $specialProducts = ProductNew::where('status', '=', 'special')->with('relatedProducts')->orderBy('position','asc')->get();

        return response()->json($specialProducts);
    }

    public function productsnew(Request $request): JsonResponse
    {
        $products = ProductNew::where('status', '')->with('relatedProducts')->orderBy('position','asc');

        if ($request->has('category_id')) {
            $products->where('category_id', $request->get('category_id'));
        }

        $products = $products->get();

        return response()->json($products);
    }
}
