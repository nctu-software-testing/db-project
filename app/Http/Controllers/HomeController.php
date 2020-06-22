<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class HomeController extends BaseController
{

    public function __construct()
    {
        parent::__construct('index');
    }

    public function getIndex(Request $request)
    {

        $category = Category::orderBy('id')->get();
        $hotProducts = Product::getHotProducts();
        return view('welcome', [
            'category' => $category,
            'products' => $hotProducts
        ]);
    }

}
