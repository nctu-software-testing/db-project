<?php

namespace App\Http\Controllers;

use App\Category;
use App\Location;
use App\Product;
use App\User;
use App\Verification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProductController extends BaseController
{
    public $paginate = 10;
    public function getProduct(Request $request){

        if (!($request->session()->has('user')))
            return redirect()->back();
        //商品資訊
        $data = Product::
        join('category', 'on_product.category_id', '=', 'category.id')
            ->select('on_product.id','product_name','product_information','expiration_date','end_date','price','state','product_type')
            ->paginate($this->paginate);
        //類別資訊
        $category = Category::get();
        return view('product', ['data' => $data], ['category' => $category]);
    }

    public function getItem(Request $request)
    {
        $itemid = request('id');
        if(!$itemid)
        return redirect()->back();
        $data = Product::
        join('category', 'on_product.category_id', '=', 'category.id')
            ->select('on_product.id','product_name','product_information','expiration_date','end_date','price','state','product_type')
            ->where('on_product.id','=',$itemid)
            ->paginate($this->paginate);
        return view('item', ['data' => $data]);
    }
}

