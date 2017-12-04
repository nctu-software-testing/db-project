<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('category');
    }

    public function getCategory(Request $request)
    {
        $data = Category::orderBy('id')->get();
        return view('category', ['category' => $data]);
    }


    public function getManageCategory(Request $request)
    {
        $data = Category::orderBy('id')->get();
        return view('management.category',
            ['category' => $data])
            ->with('title', '管理分類');
    }

    public function postManageCategory(Request $request)
    {
        $id = request('id', -1);
        $product_type = request('product_type');
        $check = Category::where('product_type', '=', $product_type)
            ->where('id', '!=', $id)
            ->first();
        if ($check) {
            return $this->result('已有相同名稱之類別', false);
        }
        $category = Category::findOrNew($id);
        $category->product_type = $product_type;
        $category->save();
        return $this->result('Ok', true);
    }

    public function deleteCategory(Request $request){
        $id = request('id');
        if(Product::where('category_id', $id)->count()>0){
            return $this->result('不可以刪除有任何商品的分類', false);
        }else{
           Category::find($id)->forceDelete();
           return $this->result('OK', true);
        }
    }
}
