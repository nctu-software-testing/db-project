<?php

namespace App\Http\Controllers;

use App\User;
use App\Verification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DateTime;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class VerificationController extends BaseController
{
    public $paginate = 10;
    public function getVerification(Request $request){
        if (!($request->session()->has('user')))
           return redirect()->back();
        if($request->session()->get('user')->role=="A") {
            $data = User::
                join('verification', 'user.id', '=', 'verification.user_id')
                ->paginate($this->paginate);
            return view('verification.verification', ['data' => $data]);
        }
        else
        {
            $id=$request->session()->get('user')->id;
            $data = User::
                join('verification', 'user.id', '=', 'verification.user_id')
                ->where('user.id','=',$id)
                ->paginate($this->paginate);
            return view('verification.verification', ['data' => $data]);
        }
    }
    public function postVerification(Request $request)
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
    public function verification(Request $request)
    {
        $Verification = new Verification;
        //圖片上傳
        if ($request->hasFile('file1') and $request->hasFile('file2')) {
            if ($request->file('file1')->isValid() and $request->file('file2')->isValid()) {
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
    public function getImage($vid, $face){
        $role = session('user.role');
        $verify = Verification::where('id',$vid);
        if($role!=='A'){
            $verify->where('user_id', session('user.id', -1));
        }
        $verify =$verify->first();
        if($verify){
            $imagePath = '';
            if($face==='front'){
                $imagePath = $verify->front_picture;
            }else if ($face==='back'){
                $imagePath = $verify->back_picture;
            }
            if(Storage::exists($imagePath)){
                $type = Storage::mimeType($imagePath);
                $content = (Storage::get($imagePath));


                $response = Response::make($content, 200);
                $response->header("Content-Type", $type);

                return $response;
            }
        }

        return abort(404);
    }
}
