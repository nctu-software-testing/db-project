@extends('management.base')
@section('content')
    <div class="card card-body">
        <h4 class="card-title">出貨狀態</h4>
        <div class="card-text">
            <!--Table-->
            <table class="table table-hover table-striped table-sm">

                <!--Table head-->
                <thead class="blue-grey lighten-4">
                <tr>
                    <th width="40%">商品名稱</th>
                    <th>數量</th>
                    <th width="85">訂單日期</th>
                    <th>地址</th>
                    <th width="55">狀態</th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                @foreach($data as $d)
                    <tr class="order-row">
                        <td>{{$d->product->product_name}}</td>
                        <td>{{$d->amount}}</td>
                        <td>{{$d->order->order_time}}</td>
                        <td>({{$d->order->location->zip_code}}) {{$d->order->location->address}}</td>
                        <td>{{$d->order->GetState()}}</td>
                    </tr>
                @endforeach
                </tbody>
                <!--Table body-->
            </table>
            <!--Table-->
        {{ $data->appends(request()->except('page'))->render('pagination::mdb') }}
        </div>
    </div>
@endsection
@section('eofScript')
    <script>
    </script>
@endsection