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

class UserController extends BaseController
{
    public $paginate = 10;
    public function getReg(Request $request){
        return view('register');
    }
    public function postReg(Request $request){
        //讀表單
        $account = request('account');
        $password = bcrypt(request('password'));
        $name= request('name');
        $role= request('role');
        if(!preg_match("/B|C/",$role))
        {
            $request->session()->flash('log', '參數錯誤');
            return redirect()->back();
        }
        $sn= request('sn');
        $gender= request('gender');
        if(!preg_match("/男|女/",$gender))
        {
            $request->session()->flash('log', '參數錯誤');
            return redirect()->back();
        }
        $email= request('email');
        $birthday= request('birthday');
        //資料封裝
        $new_user = new User;
        $new_user->account=$account;
        $new_user->password=$password;
        $new_user->role=$role;
        $new_user->name=$name;
        $new_user->sn=$sn;
        $new_user->gender=$gender;
        $new_user->email=$email;
        $new_user->birthday=$birthday;
        $check_user=User::where('account','=',$account)->first();
        if( $check_user) {
            $request->session()->flash('log', '已有相同帳戶');
            return redirect()->back();
        }
        else {
            $new_user->save();
            $request->session()->flash('log', '註冊成功');
            return redirect()->back();
        }
    }
    public function login(Request $request)
    {
        //讀表單
        $account = request('account');
        $password = request('password');
        $check_user=User::where('account','=',$account)->first();
        if( $check_user) {
            $dbpassword = $check_user->password;
            if (Hash::check($password, $dbpassword))
            {
                $request->session()->put('user',  $check_user);
                $request->session()->flash('log', '登入成功');
                return redirect()->back();
            }
        }
        $request->session()->flash('log', '登入失敗');
        return redirect()->back();
    }
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->flash('log', '登出成功');
        return redirect('register');
    }
    public function getUserInfo(Request $request){
        if (!($request->session()->has('user')))
            return redirect()->back();
        $id=$request->session()->get('user')->id;
        if($request->session()->get('user')->role=="A") {
            $data = User::
                paginate($this->paginate);
            return view('userinfo', ['data' => $data]);
        }
        else
        {
            $data = User::
                where('user.id', '=', $id)
                ->paginate($this->paginate);
            return view('userinfo', ['data' => $data]);
        }
    }
    public function changePassword(Request $request)
    {
        $id=$request->session()->get('user')->id;
        $oldpassword = request('oldpassword');
        $check_user=User::where('id','=',$id)->first();
            $dbpassword = $check_user->password;
            if (Hash::check($oldpassword, $dbpassword))
            {
                $check_user->password=bcrypt(request('newpassword'));
                $check_user->save();
                $request->session()->flush();
                $request->session()->flash('log', '修改成功，請重新登入。');
                return redirect('register');
            }
            else
            {
                $request->session()->put('user',  $check_user);
                $request->session()->flash('log', '密碼不符合');
                return redirect()->back();
            }
    }
    public function changeEmail(Request $request)
    {
        $id=$request->session()->get('user')->id;
        $email = request('email');
        $check_user=User::where('id','=',$id)->first();
        $check_user->email=$email;
        $check_user->save();
        $request->session()->flash('log', '修改成功。');
        return redirect()->back();
    }
}
