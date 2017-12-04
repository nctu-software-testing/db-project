@extends('base')
@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <!--Table-->
        <table class="table">

            <!--Table head-->
            <thead class="blue-grey lighten-4">
                <tr>
                    <th>商品編號</th>
                    <th>商品名稱</th>
                    <th>價格</th>
                    <th>總價</th>
                </tr>
            </thead>
            <!--Table head-->

            <!--Table body-->
            <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                         <td>{{$data[$i]->product_id}}</td>
                         <td><a href="{{action('ProductController@getItem', $data[$i]->product->id)}}"> {{$data[$i]->product->product_name}} </a></td>
                         <td>{{$data[$i]->product->price}}</td>
                         <td>{{$data[$i]->amount}}</td>
                    </tr>
                @endfor
            </tbody>

            <!--Table body-->
        </table>
        總價:{{$order->final_cost}}
        <br>
        收貨人地址:{{$location->address}}
        <br>
        優惠:不知怎弄
    <!--Table-->
@endsection
@section('eofScript')
@endsection