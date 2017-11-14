<?php

namespace App\Http\Controllers;

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

class LocationController extends BaseController
{
    public function getLocation(Request $request){
        if (!($request->session()->has('user')))
            return redirect()->back();
        $id=$request->session()->get('user')->id;
        $data = DB::table('location')
            ->where('user_id','=',$id)
            ->get();
        return view('location', ['data' => $data]);
    }
    public function CreateLocation(Request $request)
    {
        $id=$request->session()->get('user')->id;
        $address = request('address');
        $zip_code = request('zip_code');
        $location = new Location();
        $location->user_id=$id;
        $location->address=$address;
        $location->zip_code=$zip_code;
        $location->save();
        $request->session()->flash('log', '建立成功');
        return redirect()->back();
    }
}
