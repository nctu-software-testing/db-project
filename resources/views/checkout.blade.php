@extends('base')
@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <div class="container">
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
                            <td class = "price-amount">$ {{$data[$i]->product->price * $data[$i]->amount}}</td>
                        </tr>
                    @endfor
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
                        <td><h5>付款方式</h5></td>
                        <td><h5>信用卡/VISA金融卡</h5></td>
                    </tr>
                    <tr>
                        <td><h5>選擇付款帳號</h5></td>
                        <td><button type="button" class="btn btn-default" data-toggle="modal" data-target="#creditCard"><i class="fa fa-plus" aria-hidden="true"></i> &nbsp;&nbsp;新增信用卡付款</button></td>
                    </tr>
                    <tr>
                        <td><h5>配送地址</h5></td>
                        <td>
                            <select  class="mdb-select" name="location" required>
                                @for ($i = 0; $i < count($location); $i++)
                                　<option value={{$location[$i]->id}}>{{$location[$i]->address}}
                                </option>
                                @endfor
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><h5>運費</h5></td>
                        <td><h5>$60</h5></td>
                    </tr>
                    <tr>
                        <td><h5>訂單金額：</h5></td>
                        <td><h5 class="price-amount"> ${{$final + 60}}</h5></td>
                    </tr>
                    </tbody>
                </table>
                <button  type="button" class="btn blue-gradient btn-block btn-rounded btn-check-out">下訂單</button>
            </div>
        </div>
    </div>

<!-- Modal -->
    <div class="modal fade" id="creditCard" tabindex="-1" role="dialog" aria-labelledby="creditCardLabel" aria-hidden="true">
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
                        <input type="text" id="form2" class="form-control">
                        <label for="form2">信用卡號</label>
                    </div>
                    <div class="md-form">
                        <input type="text" id="form2" class="form-control">
                        <label for="form2">卡片末三碼</label>
                    </div>
                    <div class="md-form">
                        <input type="text" id="form2" class="form-control">
                        <label for="form2">有效月 如 02 (月)</label>
                    </div>
                    <div class="md-form">
                        <input type="text" id="form2" class="form-control">
                        <label for="form2">有效年 如 15 (年)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<!--end modal-->

<!--
    看不懂怎麼拆這個form
    總價:{{$final}}
    <Form action="checkout" method="post">
        訂單送達地址:<select  class="mdb-select" name="location" required>
            @for ($i = 0; $i < count($location); $i++)
                　<option value={{$location[$i]->id}}>{{$location[$i]->address}}
                  </option>
            @endfor
        </select>
        <br>
        優惠:不知怎弄
        <br>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="送出">
    </Form>
    -->
@endsection
@section('eofScript')
@endsection