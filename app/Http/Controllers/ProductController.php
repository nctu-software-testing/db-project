<?php

namespace App\Http\Controllers;

use App\Category;
use App\Location;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\ProductPicture;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class ProductController extends BaseController
{
    public $paginate = 12;
    private const SEARCH_KEY = ['name', 'category', 'minPrice', 'maxPrice', 'sort'];

    public function __construct()
    {
        parent::__construct('product');
    }

    public function getProducts(Request $request)
    {
        $search = request('search', []);
        //商品資訊
        $data = Product
            ::join('category', 'on_product.category_id', '=', 'category.id')
            ->select(
                'on_product.id',
                'product_name',
                'product_information',
                'start_date',
                'end_date',
                'price',
                'state',
                'product_type'
            )
            ->selectRaw('GetDiffUserBuyProduct(on_product.id) as diffBuy');

        //公開瀏覽
        $now = new DateTime();
        $data = Product::getOnProductsBuilder($data);
        $data = $this->applySearchCond($data, $search);

        $data = $data->paginate($this->paginate);
        $id = request("id", 0);
        $count = 0;
        //類別資訊
        $category = Category::orderBy('id')->get();
        return view('products.list')
            ->with('category', $category)
            ->with('data', $data)
            ->with('id', $id)
            ->with('searchList', self::SEARCH_KEY)
            ->with('search', $search);
    }

    private function applySearchCond(Builder $builder, array $search): Builder
    {
        $data = [];
        foreach (self::SEARCH_KEY as $k)
            $data[$k] = isset($search[$k]) && is_string($search[$k]) ? trim($search[$k]) : null;

        if ($data['category'] !== null)
            $builder->where('on_product.category_id', $data['category']);

        if ($data['name'] != null)
            $builder->where('on_product.product_name', 'LIKE', '%'.$data['name'].'%');

        if(is_numeric($data['minPrice']))
            $builder->where('on_product.price', '>=', $data['minPrice']);

        if(is_numeric($data['maxPrice']))
            $builder->where('on_product.price', '<=', $data['maxPrice']);

        //Apply Sort
        switch(intval($data['sort'])){
            case 2:
                $builder->orderBy('on_product.price', 'ASC');
                break;
            case 3:
                $builder->orderBy('on_product.price', 'DESC');
                break;
            case 4:
                $builder->orderBy('diffBuy', 'DESC');
                break;
            default:
                $builder->orderBy('on_product.start_date', 'DESC');
                break;
        }
        $builder->orderBy('on_product.id', 'ASC');

        return $builder;
    }

    public function getSelfProducts(Request $request)
    {
        //商品資訊
        $type = request("type");
        $data = Product
            ::join('category', 'on_product.category_id', '=', 'category.id')
            ->select('on_product.id', 'product_name', 'product_information', 'start_date', 'end_date', 'price', 'state', 'product_type', 'user_id');

        $uid = session('user.id');
        $data->where('user_id', $uid);

        if (is_numeric($request->get('category'))) {
            $data->where('on_product.category_id', $request->get('category'));
        }
        $data = $data->paginate($this->paginate);
        $id = request("id", 0);
        $count = 0;
        //類別資訊
        $category = Category::get();
        return view('products.manage')->
        with('category', $category)->
        with('data', $data)->
        with('id', $id)->
        with('type', $type);
    }

    public function getSell(Request $request, $id)
    {
        $editdata = null;
        $count = 0;
        if ($id === 'add') {
            $editdata = new Product();
        } else {
            $id = intval($id, 10);
            $editdata = Product::
            join('category', 'on_product.category_id', '=', 'category.id')
                ->where('on_product.id', $id)
                ->get()->first();
            if (is_null($editdata)) {
                return abort(404);
            }
            $count = ProductPicture::
            where('product_id', '=', $id)
                ->count();
        }

        $category = Category::all();

        return view('products.modify')
            ->with('id', $id)
            ->with('category', $category)
            ->with('editdata', $editdata)
            ->with('count', $count);
    }

    public function getItem(Request $request, $id)
    {
        if (!$id)
            return redirect('/');
        $data = Product::
        join('category', 'on_product.category_id', '=', 'category.id')
            ->select('on_product.id', 'product_name', 'product_information', 'start_date', 'end_date', 'price', 'state', 'product_type', 'user_id')
            ->where('on_product.id', '=', $id)->first();
        if (!$data) {
            return abort(404);
        }
        //圖片數量
        $count = ProductPicture::
        where('product_id', '=', $id)
            ->count();
        return view('products.item', ['p' => $data, 'count' => $count]);
    }

    public function postSell(Request $request)
    {
        //
        $id = $request->session()->get('user')->id;
        $edit_id = request('Edit_id');
        $title = request('title');
        $category = request('category');
        $price = request('price');
        $d1 = request('start_date');
        $t1 = request('expiration_time');
        $d2 = request('end_date');
        $t2 = request('end_time');
        $dt1 = $d1 . ' ' . $t1;
        $dt2 = $d2 . ' ' . $t2;
        $info = request('info');
        if ($price < 0 || $dt1 > $dt2) {
            $request->session()->flash('log', '參數錯誤');
            return redirect()->back();
        }
        if ($edit_id == 0)
            $product = new Product();
        else
            $product = Product::Where('id', $edit_id)->first();
        $product->product_name = $title;
        $product->product_information = $info;
        $product->start_date = $dt1;
        $product->end_date = $dt2;
        $product->price = $price;
        $product->category_id = $category;
        $product->user_id = $id;
        $product->state = Product::STATE_DRAFT;
        $product->save();
        //移除圖片
        $image = ProductPicture::where('product_id', $edit_id)->get();
        for ($i = 0; $i < 5; $i++) {
            $del = request('delImage' . $i);
            if ($del == 1)
                $image[$i]->delete();
        }
        //圖片
        for ($i = 0; $i < 5; $i++) {
            if ($request->hasFile('file' . $i)) {
                $file = $request->file('file' . $i);
                if ($file->isValid()) {
                    $path[$i] = $file->store('images');
                    $pp = new ProductPicture();
                    $pp->product_id = $product->id;
                    $pp->path = $path[$i];
                    $pp->save();
                }
            }
        }
        if ($edit_id === 0)
            $request->session()->flash('log', '建立成功');
        else
            $request->session()->flash('log', '修改成功');
        return redirect()->action('ProductController@getProducts');
    }

    public function getImage($pid, $id)
    {
        $image = ProductPicture::where('product_id', $pid)->get();
        $image = $image[$id] ?? null;
        if ($image) {
            $imagePath = $image->path;
        }
        if (is_null($image) || !Storage::exists($imagePath)) {
            $imagePath = 'public/product-no-image.png';
        }
        if (Storage::exists($imagePath)) {
            $type = Storage::mimeType($imagePath);
            $content = (Storage::get($imagePath));
            $response = Response::make($content, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
        return abort(404);
    }

    public function delProduct(Request $request)
    {
        $id = request('id');
        $p = Product::where("id", $id)->first();
        $p->state = Product::STATE_DELETED;
        $p->save();
    }

    public function releaseProduct(Request $request)
    {
        $id = request('id');
        $p = Product::where("id", $id)->first();
        $p->state = Product::STATE_RELEASE;
        $p->save();
    }

    //購物車
    public function buyProduct(Request $request)
    {
        $id = request('id');
        $amount = request('amount');
        $now = new DateTime();
        $p = Product::where("id", $id)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('state', Product::STATE_RELEASE)
            ->first();
        if ($p) {
            if (($request->session()->has('shoppingcar'))) {
                $shoppingcar = session()->get('shoppingcar');
            } else {
                $shoppingcar = collect();
            }
            $flag = false;
            for ($i = 0; $i < count($shoppingcar); $i++) {
                if ($shoppingcar[$i]->product->id == $id) {
                    $shoppingcar[$i]->amount += $amount;
                    $request->session()->put('shoppingcar', $shoppingcar);
                    $flag = true;
                }
            }
            if ($flag) return;
            $op = new OrderProduct();
            $op->product_id = $id;
            $op->amount = $amount;
            $product = Product::where("id", $id)->get()->first();
            $op->product = $product;
            $shoppingcar->push($op);
            $request->session()->put('shoppingcar', $shoppingcar);
        }
    }

    public function getShoppingCar(Request $request)
    {
        $shoppingcar = session()->get('shoppingcar');
        $this->renewShoppingcar($request);
        $final = session()->get('final');
        return view('shoppingcar', ['data' => $shoppingcar], ['final' => $final]);
    }

    public function renewShoppingcar(Request $request)
    {
        if (($request->session()->has('shoppingcar'))) {
            $shoppingcar = session()->get('shoppingcar');
        } else {
            $shoppingcar = collect();
        }
        $final = 0;
        for ($i = 0; $i < count($shoppingcar); $i++) {
            $final += $shoppingcar[$i]->product->price * $shoppingcar[$i]->amount;
        }
        $request->session()->put('final', $final);
    }

    public function changeAmount(Request $request)
    {
        $id = request('id');
        $amount = request('amount');
        $shoppingcar = session()->get('shoppingcar');
        for ($i = 0; $i < count($shoppingcar); $i++) {
            if ($shoppingcar[$i]->product->id == $id) {
                $shoppingcar[$i]->amount = $amount;
                $request->session()->put('shoppingcar', $shoppingcar);
            }
        }
    }

    public function removeProductFromShoppingcar(Request $request)
    {
        $id = request('id');
        $shoppingcar = session()->get('shoppingcar');
        $flag = false;
        for ($i = 0; $i < count($shoppingcar) - 1; $i++) {
            if ($shoppingcar[$i]->product->id == $id) {
                $flag = true;
            }
            if ($flag) {
                $shoppingcar[$i] = $shoppingcar[$i + 1];
            }
        }
        $shoppingcar->pop();
        $request->session()->put('shoppingcar', $shoppingcar);
        return;
    }

    //結帳頁面
    public function getCheckOut(Request $request)
    {
        $shoppingcar = session()->get('shoppingcar');
        $this->renewShoppingcar($request);
        $final = session()->get('final');
        $uid = $request->session()->get('user')->id;
        $location = Location::where('user_id', $uid)->get();
        return view('checkout')->with('data', $shoppingcar)->
        with('final', $final)->
        with('location', $location);
    }

    public function checkOut(Request $request)
    {
        $uid = $request->session()->get('user')->id;
        $locationid = request('location');
        $final = session()->get('final');
        $location = Location::where('id', $locationid)->where('user_id', $uid)
            ->first();
        if (!$location) {
            $request->session()->flash('log', '參數錯誤');
            return redirect()->back();
        }
        $order = new Order();
        $order->location_id = $locationid;
        $order->customer_id = $uid;
        $order->state = Product::STATE_DRAFT;
        $order->final_cost = $final;
        $order->save();
        $date = date('Y-m-d H:i:s', strtotime('+1hour'));
        $order->sent_time = $date;
        $date = date('Y-m-d H:i:s', strtotime('+6hours'));
        $order->arrival_time = $date;
        $order->save();
        //裝填貨物
        $shoppingcar = session()->get('shoppingcar');
        for ($i = 0; $i < count($shoppingcar); $i++) {
            $op = new OrderProduct();
            $op->product_id = $shoppingcar[$i]->product->id;
            $op->amount = $shoppingcar[$i]->amount;
            $op->order_id = $order->id;
            $op->save();
        }
        $request->session()->forget('shoppingcar');
        $request->session()->flash('log', '成功');
        return redirect()->back();
    }
}

