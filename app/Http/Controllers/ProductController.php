<?php

namespace App\Http\Controllers;

use App\Category;
use App\Location;
use App\Product;
use App\Product_Picture;
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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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
        //圖片數量
        $count = Product_Picture::
        where('product_id','=',$itemid)
            ->count();
        return view('item', ['data' => $data],['count'=> $count]);
    }
    public function sell(Request $request)
    {
        //
        $id=$request->session()->get('user')->id;
        $title = request('title');
        $category = request('category');
        $price = request('price');
        $d1=request('expiration_date');
        $t1=request('expiration_time');
        $d2=request('end_date');
        $t2=request('end_time');
        $dt1=$d1.' '.$t1;
        $dt2=$d2.' '.$t2;
        $info=request('info');
        if($price<0||$dt1>$dt2)
        {
            $request->session()->flash('log', '參數錯誤');
            return redirect()->back();
        }
        $product = new Product();
        $product->product_name=$title;
        $product->product_information=$info;
        $product->expiration_date=$dt1;
        $product->end_date=$dt2;
        $product->price=$price;
        $product->category_id=$category;
        $product->user_id=$id;
        $product->state=0;
        $product->save();
        //圖片
        for($i=1;$i<=5;$i++)
        {
            if ($request->hasFile('file'.$i)) {
                $file = $request->file('file'.$i);
                if ($file->isValid()) {
                    $path[$i] = $file->store('images');
                    $pp=new Product_Picture();
                    $pp->product_id= $product->id;
                    $pp->path=$path[$i];
                    $pp->save();
                }
            }
        }
        $request->session()->flash('log', '建立成功');
        return redirect()->back();
    }
    public function getImage($pid, $id){
        $image = Product_Picture::where('product_id',$pid)->get();
        $image = $image[$id];
        if($image){
            $imagePath = $image->path;
            if(Storage::exists($imagePath)){
                $type = Storage::mimeType($imagePath);
                $content = (Storage::get($imagePath));
                $response = Response::make($content, 200);
                $response->header("Content-Type", $type);
                return $response;
            }
        }
        return abort(404);
    }
}

