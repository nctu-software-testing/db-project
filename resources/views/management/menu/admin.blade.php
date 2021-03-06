<section class="menu-section">
    <h5>管理</h5>
    <div class="list-group">
        <a class="list-group-item list-group-item-action"
           href="{{action('AdminController@getUsersManager')}}"
        >管理會員</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('VerificationController@getVerification')}}"
        >驗證會員</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('CategoryController@getManageCategory')}}"
        >管理分類</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('ProductController@getSelfProducts')}}"
         >管理商品</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('DiscountController@getManageDiscount')}}"
        >管理折扣</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('OrderController@getShippingStatus')}}"
        >出貨狀態</a>
        <a class="list-group-item list-group-item-action"
           href="{{action('DiscountController@getShipping')}}"
        >管理運費</a>
    </div>
</section>