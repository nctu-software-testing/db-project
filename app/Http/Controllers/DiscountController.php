<?php

namespace App\Http\Controllers;

use App\Catlog;
use App\Discount;
use App\Shipping;
use Illuminate\Http\Request;
use DateTime;

class DiscountController extends BaseController
{
    public $paginate = 10;
    private const TYPE_PERCENTAGE = '%';
    private const TYPE_AMOUNT = '$';

    public function __construct()
    {
        parent::__construct('discount');
    }

    public function getManageDiscount(Request $request)
    {

        if (!($request->session()->has('user')))
            return redirect()->back();
        $id = $request->session()->get('user')->id;
        $data = Discount::
        paginate($this->paginate);
        return view('management.discount', ['data' => $data])->with('title', '管理折扣');
    }

    public function getShipping()
    {
        $data=Shipping::orderBy('lower_bound')->get();
        return view('management.shipping',['data'=>$data])->with('title', '管理運費');
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
                for ($i = 0; $i < count($shoppingcart); $i++)
                {
                    $category = $shoppingcart[$i]->product->category_id;
                    $result = Catlog:: where('discount_id', $code)
                        ->where('category_id', $category)
                        ->first();
                    if($result)
                    {
                        $price = $shoppingcart[$i]->product->price;
                        $amount= $shoppingcart[$i]->amount;
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

    public function createShipping(Request $request)
    {
        $lower_bound = request('lower_bound');
        $upper_bound = request('upper_bound');
        $price = request('price');
        $shipping = new Shipping();
        $shipping->lower_bound = $lower_bound;
        $shipping->upper_bound = $upper_bound;
        $shipping->price = $price;
        $shipping->save();
        $request->session()->flash('log', '建立成功');
        return redirect()->back();
    }
    public  function deleteShipping(Request $request)
    {
        $id=request('id');
        Shipping::find($id)->forceDelete();
        return $this->result('OK', true);
    }

    public  function createDiscount(Request $request)
    {
        $type =  $request->input('type');
        $name = request('name');
        $value = request('value');
        $selectCategory = request('category');
        $start_discount_time = $request->input('start_date');
        $end_discount_time = $request->input('end_date');
        $discount = new Discount();
        $discount->type = $type;
        $discount->name = $name;
        $discount->value = $value;
        $discount->start_discount_time = $start_discount_time;
        $discount->end_discount_time = $end_discount_time;
        $discount->save();
        if ($type == 'C')
        {
            $catlog = new Catlog();
            $catlog->discount_id = $discount->id;
            $catlog->category_id = $selectCategory;
            $catlog->save();
        }
        $request->session()->flash('log', '建立成功');
        return redirect()->back();
    }

    public  function disableDiscount(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $id = request('id');
        $discount = Discount::find($id);
        $discount->end_discount_time = $date;
        $discount->save();
        return $this->result('OK', true);
    }
}
