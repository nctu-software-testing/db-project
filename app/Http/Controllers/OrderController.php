<?php

namespace App\Http\Controllers;

use App\Location;
use App\Order;
use App\Order_Product;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class OrderController extends BaseController
{
    public $paginate = 10;

    public function getOrder(Request $request)
    {

        if (!($request->session()->has('user')))
            return redirect()->back();
        $id = $request->session()->get('user')->id;
        $data = Order::
        where('customer_id', '=', $id)
            ->paginate($this->paginate);
        return view('order', ['data' => $data]);
    }

    public function getOrderDetail(Request $request)
    {
        if (!($request->session()->has('user')))
            return redirect()->back();
        $uid = $request->session()->get('user')->id;
        $orderid = request('id');
        $order = Order::
        where('customer_id', $uid)
            ->where('id', $orderid)
            ->first();
        if (!$order)
            return abort(404);
        $data = Order_Product::
        where('order_id', $orderid)
            ->paginate($this->paginate);
        $location = Location::
        where('id', $order->location_id)
            ->first();
        for ($i = 0; $i < count($data); $i++) {
            $product = Product::where('id', $data[$i]->product_id)->first();
            $data[$i]->product = $product;
        }
        return view('orderDetail')
            ->with('data', $data)
            ->with('location', $location)
            ->with('order', $order);
    }
}
