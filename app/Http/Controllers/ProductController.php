<?php

namespace App\Http\Controllers;

use App\Category;
use App\Location;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\ProductPicture;
use App\Shipping;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;


class ProductController extends BaseController
{
    public $paginate = 12;
    private const SEARCH_KEY = ['name', 'category', 'minPrice', 'maxPrice', 'sort'];
    private const IMAGE_LIMIT = 5;

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
        $data = Product::getOnProductsBuilder($data)
            ->where('state', Product::STATE_RELEASE);
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
            $builder->where('on_product.product_name', 'LIKE', '%' . $data['name'] . '%');

        if (is_numeric($data['minPrice']))
            $builder->where('on_product.price', '>=', $data['minPrice']);

        if (is_numeric($data['maxPrice']))
            $builder->where('on_product.price', '<=', $data['maxPrice']);

        //Apply Sort
        switch (intval($data['sort'])) {
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
        $title = request('title');
        $data = Product
            ::join('category', 'on_product.category_id', '=', 'category.id')
            ->select('on_product.id', 'product_name', 'product_information', 'start_date', 'end_date', 'price', 'state', 'product_type', 'user_id');


        if (session('user.role') === 'B') {
            $uid = session('user.id');
            $data->where('user_id', $uid);
        }

        if ($title !== '') {
            $data->where('product_name', 'LIKE', '%' . $title . '%');
        }

        if (is_numeric($request->get('category'))) {
            $data->where('on_product.category_id', $request->get('category'));
        }
        $data = $data->paginate($this->paginate);
        $id = request("id", 0);
        $count = 0;
        //類別資訊
        $category = Category::get();
        return view('products.manage')
            ->with('category', $category)
            ->with('data', $data)
            ->with('id', $id)
            ->with('type', $type)
            ->with('title', '管理商品');
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
            ->with('count', $count)
            ->with('imgLimit', self::IMAGE_LIMIT);
    }

