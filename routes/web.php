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

Route::get('/', 'HomeController@getIndex');

Route::group([
    'middleware' => ['auth']
], function () {
    Route::post('logout', 'UserController@logout');

    //VERIFICATION
    Route::get('verification', 'VerificationController@getVerification');
    Route::post('new-verification', 'VerificationController@verification');

    //CHANGE
    Route::post('change-password', 'UserController@changePassword');
    Route::post('change-email', 'UserController@changeEmail');

    //LOCATION
    Route::get('location', 'LocationController@getLocation');
    Route::post('location', 'LocationController@createLocation');
    Route::get('verify-image/{vid}/{face}', 'VerificationController@getImage');


    //USER
    Route::get('profile', 'UserController@getUserInfo');
    Route::post('profile/avatar', 'UserController@postChangeAvatar');


    //購物車
    Route::delete('shopping-cart', 'ShoppingCartController@deleteShoppingCart');
    Route::get('shopping-cart', 'ShoppingCartController@getShoppingCart');
    Route::post('buy', 'ShoppingCartController@buyProduct');
    Route::post('changeAmount', 'ShoppingCartController@changeAmount');
    Route::post('checkout', 'ShoppingCartController@checkOut');
    Route::post('removeProductFromShoppingcart', 'ShoppingCartController@removeProductFromShoppingcart');
    Route::post('shopping-cart', 'ShoppingCartController@postShoppingCart');
    Route::post('shopping-cart/discount', 'ShoppingCartController@postSetDiscount');
});

Route::group([
    'middleware' => ['auth.admin']
], function () {

    Route::post('verification', 'VerificationController@postVerification');
    Route::get('category/manage', 'CategoryController@getManageCategory');
    Route::post('category/manage', 'CategoryController@postManageCategory');
    Route::delete('category/manage/delete', 'CategoryController@deleteCategory');

    //SHIPPING MANGE
    Route::get('shipping/manage', 'DiscountController@getShipping');
    Route::post('shipping/manage', 'DiscountController@createShipping');
    Route::delete('shipping/manage/delete', 'DiscountController@deleteShipping');


    //USER MANAGE
    Route::get('admin/users-manage', 'AdminController@getUsersManager');
    Route::post('admin/users-manage/change-password', 'AdminController@changePassword');

    //DISCOUNT
    Route::get('discount/manage', 'DiscountController@getManageDiscount');
    Route::post('discount/manage/create', 'DiscountController@createDiscount');
    Route::post('discount/manage/disable', 'DiscountController@disableDiscount');
});

Route::group([
    'middleware' => ['auth.business']
], function () {
    Route::get('products/item/{id}/edit', 'ProductController@getSell');
    Route::post('products/item/manage', 'ProductController@postSell');

    //PRODUCT
    Route::get('products/manage', 'ProductController@getSelfProducts');
    Route::post('products/manage/release-product', 'ProductController@releaseProduct');
    Route::post('products/deleteProduct', 'ProductController@delProduct');

    Route::get('order/shipping-status', 'OrderController@getShippingStatus');


    Route::get('stat/business', 'StatController@getBusinessStat');
    Route::post('stat/business', 'StatController@postBusinessStat');
});

Route::group([
    'middleware' => ['auth.customer']
], function () {
    Route::get('stat/customer', 'StatController@getCustomStat');
    Route::post('stat/customer', 'StatController@postCustomStat');

    //ORDER
    Route::get('order', 'OrderController@getOrder');
    Route::get('orderDetail/{id}', 'OrderController@getOrderDetail');
});

Route::group([
    'middleware' => ['auth.non']
], function () {
    //REGISTER
    Route::get('register', 'UserController@getReg');
    Route::post('register', 'UserController@postReg');

    //LOGIN LOGOUT
    Route::post('login', 'UserController@postLogin');
});

Route::group([
    'prefix' => 'captcha',
], function () {
    Route::get('full-image', 'CaptchaController@getFullImage');
    Route::get('masked-image', 'CaptchaController@getMaskImage');
    Route::get('slice', 'CaptchaController@getSliceImage');
    Route::post('verify', 'CaptchaController@postVerify');
    Route::get('/', 'CaptchaController@getIndex');
});


//CATEGORY
Route::get('category', 'CategoryController@getCategory');

//PRODUCT
Route::get('products', 'ProductController@getProducts');
Route::get('products/item/{id}', 'ProductController@getItem');


Route::get('function', 'HomeController@getFunction');