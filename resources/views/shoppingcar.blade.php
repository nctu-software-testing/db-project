@extends('base')
@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
 <div class = "container">
     <div class="row">
         <div class="col-lg-12 col-lg-offset-0">
             <table class="table table-hover shopping-table" align="center">
                 <thead>
                 <tr>
                     <th>No</th>
                     <th>產品名稱</th>
                     <th>單價</th>
                     <th>數量</th>
                     <th>小計</th>
                     <th style="text-align:center;">更變</th>
                 </tr>
                 </thead>
                 <tbody>
                 @for ($i = 0; $i < count($data); $i++)
                 <tr>
                     <th>{{$i + 1}}</th>
                     <td>
                         <img class = "shoppingCar-image" alt="placeholder" src="{{action('ProductController@getImage', ['pid'=>$data[$i]->product_id, 'id'=>0])}}">
                         <a href="{{$data[$i]->product->GetLink()}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$data[$i]->product->product_name}} </a>
                     </td>
                     <td>{{$data[$i]->product->price}}</td>
                     　<td>{{$data[$i]->amount}}</td>
                     <td class = "price-amount">$ {{$data[$i]->product->price * $data[$i]->amount}}</td>
                     <td style="text-align:center;">
                         <button type="button" class="btn btn-success btn-sm btn-rounded" onclick="ChangeAmount('{{$data[$i]->product_id}}','{{$data[$i]->amount}}')"><i class="fa fa-arrows-v fa-2x pr-2" aria-hidden="true"></i>修改數量</button>
                         <button type="button" class="btn btn-danger btn-sm btn-rounded" onclick="Remove('{{$data[$i]->product_id}}')"><i class="fa fa-close pr-2" aria-hidden="true"></i>移出</button>
                     </td>
                 </tr>
                 @endfor
                 </tbody>
             </table>
         </div>
     </div>
     <div class = "row">
         <div class="col-md-7 offset-md-5 buy-info">
             <table class="table">
                 <tbody>
                    <tr>
                        <td><h5>購買總金額：</h5></td>
                        <td><h5 class="price-amount">$ {{$final}}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>折扣代碼：&nbsp;&nbsp;<input type="text" class="input-alternate discount-code">&nbsp;&nbsp;&nbsp;<button type="button" class="btn htn-sm btn-rounded btn-amber">完成</button></h5></td>
                        <td><h5>-$0</h5></td>
                    </tr>
                    <tr>
                        <td><h5>訂單金額：</h5></td>
                        <td><h5 class="price-amount"> ${{$final}}</h5></td>
                    </tr>
                 </tbody>
             </table>
         </div>
         <div class = "col-md-7 offset-md-5">
             <button onclick="CheckOut()" type="button" class="btn blue-gradient btn-block btn-rounded btn-check-out">去買單</button>
         </div>
     </div>
 </div>
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