@extends('base')
@section('productList')
    <div id="products" class="d-flex">
        <?php $i = 0;$colNum = 4;?>
        @foreach($data as $p)

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
        @endforeach

        @while(($i++)%$colNum!==0)
            <div class="product-wrap empty"></div>
        @endwhile
    </div>

    {{ $data->render('pagination::mdb') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-2">
            @include('products.category-list')
        </div>
        <div class="col-10">
            @yield('productList')
        </div>
    </div>
    <table border="1">
        <tr>
            　
            <td>id</td>
            <td>Title</td>
            <td>Category</td>
            <td>User</td>
            <td>Price</td>
            <td>上架日期</td>
            <td>下架日期</td>
            <td>狀態</td>
            <td>購買</td>

        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                <td>{{$data[$i]->id}}</td>
                　
                <td>
                    <a href="{{action('ProductController@getItem', ['id'=>$data[$i]->id])}}"> {{$data[$i]->	product_name}} </a>
                </td>
                　
                <td>{{$data[$i]->product_type}}</td>
                <td>{{$data[$i]->price}}元</td>
                　
                <td>{{$data[$i]->start_date}}</td>
                　
                <td>{{$data[$i]->end_date}}</td>
                <td>{{$data[$i]->GetState()}}</td>
                <td>
                    <button onclick="Buy('{{$data[$i]->id}}')">購買</button>
                </td>
            </tr>
        @endfor
    </table>
    <br>
@endsection
@section('eofScript')
    <script>
        function Buy(id) {

            var amount = prompt("請輸入購買數量!", "1");
            if (isNaN(amount) || amount < 0 || !amount) {
                alert("請輸入正確數字");
                return;
            }
            $.post("buy",
                {
                    id: id,
                    amount: amount,
                },
                function (data) {
                    location.reload();
                });
        }
    </script>
@endsection