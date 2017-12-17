<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends BaseController
{
    public const PAGINATE = 10;

    public function __construct()
    {
        parent::__construct('location');
    }

    public function getLocation(Request $request)
    {

        if (!($request->session()->has('user')))
            return redirect()->back();
        $id = $request->session()->get('user')->id;
        $data = Location::
        where('user_id', '=', $id)
            ->paginate(self::PAGINATE);
        return view('location', ['data' => $data])
            ->with('title', '會員資料');
    }

    public function createLocation(Request $request)
    {
        $id = $request->session()->get('user')->id;
        $address = request('address');
        $zip_code = request('zipcode');
        $location = new Location();
        $location->user_id = $id;
        $location->address = $address;
        $location->zip_code = $zip_code;
        $location->save();
        $request->session()->flash('log', '建立成功');
        return redirect()->back();
    }
}
