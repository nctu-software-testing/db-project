@extends('base')
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
              <td>修改數量</td>
              <td>移出</td>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->product_id}}</td>
                  <td><a href="{{$data[$i]->product->GetLink()}}"> {{$data[$i]->product->product_name}} </a></td>
                  <td>{{$data[$i]->product->price}}</td>
                　<td>{{$data[$i]->amount}}</td>
                  <td>
                    <button onclick="ChangeAmount('{{$data[$i]->product_id}}','{{$data[$i]->amount}}')">修改數量</button>
                  </td>
                  <td>
                      <button onclick="Remove('{{$data[$i]->product_id}}')">移出</button>
                  </td>
            </tr>
        @endfor
    </table>

    總價:{{$final}}
    <button onclick="CheckOut()">結帳</button>
@endsection
@section('eofScript')
    <script>
    function ChangeAmount(id,a) {

    var amount=prompt("請輸入修改後數量!", a);
    if (isNaN(amount)||amount<0||!amount) {
    alert("請輸入正確數字");
    return;
    }
    $.post("changeAmount",
    {
    id:id,
    amount:amount,
    },
    function(data){
    location.reload();
    });
    }
    function Remove(id) {
        var ok=confirm("確認將商品移出購物車?");
        if(ok)
        {
            $.post("removeProductFromShoppingcar",
                {
                    id:id,
                },
                function(data){
                    location.reload();
                });
        }
    }
    function CheckOut() {
        location.href="/any_buy/public/checkout";
    }
    </script>
@endsection