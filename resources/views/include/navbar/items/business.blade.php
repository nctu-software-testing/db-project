<h6 class="dropdown-header">
    管理
</h6>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('ProductController@getSelfProducts')}}"
>管理商品</a>
<div class="divider"></div>
<h6 class="dropdown-header">
    查詢
</h6>
<a class="dropdown-item waves-effect waves-light"
   href="{{action('StatController@getBusinessStat')}}"
>統計資料</a>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('OrderController@getShippingStatus')}}"
>出貨狀態</a>
