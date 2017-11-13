<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\User;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/register', function () {
    return view('register');
});
Route::post('/register', function () {

    $account = request('account');
    $password = bcrypt(request('password'));
    $name= request('name');
    $role= request('role');
    $sn= request('sn');
    $gender= request('gender');
    $email= request('email');
    $birthday= request('birthday');
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
        return "已存在該用戶";
    }
    else {
        $new_user->save();
        return "註冊成功";
    }
});
