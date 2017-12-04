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

Route::get('/', function () {
    $category = \App\Category::orderBy('id')->get();
    $hotProducts = \App\Product::getHotProducts();
    return view('welcome', [
        'pageName' => 'index',
        'category' => $category,
        'products' => $hotProducts
    ]);
});

Route::group([
    'middleware' => ['auth']
], function () {
    Route::post('/logout', 'UserController@logout');

    //VERIFICATION
    Route::get('/verification', 'VerificationController@getVerification');
    Route::post('/newverification', 'VerificationController@verification');

    //CHANGE
    Route::post('/changepassword', 'UserController@changePassword');
    Route::post('/changeemail', 'UserController@changeEmail');

    //LOCATION
    Route::get('/location', 'LocationController@getLocation');
    Route::post('/location', 'LocationController@createLocation');
    Route::get('/verify-image/{vid}/{face}', 'VerificationController@getImage');


    //USER
    Route::get('profile', 'UserController@getUserInfo');
    Route::post('profile/avatar', 'UserController@postChangeAvatar');
});

Route::group([
    'middleware' => ['auth.admin']
], function () {

    Route::post('/verification', 'VerificationController@postVerification');
    Route::get('/category/manage', 'CategoryController@getManageCategory');
    Route::post('/category/manage', 'CategoryController@postManageCategory');
    Route::delete('/category/manage/delete', 'CategoryController@deleteCategory');
});

Route::group([
    'middleware' => ['auth.business']
], function () {
    Route::get('/sell/{id}', 'ProductController@getSell');
    Route::post('/sell', 'ProductController@postSell');

    //PRODUCT
    Route::get('/products/edit', 'ProductController@getSelfProducts');
    Route::post('/releaseProduct', 'ProductController@releaseProduct');
    Route::post('/products/deleteProduct', 'ProductController@delProduct');
});

Route::group([
    'middleware' => ['auth.non']
], function () {
    //REGISTER
    Route::get('/register', 'UserController@getReg');
    Route::post('/register', 'UserController@postReg');

    //LOGIN LOGOUT
    Route::post('/login', 'UserController@postLogin');
});


//CATEGORY
Route::get('/category', 'CategoryController@getCategory');

//PRODUCT
Route::get('/products', 'ProductController@getProducts');
Route::get('/products/item/{id}', 'ProductController@getItem');
Route::get('/products/item-image/{pid}/{id}', 'ProductController@getImage');
//購物車
Route::post('/buy', 'ProductController@buyProduct');
Route::get('/shoppingcar', 'ProductController@getShoppingCar');
Route::post('/changeAmount', 'ProductController@changeAmount');
Route::post('/removeProductFromShoppingcar', 'ProductController@removeProductFromShoppingcar');
//DISCOUNT
Route::get('/discount', 'DiscountController@getDiscount');
//CHECKOUT
Route::get('/checkout', 'ProductController@getCheckOut');
Route::post('/checkout', 'ProductController@checkOut');
//ORDER
Route::get('/order', 'OrderController@getOrder');
Route::get('/orderDetail', 'OrderController@getOrderDetail');


Route::get('/function', function () {
    return view('function');
});