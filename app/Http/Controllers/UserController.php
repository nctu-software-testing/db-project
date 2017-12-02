<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('user');
    }

    public function getReg(Request $request)
    {
        return view('register');
    }

    public function postReg(Request $request)
    {
        //讀表單
        $account = request('account');
        $password = bcrypt(request('password'));
        $name = request('name');
        $role = request('role');
        if (!preg_match("/^(B|C)$/", $role)) {
            return $this->result('參數錯誤', false);
        }
        $sn = request('sn');
        $gender = request('gender');
        if (!preg_match("/^(男|女)$/", $gender)) {
            return $this->result('參數錯誤', false);
        }
        $email = request('email');
        $birthday = request('birthday');
        //資料封裝
        $new_user = new User();
        $new_user->account = $account;
        $new_user->password = $password;
        $new_user->role = $role;
        $new_user->name = $name;
        $new_user->sn = $sn;
        $new_user->gender = $gender;
        $new_user->email = $email;
        $new_user->birthday = $birthday;
        $check_user = User::where('account', $account)->count();
        if ($check_user !== 0) {
            return $this->result('已有相同帳戶', false);
        } else {
            $new_user->save();
            return $this->result('註冊成功', true);
        }
    }

    public function postLogin(Request $request)
    {
        //讀表單
        $account = request('account');
        $password = request('password');
        $check_user = User::where('account', '=', $account)->first();
        if ($check_user) {
            $dbpassword = $check_user->password;
            if (Hash::check($password, $dbpassword)) {
                //TODO: Check references
                //$request->session()->put('user', json_decode($check_user->toJson()));
                $request->session()->put('user', $check_user);
                return $this->result('登入成功', true);
            }
        }
        return $this->result('登入失敗', false);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->flash('log', '登出成功');
        return redirect('/');
    }

    public function getUserInfo(Request $request)
    {
        $id = $request->session()->get('user')->id;
        $data = User::
        where('user.id', '=', $id)
            ->paginate($this->paginate);
        return view('management.user-info', ['data' => $data])
            ->with('title', '會員資料');
    }

    public function changePassword(Request $request)
    {
        $id = $request->session()->get('user')->id;
        $oldpassword = request('oldpassword');
        $check_user = User::where('id', '=', $id)->first();
        $dbpassword = $check_user->password;
        if (Hash::check($oldpassword, $dbpassword)) {
            $check_user->password = bcrypt(request('newpassword'));
            $check_user->save();
            $request->session()->flush();
            $request->session()->flash('log', '修改成功，請重新登入。');
            return redirect('/');
        } else {
            $request->session()->put('user', $check_user);
            $request->session()->flash('log', '密碼不符合');
            return redirect()->back();
        }
    }

    public function changeEmail(Request $request)
    {
        $id = $request->session()->get('user')->id;
        $email = request('email');
        $check_user = User::where('id', '=', $id)->first();
        $check_user->email = $email;
        $check_user->save();
        $request->session()->flash('log', '修改成功。');
        return redirect()->back();
    }
}
