@extends('base')
@section('content')

    <div class="row">
        <div class="col">
            <!--Panel-->
            <div class="card card-body">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">購物車</h4>
                    </div>
                    <div class="col text-right">
                        <button class="btn btn-sm btn-amber" onclick="ClearShoppingCart()">清空購物車</button>
                    </div>
                </div>
                <div class="card-text">

                    <form method="POST" action="{{action('ShoppingCartController@postShoppingCart')}}">
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
                                    @forelse ($data as $d)
                                        <tr>
                                            <th>{{$loop->iteration}}</th>
                                            <td>
                                                <img class="shoppingCar-image" alt="placeholder"
                                                     src="{{action('ProductController@getImage', ['pid'=>$d->product_id, 'id'=>0])}}">
                                                <a href="{{action('ProductController@getItem', $d->product->id)}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$d->product->product_name}} </a>
                                            </td>
                                            <td>{{$d->product->price}}</td>
                                            　
                                            <td>{{$d->amount}}</td>
                                            <td class="price-amount">$ {{$d->product->price * $d->amount}}</td>
                                            <td style="text-align:center;">
                                                <button type="button" class="btn btn-success btn-sm btn-rounded"
                                                        onclick="ChangeAmount('{{$d->product_id}}','{{$d->amount}}')">
                                                    <i class="fa fa-arrows-v fa-2x pr-2" aria-hidden="true"></i>修改數量
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm btn-rounded"
                                                        onclick="Remove('{{$d->product_id}}')"><i class="fa fa-close pr-2"
                                                                                                         aria-hidden="true"></i>移出
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <p class="no-data"></p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" id="checkout_info">
                            <div class="col-md-7 offset-md-5 buy-info">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td><h5>購買總金額：</h5></td>
                                        <td><h5 class="price-amount">$ {{$final}}</h5></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="discount">
                                                <h5>折扣代碼：&nbsp;&nbsp;</h5>
                                            </label>

                                            <input id="discount"
                                                   name="discount"
                                                   type="text" class="input-alternate discount-code">&nbsp;&nbsp;&nbsp;
                                            <button
                                                    type="button" class="btn htn-sm btn-rounded btn-amber" id="discountBtn">完成
                                            </button>
                                        </td>
                                        <td><h5 id="discountValue">-$0</h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>訂單金額：</h5></td>
                                        <td><h5 class="price-amount" id="finalCost"> ${{$final}}</h5></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7 offset-md-5">
                                <button type="submit"
                                        name="_token"
                                        value="{{csrf_token()}}"
                                        class="btn blue-gradient btn-block btn-rounded btn-check-out">去買單
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('eofScript')
    @if(count($data)==0)
        <style>#checkout_info {
                display: none
            }</style>
    @endif
    <script type="text/javascript" nonce="{{$nonce}}">
        $('#discountBtn').on('click', function () {
            let discountCode = $("#discount").val();
            encryptAjax('POST', '{{action('ShoppingCartController@postSetDiscount')}}', {
                code: discountCode
            })
                .then(d => {
                    if (d.success) {
                        let preFix = d.result.type === '$' ? '$' : '';
                        let postFix = d.result.type === '%' ? '%' : '';
                        toastr.success(d.result.message);
                        $("#finalCost").text(`$${d.result.final_cost}`);
                        $("#discountValue").text(`${preFix}-${d.result.discountAmount}${postFix}`);
                    } else {
                        if(typeof d.result === 'string'){
                            toastr.error(d.result);
                        }else {
                            $("#finalCost").text(`$${d.result.final_cost}`);
                            $("#discountValue").text(0);
                            toastr.error(d.result.message);
                        }
                    }
                })
        });

        function ChangeAmount(id, a) {

            var amount = prompt("請輸入修改後數量!", a);
            if (isNaN(amount) || amount < 0 || !amount) {
                alert("請輸入正確數字");
                return;
            }
            encryptAjax('POST', '{{action('ShoppingCartController@changeAmount')}}', {
                id: id,
                amount: amount,
            }).then(function (d) {
                if (d.success) {
                    location.reload();
                } else {
                    toastr.error(d.result);
                }
            });
        }

        function Remove(id) {
            var ok = confirm("確認將商品移出購物車?");
            if (ok) {
                encryptAjax('POST', '{{action('ShoppingCartController@removeProductFromShoppingcart')}}', {
                    id: id,
                }).then(function (d) {
                    if (d.success) {
                        location.reload();
                    } else {
                        toastr.error(d.result);
                    }
                });
            }
        }

        function ClearShoppingCart() {
            if (confirm("你確定要清空嗎?")) {
                ajax('DELETE', '{{action('ShoppingCartController@deleteShoppingCart')}}')
                    .then(function (d) {
                        if (d.success) {
                            location.reload();
                        } else {
                            toastr.error(d.result);
                        }
                    })
            }
        }
    </script>
@endsection