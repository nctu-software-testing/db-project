<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('admin');
    }

    public function getUsersManager(Request $request)
    {
        $data = User::orderBy('id')->get();
        return view('management.usersManager', ['data' => $data])
            ->with('title', '管理會員');
    }

    public function changePassword(Request $request)
    {
        $id = $request->get('id');
        $check_user = User::where('id', '=', $id)->first();
        $check_user->password = bcrypt(request('newpassword'));
        $check_user->save();
        return $this->result('修改成功。', true);
    }
}
