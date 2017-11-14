<?php

namespace App\Http\Controllers;

use App\User;
use App\Verification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use DateTime;
class VerificationController extends BaseController
{
    public function getVerification(Request $request){
        if (!($request->session()->has('user')))
           return redirect()->back();
        if($request->session()->get('user')->role=="A") {
            $data = DB::table('user')
                ->join('verification', 'user.id', '=', 'verification.user_id')
                ->get();
            return view('verification', ['data' => $data]);
        }
        else
        {
            $id=$request->session()->get('user')->id;
            $data = DB::table('user')
                ->join('verification', 'user.id', '=', 'verification.user_id')
                ->where('user.id','=',$id)
                ->get();
            return view('verification', ['data' => $data]);
        }
    }
    public function PostVerification(Request $request)
    {
        $id = request('id');
        $result = request('result');
        $reason = request('reason');
        $verification=Verification::where('id', '=', $id)->first();
        $verification->verify_result=$result;
        $verification->description=$reason;
        $now = new DateTime();
        $verification->datetime=$now;
        $verification->save();
        $userid = $verification->user_id;
        if($verification->verify_result=="驗證成功")
        {
            $user=User::where('id', '=', $userid)->first();
            $user->enable = 1;
            $user->save();
        }
        return;
    }
    public function Verification(Request $request)
    {
        $Verification = new Verification;
        //圖片上傳
        if ($request->hasFile('file1') and $request->hasFile('file2')) {
            if ($request->file('file1')->isValid() and $request->file('file2')->isValid()) {
                $file1 = $request->file1;
                $file2 = $request->file2;
                $path1 = $request->file1->store('images');
                $path2 = $request->file2->store('images');
                $Verification->front_picture=$path1;
                $Verification->back_picture=$path2;
                $Verification->user_id=$request->session()->get('user')->id;
                $Verification->save();
                $request->session()->flash('log', '驗證已送出，請靜待管理員審核。');
                return redirect()->back();
            }
        }
        else {
            $request->session()->flash('log', '圖片出錯');
            return redirect()->back();
        }
    }
}
