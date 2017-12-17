<?php

namespace App\Http\Controllers;

use App\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('stat');
    }

    public function getCustomStat(Request $request)
    {


        return view('stat.customer')
            ->with('title', '統計資料');
    }

    public function postCustomStat(Request $request)
    {
        $type = intval(request('type', -1));
        $uid = session('user.id');
        $filterData = function($data){
            foreach($data as $d){
                $d->total_count = intval($d->total_count);
                unset($d->user_id);
            }
            return $data;
        };

        $countStr = 'SUM(`order_product`.`amount`)';
        if ($type === 1) {
            $myProducts = OrderProduct::join('order', 'order.id', 'order_product.order_id')
                ->join('on_product', 'on_product.id', 'order_product.product_id')
                ->join('category', 'category.id', 'on_product.category_id')
                ->where('order.customer_id', $uid)
                ->groupBy('on_product.category_id', 'product_type')
                ->select('on_product.category_id', 'product_type')
                ->selectRaw($countStr . ' AS `total_count`')
                ->having(DB::raw($countStr), '>', 0)
                ->get();

            $myProducts = $filterData($myProducts);
            return $this->result($myProducts, true);
        } elseif ($type === 2) {
            $result = OrderProduct::join('order', 'order.id', 'order_product.order_id')
                ->join('on_product', 'on_product.id', 'order_product.product_id')
                ->join('user', 'user.id', 'on_product.user_id')
                ->where('order.customer_id', $uid)
                ->groupBy('on_product.user_id', 'user.name')
                ->select('on_product.user_id', 'user.name')
                ->selectRaw($countStr . ' AS `total_count`')
                ->having(DB::raw($countStr), '>', 0)
                ->limit(5)
                ->get();

            $result = $filterData($result);
            return $this->result($result, true);
        }

        return $this->result([], false);
    }
}