    public function getItem(Request $request, $id)
    {
        if (!$id)
            return redirect('/');
        $data = Product::
        join('category', 'on_product.category_id', '=', 'category.id')
            ->select('on_product.id', 'product_name', 'product_information', 'start_date', 'end_date', 'price', 'state', 'product_type', 'user_id', 'category_id', 'amount')
            ->selectRaw('GetSellCount(on_product.id) as sell')
            ->where('on_product.id', '=', $id)->first();

        if (
        !$data
        ) return abort(404);

        if (
            $data->state !== Product::STATE_RELEASE && //沒有發布
            !(
                session('user.id') === $data->user_id ||
                session('user.role') === 'A'
            )
        ) {
            return abort(403, 'You Can\'t See Me');
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
        $image = ProductPicture::where('product_id', $edit_id)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
        $imgCount = 0;

        $delArray = request('delImage', []);
        if (!is_array($delArray)) $delArray = [];
        for ($i = 0, $j = count($delArray); $i < $j; $i++) {
            if (($delArray[$i] ?? null) === '1' && !is_null($image[$i] ?? null)) {
                $image[$i]->delete();
                $imgCount--;
            } else {
                $image[$i]->sort = ++$imgCount;
                $image[$i]->save();
            }
        }

        //圖片
        for ($i = 0; $i < self::IMAGE_LIMIT; $i++) {
            $key = 'productImage.' . $i;
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                if ($file->isValid()) {
                    $path = $file->store('images');
                    $pp = new ProductPicture();
                    $pp->product_id = $product->id;
                    $pp->path = $path;
                    $pp->sort = ++$imgCount;
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
        $image = ProductPicture
            ::where('product_id', $pid)
            ->where('sort', $id)
            ->first();

        if ($image) {
            $imagePath = $image->path;
        }
        if (is_null($image)) {
            $imagePath = 'public/product-no-image.png';
        }
        try {
            $type = Storage::mimeType($imagePath);
            $content = (Storage::get($imagePath));
            $response = Response::make($content, 200);
            $response->header("Content-Type", $type);
            $response->header("Cache-Control", 'public, max-age=3600');
            return $response;
        } catch (FileNotFoundException $e) {
            debugbar()->error($e);
        }
        return abort(404);
    }

    public function delProduct(Request $request)
    {
        $id = request('id');
        $p = Product::where("id", $id)->first();
        $p->state = Product::STATE_DELETED;
        $p->save();

        return $this->result('ok', true);
    }

    public function releaseProduct(Request $request)
    {
        $id = request('id');
        $p = Product::where("id", $id)->first();
        $p->state = Product::STATE_RELEASE;
        $p->save();
        return $this->result('ok', true);
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
            $shoppingcart = session('shoppingcart', collect());

            $flag = false;
            for ($i = 0; $i < $shoppingcart->count(); $i++) {
                if ($shoppingcart[$i]->product->id == $id) {
                    $shoppingcart[$i]->amount += $amount;
                    $request->session()->put('shoppingcart', $shoppingcart);
                    $flag = true;
                }
            }
            if ($flag) return;
            $op = new OrderProduct();
            $op->product_id = $id;
            $op->amount = $amount;
            $product = Product::where("id", $id)->first();
            $op->product = $product;
            $shoppingcart->push($op);
            $request->session()->put('shoppingcart', $shoppingcart);
        }
    }

    public function getShoppingCart(Request $request)
    {
        $this->renewShoppingCart();
        $shoppingcart = session('shoppingcart');
        if ($request->get('type') === 'query') {
            return $this->result(count($shoppingcart), true);
        }
        $final = session()->get('final');
        return view('shopping-cart')
            ->with('data', $shoppingcart)
            ->with('final', $final);
    }

    private function renewShoppingCart()
    {
        $shoppingcart = session('shoppingcart', collect());
        $final = 0;
        for ($i = 0; $i < count($shoppingcart); $i++) {
            $final += $shoppingcart[$i]->product->price * $shoppingcart[$i]->amount;
        }
        session()->put('shoppingcart', $shoppingcart);
        session()->put('final', $final);
    }

    public function changeAmount(Request $request)
    {
        $id = request('id');
        $amount = request('amount');
        $shoppingcart = session()->get('shoppingcart');
        for ($i = 0; $i < count($shoppingcart); $i++) {
            if ($shoppingcart[$i]->product->id == $id) {
                $shoppingcart[$i]->amount = $amount;
                $request->session()->put('shoppingcart', $shoppingcart);
            }
        }
    }

    public function removeProductFromShoppingcart(Request $request)
    {
        $id = request('id');
        $shoppingcart = session()->get('shoppingcart');
        $flag = false;
        for ($i = 0; $i < count($shoppingcart) - 1; $i++) {
            if ($shoppingcart[$i]->product->id == $id) {
                $flag = true;
            }
            if ($flag) {
                $shoppingcart[$i] = $shoppingcart[$i + 1];
            }
        }
        $shoppingcart->pop();
        $request->session()->put('shoppingcart', $shoppingcart);
        return;
    }

    //結帳頁面
    public function postShoppingCart(Request $request)
    {
        $this->renewShoppingCart();
        $shoppingcart = session()->get('shoppingcart');
        $final = session()->get('final');
        $uid = $request->session()->get('user')->id;
        $location = Location::where('user_id', $uid)->get();
        return view('checkout')
            ->with('data', $shoppingcart)
            ->with('final', $final)
            ->with('location', $location)
            ->with('shippingment', $this->getShippingCost($request));
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
        $shoppingcart = session()->get('shoppingcart');
        for ($i = 0; $i < count($shoppingcart); $i++) {
            $op = new OrderProduct();
            $op->product_id = $shoppingcart[$i]->product->id;
            $op->amount = $shoppingcart[$i]->amount;
            $op->order_id = $order->id;
            $op->save();
        }
        $request->session()->forget('shoppingcart');
        $request->session()->flash('log', '成功');
        return redirect('/');
    }

    private function getShippingCost(Request $request): int
    {
        $price = $this->getCurrentPrice();
        return Shipping::getShippingPrice($price);
    }

    private function getCurrentPrice(): int
    {
        $this->renewShoppingCart();

        return session('final', 0);
    }
}

