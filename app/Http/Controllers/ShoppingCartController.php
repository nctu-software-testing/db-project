<?php

namespace App\Http\Controllers;

use App\Catlog;
use App\Discount;
use App\Location;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\Shipping;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ShoppingCartController extends BaseController
{

    public function __construct()
    {
        parent::__construct('shopping-cart product');
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
            if (!$this->checkAllowBuy($amount, $p)) {
                return $this->result('沒有庫存了', false);
            }

            $shoppingcart = session('shoppingcart', []);
            if (!is_array($shoppingcart)) $shoppingcart = [];

            $flag = false;
            for ($i = 0, $j = count($shoppingcart); $i < $j; $i++) {
                if ($shoppingcart[$i]->product->id == $id) {
                    $shoppingcart[$i]->amount = $amount;
                    $flag = true;
                }
            }
            if (!$flag) {
                $op = new OrderProduct();
                $op->amount = $amount;
                $product = Product::find($id);
                $op->product()->associate($product);
                $shoppingcart[] = $op;
            }
            $request->session()->put('shoppingcart', $shoppingcart);

            return $this->result('OK', true);
        }
        return $this->result('WTF', false);
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

    public function deleteShoppingCart()
    {
        session()->forget(['shoppingcart', 'final']);

        return $this->result('OK', true);
    }

    private function renewShoppingCart()
    {
        $shoppingcart = session('shoppingcart', []);
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
            $p = $shoppingcart[$i]->product;
            if ($p->id == $id) {
                if (!$this->checkAllowBuy($amount, $p)) {
                    return $this->result('沒有庫存了', false);
                }

                $shoppingcart[$i]->amount = $amount;
                $request->session()->put('shoppingcart', $shoppingcart);
            }
        }
        session()->remove('discount');

        return $this->result('OK', true);
    }
    
    private function checkAllowBuy($amount,Product $p): bool{
        $curSell = OrderProduct::getSellCount($p->id);
        $remCount = $p->amount - $curSell - $amount*1;
        return $remCount >= 0;
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

        if($flag) {
            array_pop($shoppingcart);
        }
        $request->session()->put('shoppingcart', $shoppingcart);
        session()->remove('discount');
        return $this->result('OK', true);
    }

    //結帳頁面
    public function postShoppingCart(Request $request)
    {
        $this->renewShoppingCart();
        $shoppingcart = session()->get('shoppingcart');
        $uid = $request->session()->get('user')->id;
        $location = Location::where('user_id', $uid)->get();
        $discount = $request->session()->get('discount');
        $final = session()->get('final');
        $discountAmount = 0;
        if ($discount) {
            $final = $discount['final_price'];
            $discountAmount = $discount['discountAmount'];
        }
        $shippingCost = $this->getShippingCost($request);
        return view('checkout')
            ->with('data', $shoppingcart)
            ->with('final', $final)
            ->with('AftershippingCostfinal', $final + $shippingCost)
            ->with('discountAmount', $discountAmount)
            ->with('location', $location)
            ->with('shippingment', $shippingCost);
    }

    public function getAfterDiscountFinal(Request $request)
    {
        $discount = $request->session()->get('discount');
        $final = session()->get('final');
        if ($discount) {
            $final = $discount['final_price'];
        }
        return $final;
    }


    private function getShippingCost(Request $request): int
    {
        $price = $this->getAfterDiscountFinal($request);
        return Shipping::getShippingPrice($price);
    }

    public function checkOut(Request $request)
    {
        $uid = $request->session()->get('user')->id;
        $locationid = request('location');
        $discountcode = session()->get('discount')['code'];
        $final = session()->get('final');
        $location = Location::where('id', $locationid)->where('user_id', $uid)
            ->first();
        if (!$location) {
            $request->session()->flash('log', '參數錯誤');
            return redirect()->back();
        }
        $order = new Order();
        $shippingCost = $this->getShippingCost($request);
        $order->shipping_cost = $shippingCost;
        $order->location_id = $locationid;
        $order->customer_id = $uid;
        $order->state = Product::STATE_DRAFT;
        $order->original_cost = $final;
        $order->discount_id = $discountcode;
        $order->final_cost = $this->getAfterDiscountFinal($request) + $this->getShippingCost($request);
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
            $op->product()->associate($shoppingcart[$i]->product);
            $op->amount = $shoppingcart[$i]->amount;
            $op->order_id = $order->id;
            $op->save();
        }
        $request->session()->forget('shoppingcart');
        $request->session()->flash('log', '成功');
        session()->remove('discount');
        return redirect('/');
    }


    public function postSetDiscount(Request $request)
    {
        $now = new DateTime();
        $code = $request->get('code');
        $code = Discount::decrypt($code) ?? -1;
        $d = Discount::where("id", $code)
            ->where('start_discount_time', '<=', $now)
            ->where('end_discount_time', '>=', $now)->first();

        $finalCost = session('final', 0);


        if ($d) {
            $type = $d->type;
            $value = $d->value;
            $discountAmount = 0;
            //A 總價打折
            if ($type === 'A') {
                $discountAmount = $finalCost * $value;
            }
            //B 總價折扣XX元
            if ($type === 'B') {
                $discountAmount = $value;
            }
            //C 分類折扣
            if ($type === 'C') {
                $shoppingcart = session()->get('shoppingcart');
                for ($i = 0; $i < count($shoppingcart); $i++) {
                    $category = $shoppingcart[$i]->product->category_id;
                    $result = Catlog:: where('discount_id', $code)
                        ->where('category_id', $category)
                        ->first();
                    if ($result) {
                        $price = $shoppingcart[$i]->product->price;
                        $amount = $shoppingcart[$i]->amount;
                        $discountAmount += $price * $amount * $value;
                    }
                }
            }

            $discountAmount = round($discountAmount);
            $finalCost -= $discountAmount;
            if ($finalCost < 0) $finalCost = 0;

            session()->put('discount', [
                'final_price' => $finalCost,
                'discountAmount' => $discountAmount,
                'code' => $code
            ]);

            return $this->result([
                'message' => "套用優惠成功",
                'type' => $type,
                'value' => $value,
                'discountAmount' => $discountAmount,
                'final_cost' => $finalCost,
            ], true);
        } else {
            session()->remove('discount');
            return $this->result([
                'final_cost' => $finalCost,
                'message' => "找不到符合條件的優惠"
            ], false);
        }

    }
}