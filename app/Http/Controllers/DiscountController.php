<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Http\Request;

class DiscountController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('discount');
    }

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
