<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\ProductNew;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Return all categories
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::get();
        return response()->json($categories);
    }


    public function getProducts($id)
    {
            return response()->json(ProductNew::where('category_id', $id)->get());
    }
}
