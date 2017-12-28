<?php

namespace App\Http\Controllers;

use App\Location;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('order');
    }

    public function getOrder(Request $request)
    {

        if (!($request->session()->has('user')))
            return redirect()->back();
        $id = $request->session()->get('user')->id;
        $data = Order::
        where('customer_id', '=', $id)
            ->orderBy('id', 'DESC')
            ->paginate($this->paginate);
        return view('order', ['data' => $data])
            ->with('title', '我的訂單');
    }

    public function getOrderDetail(Request $request, $id)
    {
        if (!($request->session()->has('user')))
            return redirect()->back();
        $uid = $request->session()->get('user')->id;
        $order = Order::
        where('customer_id', $uid)
            ->where('id', $id)
            ->first();
        if (!$order)
            return abort(404);
        $data = OrderProduct::where('order_id', $id)->get();
        $original_cost = $order->original_cost;
        $location = Location::find($order->location_id);
        return view('orderDetail')
            ->with('data', $data)
            ->with('location', $location)
            ->with('order', $order)
            ->with('originalcost', $original_cost)
            ->with('discountAmount', $order->discountAmount())
            ->with('title', '我的訂單');
    }

    public function getShippingStatus()
    {
        $uid = session('user.id');
        $dataQuery = OrderProduct::join('on_product', 'product_id', 'on_product.id')
            ->orderBy('order_id', 'DESC')
            ->select('order_product.*');

        if (session('user.role') !== 'A') {
            $dataQuery->where('on_product.user_id', $uid);
        }

        $data = $dataQuery->paginate($this->paginate);

        return view('shipping-status')
            ->with('data', $data)
            ->with('title', '出貨狀態');
    }

}
