<div id="categoryList">
    <div class="row section">
        <ul>
            <li>
                <a href="#" data-index="0">所有分類</a>
            </li>
            @foreach($category as $cat)
                <li>
                    <a href="#" data-index="{{$cat->id}}">
                        {{$cat->product_type}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>