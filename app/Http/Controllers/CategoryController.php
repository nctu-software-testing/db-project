<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public $paginate = 10;

    public function getCategory(Request $request)
    {

        if (!($request->session()->has('user')))
            return redirect()->back();
        $data = Category::
        paginate($this->paginate);
        return view('category', ['data' => $data]);
    }

    public function createCategory(Request $request)
    {
        $product_type = request('product_type');
        $check = Category::where('product_type', '=', $product_type)->first();
        if ($check) {
            $request->session()->flash('log', '已有相同名稱之類別');
            return redirect()->back();
        }
        $category = new Category();
        $category->product_type = $product_type;
        $category->save();
        $request->session()->flash('log', '新增成功');
        return redirect()->back();
    }
}
