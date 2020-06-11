<?php

namespace App\Http\Controllers;

use App\Category;
use App\OrderProduct;
use App\Product;
use App\ProductPicture;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


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
        $buyCountSub = OrderProduct::select([
            'product_id',
            \DB::raw('COUNT(DISTINCT customer_id) AS cnt')
        ])
            ->join('order', 'order_id', '=', 'order.id')
            ->groupBy(['product_id']);

        //商品資訊
        $data = Product
            ::join('category', 'on_product.category_id', '=', 'category.id')
            ->leftJoin(\DB::raw('(' . $buyCountSub->getQuery()->toSql() . ') as sub'), 'sub.product_id', '=', 'on_product.id')
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
            ->selectRaw('COALESCE(sub.cnt, 0) as diffBuy');

        //公開瀏覽
        $now = Carbon::now();
        $data = Product::getOnProductsBuilder($data)
            ->where('on_product.state', Product::STATE_RELEASE);
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
            ->select('on_product.id', 'product_name', 'product_information', 'start_date', 'end_date', 'price', 'state', 'product_type', 'user_id')
            ->where('on_product.state', '!=', Product::STATE_DELETED);


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
        $data->orderBy('id', 'DESC');

        $data = $data->paginate($this->paginate);
        $id = request("id", 0);
        $count = 0;
        //類別資訊
        $category = Category::get();
        return view('management.product.manage')
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
            if (is_null($editdata) || !$editdata->isAllowChange()) {
                return abort(404);
            }
            $count = ProductPicture::
            where('product_id', '=', $id)
                ->count();
        }

        $category = Category::all();

        return view('management.product.modify')
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

        $sellCount = OrderProduct::getSellCount($id);

        $data = Product::where('on_product.id', '=', $id)
            ->join('category', 'on_product.category_id', '=', 'category.id')
            ->select('on_product.id', 'product_name', 'product_information', 'start_date', 'end_date', 'price', 'state', 'product_type', 'user_id', 'category_id', 'amount')
            ->first();

        if (!$data) return abort(404);
        $data->sell = $sellCount;

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
        $count = ProductPicture::where('product_id', '=', $id)
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
        $d2 = request('end_date');
        $amount = request('amount');
        $dt1 = $d1;
        $dt2 = $d2;
        $info = request('info');
        if ($price < 0 || $dt1 > $dt2) {
            $request->session()->flash('log', '參數錯誤');
            return redirect()->back();
        }
        $product = null;
        if ($edit_id == 0)
            $product = new Product();
        else {
            $product = Product::where('id', $edit_id);
            if (session('user.role') !== 'A') {
                $product->where('user_id', $id);
            }

            $product = $product->first();
            if (!$product || !$product->isAllowChange()) {
                return abort(404);
            }
        }
        $product->product_name = $title;
        $product->product_information = $info;
        $product->start_date = $dt1;
        $product->end_date = $dt2;
        $product->price = $price;
        $product->category_id = $category;
        $product->user_id = $id;
        $product->state = Product::STATE_DRAFT;
        $product->amount = $amount;
        $product->save();

        //移除圖片
        $image = ProductPicture::where('product_id', $edit_id)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
        $imgCount = 0;
        // sort從0開始
        $delArray = request('delImage', []);
        if (!is_array($delArray)) $delArray = [];
        for ($i = 0, $j = count($delArray); $i < $j; $i++) {
            if (($delArray[$i] ?? null) === '1' && !is_null($image[$i] ?? null)) {
                $image[$i]->forceDelete();
                $imgCount--;
            } else {
                $image[$i]->sort = $imgCount++; //從0開始
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
                    $pp->sort = $imgCount++;
                    $pp->save();
                }
            }
        }
        if ($edit_id === 0)
            $request->session()->flash('log', '建立成功');
        else
            $request->session()->flash('log', '修改成功');
        return redirect()->action('ProductController@getSelfProducts');
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
        } catch (\Exception $e) {
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

}

