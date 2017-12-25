<h6 class="dropdown-header">
    管理
</h6>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('AdminController@getUsersManager')}}"
>管理會員</a>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('VerificationController@getVerification')}}"
>驗證會員</a>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('CategoryController@getManageCategory')}}"
>管理分類</a>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('ProductController@getSelfProducts')}}"
>管理商品</a>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('DiscountController@getManageDiscount')}}"
>管理折扣</a>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('OrderController@getShippingStatus')}}"
>出貨狀態</a>
<a class="dropdown-item waves-effect waves-light"
   href="{{action('DiscountController@getShipping')}}"
>管理運費</a>