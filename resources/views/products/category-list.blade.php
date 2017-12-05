<?php
$isCurrentCategory = function($id) use ($search)
{
    $curID = intval($search['category'] ?? 0);
    return ($id == $curID);
};
$activeClass = 'active border-warning amber';
?>
<div id="searchList">
    <div class="row section">
        <div class="col">
            <h5>分類</h5>
            <div class="list-group">
                <a class="list-group-item list-group-item-action {{$isCurrentCategory(0)?$activeClass:''}}"
                   href="{{action('ProductController@getProducts')}}" data-index="0"
                >所有分類</a>

                @foreach($category as $cat)
                    <a class="list-group-item list-group-item-action  {{$isCurrentCategory($cat->id)?$activeClass:''}}"
                       href="{{action('ProductController@getProducts')}}?search[category]={{$cat->id}}"
                    >{{$cat->product_type}}</a>
                @endforeach
            </div>
        </div>
    </div>


    <div class="row section">
        <div class="col">
            <h5>價錢</h5>
            <div class="list-group">
                <button class="list-group-item list-group-item-action price-search"
                        data-min="0" data-max="100"
                    >0~100</button>
                <button class="list-group-item list-group-item-action price-search"
                        data-min="101" data-max="500"
                    >101~500</button>
                <button class="list-group-item list-group-item-action price-search"
                        data-min="501" data-max="1000"
                    >501~1000</button>
                <button class="list-group-item list-group-item-action price-search"
                        data-min="1001" data-max="3000"
                    >1001~3000</button>
                <button class="list-group-item list-group-item-action price-search"
                        data-min="3001" data-max="8000"
                    >3001~8000</button>
                <button class="list-group-item list-group-item-action price-search"
                        data-min="8001"
                    >8000以上</button>
            </div>
        </div>
    </div>
</div>