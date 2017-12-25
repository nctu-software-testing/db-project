<h6 class="dropdown-header">
    管理
</h6>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('LocationController@getLocation')}}"
>地址管理</a>

<div class="divider"></div>
<h6 class="dropdown-header">
    查詢
</h6>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('OrderController@getOrder')}}"
>我的訂單</a>

<a class="dropdown-item waves-effect waves-light"
   href="{{action('StatController@getCustomStat')}}"
>統計資料</a>
