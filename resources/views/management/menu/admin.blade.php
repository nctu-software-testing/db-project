<section class="menu-section">
    <h5>管理</h5>
    <div class="list-group">
        <a class="list-group-item list-group-item-action"
           href="{{action('UserController@getUserInfo')}}"
        >管理會員</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('VerificationController@getVerification')}}"
        >驗證會員</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('CategoryController@getCategory')}}"
        >管理分類</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('ProductController@getProducts')}}"
        >管理商品</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('DiscountController@getDiscount')}}"
        >管理折扣</a>
    </div>
</section>