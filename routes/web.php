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

Route::group([
    'middleware' => ['auth']
], function(){
    Route::post('/logout', 'UserController@logout');

    //VERIFICATION
    Route::get('/verification', 'VerificationController@getVerification');
    Route::post('/newverification','VerificationController@verification');

    //CHANGE
    Route::post('/changepassword','UserController@changePassword');
    Route::post('/changeemail','UserController@changeEmail');

    //LOCATION
    Route::get('/location', 'LocationController@getLocation');
    Route::post('/location', 'LocationController@createLocation');
    Route::get('/verify-image/{vid}/{face}', 'VerificationController@getImage');


    //USER
    Route::get('/userinfo', 'UserController@getUserInfo');
});

Route::group([
    'middleware' => ['auth.admin']
], function(){

    Route::post('/verification','VerificationController@postVerification');
    Route::post('/category', 'CategoryController@createCategory');
});

Route::group([
    'middleware' => ['auth.non']
], function(){
    //REGISTER
    Route::get('/register', 'UserController@getReg');
    Route::post('/register', 'UserController@postReg');

    //LOGIN LOGOUT
    Route::post('/login', 'UserController@postLogin');
});



//CATEGORY
Route::get('/category', 'CategoryController@getCategory');

//PRODUCT
Route::get('/product', 'ProductController@getProduct');
Route::get('/item', 'ProductController@getItem');
Route::post('/sell', 'ProductController@sell');
Route::get('/product-image/{pid}/{id}', 'ProductController@getImage');
Route::post('/deleteProduct','ProductController@delProduct');
Route::post('/releaseProduct','ProductController@releaseProduct');
//購物車
Route::post('/buy','ProductController@buyProduct');
Route::get('/shoppingcar', 'ProductController@getShoppingcar');
Route::post('/changeAmount','ProductController@changeAmount');
Route::post('/removeProductFromShoppingcar','ProductController@removeProductFromShoppingcar');
//DISCOUNT
Route::get('/discount', 'DiscountController@getDiscount');
//CHECKOUT
Route::get('/checkout', 'ProductController@getCheckOut');
Route::post('/checkout', 'ProductController@checkOut');
//ORDER
Route::get('/order', 'OrderController@getOrder');
Route::get('/orderDetail', 'OrderController@getOrderDetail');
