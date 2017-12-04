<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('admin');
    }

    public function getReg(Request $request)
    {
        return view('register');
    }

    public function getUsersManager(Request $request)
    {
        $data = User::orderBy('id')->get();
        return view('management.usersManager', ['data' => $data])
            ->with('title', '管理會員');
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
                $this->updateUser($check_user);
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
        $id = session('user.id');
        $data = User::find($id);
        if(!$data){
            return $this->logout($request);
        }

        return view('management.user-info', ['data' => $data])
            ->with('title', '會員資料');
    }

    public function changePassword(Request $request)
    {
        $id = $request->get('id');
        $check_user = User::where('id', '=', $id)->first();
        $check_user->password = bcrypt(request('newpassword'));
        $check_user->save();
        return $this->result('修改成功。', true);
    }

    public function postChangeAvatar(Request $request){
        $id = session('user.id');
        $user = User::find($id);
        $avatar = $request->get('avatar');
        if(!is_string($avatar)) $avatar = '';
        $user->avatar = trim($avatar);
        $this->updateUser($user);

        return $this->result([
            'url'   => $user->getAvatarUrl()
        ], true);
    }

    public function changeEmail(Request $request)
    {
        $id = $request->session()->get('user')->id;
        $email = request('email');
        $check_user = User::where('id', '=', $id)->first();
        $check_user->email = $email;
        $this->updateUser($check_user);
        return $this->result('修改成功', true);
    }

    private function updateUser(User $user) : User
    {
        $user->save();
        session()->put('user', $user);

        return $user;
    }
}
