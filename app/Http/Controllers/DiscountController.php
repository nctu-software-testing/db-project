<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Http\Request;

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
        return view('discount', ['data' => $data]);
    }

    public function postSetDiscount(Request $request)
    {
        $code = $request->get('code');
        $d = Discount::find($code);
        $finalCost = session('final', 0);


        if ($d) {
            $type = '';
            if ($d->type === 'A' || $d->type === 'B') {
                $type = self::TYPE_PERCENTAGE;
            } else if ($d->type === 'C') {
                $type = self::TYPE_AMOUNT;
            }
            $value = -.95;
            if ($type === self::TYPE_AMOUNT) {
                $finalCost -= $value;
            } else if ($type === self::TYPE_PERCENTAGE) {
                $finalCost -= $finalCost *= $value;
            }

            if ($finalCost < 0) $finalCost = 0;

            session()->put('discount_cost', $finalCost);
            return $this->result([
                'message' => "套用優惠成功",
                'type' => self::TYPE_PERCENTAGE,
                'value' => $value,
                'final_cost' => $finalCost,
            ], true);
        } else {
            return $this->result([
                'message' => "找不到符合條件的優惠"
            ], false);
        }

    }

}
