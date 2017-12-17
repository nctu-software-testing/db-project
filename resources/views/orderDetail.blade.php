@extends('management.base')
@section('content')

    <div class="card card-body">
        <h4 class="card-title">訂單資訊</h4>
        <div class="card-text">
            <!--Table-->
            <table class="table">

                <!--Table head-->
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>商品編號</th>
                    <th>商品名稱</th>
                    <th>價格</th>
                    <th>數量</th>
                    <th>小計</th>
                </tr>
                </thead>
                <!--Table head-->
                <?php $sum = 0;?>
                <!--Table body-->
                <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                        <td>{{$data[$i]->product_id}}</td>
                        <td><a href="{{action('ProductController@getItem', $data[$i]->product->id)}}"> {{$data[$i]->product->product_name}} </a></td>
                        <td class="text-right">{{$data[$i]->product->price}}</td>
                        <td>{{$data[$i]->amount}}</td>
                        <td class="text-right"><?php
                            $price = $data[$i]->product->price * $data[$i]->amount;
                            $sum+=$price;
                        ?>{{$price}}</td>
                    </tr>
                @endfor
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" style="padding: 0;line-height: 0;border-top-width: 5px"></td>
                </tr>
                <tr>
                    <th>運費</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{$order->getShippingCost()}}</td>
                </tr>
                @if($discountAmount)
                <tr>
                    <th>優惠</th>
                    <td>{{$order->discount->name}}</td>
                    <td></td>
                    <td></td>
                    <td class="text-right">-{{$order->getShippingCost()}}</td>
                </tr>
                @endif
                <tr>
                    <th>總計</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{$order->final_cost}}</td>
                </tr>
                </tfoot>
                <!--Table body-->
            </table>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="label">收貨地址</span>
                    <span class="content">{{$location->address}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="label">訂單狀態</span>
                    <span class="content">{{$order->GetState()}}</span>
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('eofScript')
@endsection