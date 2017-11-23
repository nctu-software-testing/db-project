<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DiscountController extends BaseController
{
    public $paginate = 10;

    public function getDiscount(Request $request)
    {

        if (!($request->session()->has('user')))
            return redirect()->back();
        $id = $request->session()->get('user')->id;
        $data = Discount::
        paginate($this->paginate);
        return view('discount', ['data' => $data]);
    }

}
