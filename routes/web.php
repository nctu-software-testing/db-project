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
Route::post('/verification','VerificationController@postVerification');
Route::post('/newverification','VerificationController@verification');

//USER
Route::get('/userinfo', 'UserController@getUserInfo');

//CHANGE
Route::post('/changepassword','UserController@changePassword');
Route::post('/changeemail','UserController@changeEmail');

//LOCATION
Route::get('/location', 'LocationController@getLocation');
Route::post('/location', 'LocationController@createLocation');
Route::get('/verify-image/{vid}/{face}', 'VerificationController@getImage');

//CATEGORY
Route::get('/category', 'CategoryController@getCategory');
Route::post('/category', 'CategoryController@createCategory');

//PRODUCT
Route::get('/product', 'ProductController@getProduct');
Route::get('/item', 'ProductController@getItem');
Route::post('/sell', 'ProductController@sell');
Route::get('/product-image/{pid}/{id}', 'ProductController@getImage');