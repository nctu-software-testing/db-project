<section class="menu-section">
    <h5>商人</h5>
    <div class="list-group">
        <a class="list-group-item list-group-item-action"
           href="{{action('ProductController@getSelfProducts')}}"
        >管理商品</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('StatController@getBusinessStat')}}"
        >統計資料</a>

        <a class="list-group-item list-group-item-action"
                     href="{{action('OrderController@getShippingStatus')}}"
        >出貨狀態</a>
    </div>
</section>