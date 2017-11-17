<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Location;
use App\User;
use App\Verification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DiscountController extends BaseController
{
    public $paginate = 10;
    public function getDiscount(Request $request){

        if (!($request->session()->has('user')))
            return redirect()->back();
        $id=$request->session()->get('user')->id;
        $data = Discount::
        paginate($this->paginate);
        return view('discount', ['data' => $data]);
    }

}
