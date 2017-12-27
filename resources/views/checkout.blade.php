@extends('base')
@section('content')
    <div class="container">
        <form method="POST" action="{{action('ShoppingCartController@checkOut')}}">
            <div class="row">
                <div class="col-md-12 checkout-info">
                    <h1>訂單資訊</h1>
                    <table class="table table-hover shopping-table" align="center">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>產品名稱</th>
                            <th>單價</th>
                            <th>數量</th>
                            <th>小計</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 0; $i < count($data); $i++)
                            <tr>
                                <th>{{$i + 1}}</th>
                                <td>
                                    <a href="{{action('ProductController@getItem', $data[$i]->product->id)}}">{{$data[$i]->product->product_name}} </a>
                                </td>
                                <td>{{$data[$i]->product->price}}</td>
                                　
                                <td>{{$data[$i]->amount}}</td>
                                <td class="price-amount">$ {{$data[$i]->product->price * $data[$i]->amount}}</td>
                            </tr>
                        @endfor
                        <tr>
                            <th>折扣</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="product-price">-$ {{$discountAmount}}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="product-price">$ {{$final}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-md-offset-0 payment-method">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td><label for="pay_method">付款方式</label></td>
                            <td><h5>信用卡/VISA金融卡</h5></td>
                        </tr>
                        <tr>
                            <td><label for="enter_account">選擇付款帳號</label></td>
                            <td>
                                <span id="card_number_txt"></span>
                                <button
                                        id="enter_account"
                                        type="button" class="btn btn-default"
                                        data-toggle="modal" data-target="#creditCard"><i class="fa fa-plus"
                                                                                         aria-hidden="true"></i> &nbsp;&nbsp;設定信用卡資訊
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="location">配送地址</label></td>
                            <td>
                                <select class="mdb-select" name="location" required id="location">
                                    @for ($i = 0; $i < count($location); $i++)
                                        <option value={{$location[$i]->id}}>{{$location[$i]->address}}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="shipping">運費</label></td>
                            <td><h5 id="shipping">${{$shippingment}}</h5></td>             
                        </tr>
                        <tr>
                            <td><label for="price">訂單金額：</label></td>
                            <td><h5 class="price-amount" id="price"> ${{$AftershippingCostfinal}}</h5></td>
                        </tr>
                        </tbody>
                    </table>
                    <button
                            name="_token"
                            value="{{csrf_token()}}"
                            type="submit" class="btn blue-gradient btn-block btn-rounded btn-check-out"
                      >下訂單
                    </button>
                </div>
            </div>

            <div class="modal fade" id="creditCard" tabindex="-1" role="dialog" aria-labelledby="creditCardLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="creditCardLabel">信用卡資訊</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="md-form">
                                <input type="text" id="card_number" name="card_number" class="form-control">
                                <label for="card_number">信用卡號</label>
                            </div>
                            <div class="md-form">
                                <input type="text" id="cvv_number" name="cvv_number" class="form-control"
                                       maxlength="3">
                                <label for="cvv_number">卡片末三碼</label>
                            </div>
                            <div class="md-form">
                                <input type="text" id="exp_month" name="exp_month" class="form-control" >
                                <label for="exp_month">有效月 如 02 (月)</label>
                            </div>
                            <div class="md-form">
                                <input type="text" id="exp_year" name="exp_year" class="form-control">
                                <label for="exp_year">有效年 如 15 (年)</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('eofScript')
    <script>
        $("#creditCard").on('hide.bs.modal', function () {
            let number = $("#card_number").val();
            $("#card_number_txt").text(number);
        });
    </script>
@endsection