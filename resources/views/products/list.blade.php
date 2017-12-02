@extends('base')
@section('productList')
    <div id="products" class="d-flex">
        <?php $i = 0;$colNum = 4;?>
        @forelse($data as $p)
            <div class="product-wrap">
                <!--Card-->
                <a class="card" href="{{action('ProductController@getItem', $p->id)}}">
                    <!--Card image-->
                    <div class="view overlay hm-white-slight">
                        <img src="{{action('ProductController@getImage', [
                                'pid'=>$p->id,
                                'id'    => 0
                            ])}}" class="img-fluid" alt="photo">
                        <div class="mask">

                        </div>
                    </div>

                    <!--Card content-->
                    <div class="card-body">
                        <div class="product-info">
                            <h5 class="product-title">{{$p->product_name}}</h5>
                            <div class="product-price">
                                <i class="ntd">NT$</i>
                                {{$p->price}}
                            </div>
                        </div>
                        <div class="product-order-info">
                            <p>{{$p->diffBuy}}人訂購</p>
                        </div>
                    </div>

                </a>
                <!--/.Card-->
            </div>
            @if((++$i) %$colNum === 0)
                <div class="w-100"></div>
            @endif

        @empty
            <p class="no-data"></p>
        @endforelse

        @while(($i++)%$colNum!==0)
            <div class="product-wrap empty"></div>
        @endwhile
    </div>
    {{ $data->appends(request()->except('page'))->render('pagination::mdb') }}
@endsection
@section('content')
    <form action="{{action('ProductController@getProducts')}}" hidden id="searchForm">
        <?php
        foreach ($searchList as $sk) {
            $value = $search[$sk] ?? null;
            if ($value) { ?>
               <input type="hidden" name="search[{{$sk}}]" value="{{$value}}"/>
        <?php
            }
        }
        ?>
    </form>
    <div class="row">
        <div class="col-2">
            @include('products.category-list')
        </div>
        <div class="col-10">
            @yield('productList')
        </div>
    </div>

@endsection
@section('eofScript')
    <script>
        $('.price-search').on('click', function(){
            let t = $(this);
            let min = t.data('min') || -1;
            let max = t.data('max') || -1;
            let form = $("#searchForm");
            form.find('input[name="search[minPrice]"], input[name="search[maxPrice]"]').remove();

            let minInput = $('<input type="hidden" name="search[minPrice]">');
            let maxInput = $('<input type="hidden" name="search[maxPrice]">');
            minInput.val(min);
            maxInput.val(max);

            if(min>=0) form.append(minInput);
            if(max>=0) form.append(maxInput);

            form.submit();

        })
    </script>
@endsection