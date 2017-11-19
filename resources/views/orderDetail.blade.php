@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <table border="1">
        <tr>
              <td>product_id</td>
            　<td>product_name</td>
            　<td>price</td>
              <td>amount</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->product_id}}</td>
                  <td><a href="{{$data[$i]->product->GetLink()}}"> {{$data[$i]->product->product_name}} </a></td>
                  <td>{{$data[$i]->product->price}}</td>
                　<td>{{$data[$i]->amount}}</td>
            </tr>
        @endfor
    </table>
    總價:{{$order->final_cost}}
    <br>
    訂單送達地址:{{$location->address}}
    <br>
    優惠:不知怎弄
@endsection
@section('eofScript')
@endsection
@include('base')