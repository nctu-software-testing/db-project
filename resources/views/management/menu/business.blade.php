<section class="menu-section">
    <h5>商人</h5>
    <div class="list-group">
        <a class="list-group-item list-group-item-action"
           href="{{action('ProductController@getSelfProducts')}}"
        >管理商品</a>

        <a class="list-group-item list-group-item-action"
                     href="{{action('OrderController@getOrder')}}"
        >訂單查詢</a>
    </div>
</section>