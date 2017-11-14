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
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});
//LOGIN LOGOUT
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');

//REGISTER
Route::get('/register', 'UserController@getReg');
Route::post('/register', 'UserController@postReg');

//VERIFICATION
Route::get('/verification', 'VerificationController@getVerification');
Route::post('/verification','VerificationController@PostVerification');
Route::post('/newverification','VerificationController@Verification');

//USER
Route::get('/userinfo', 'UserController@getUserInfo');

//CHANGE
Route::post('/changepassword','UserController@ChangePassword');
Route::post('/changeemail','UserController@ChangeEmail');

//LOCATION
Route::get('/location', 'LocationController@getLocation');
Route::post('/location', 'LocationController@CreateLocation');