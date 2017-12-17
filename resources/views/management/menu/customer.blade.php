<section class="menu-section">
    <h5>顧客</h5>
    <div class="list-group">
        <a class="list-group-item list-group-item-action"
           href="{{action('LocationController@getLocation')}}"
        >地址管理</a>

        <a class="list-group-item list-group-item-action"
           href="{{action('OrderController@getOrder')}}"
        >我的訂單</a>

        <a class="list-group-item list-group-item-action"
           href="#"
        >統計資料</a>
    </div>
</section>