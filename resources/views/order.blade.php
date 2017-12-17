@extends('management.base')
@section('content')
    <div class="card card-body">
        <h4 class="card-title">我的訂單</h4>
        <div class="card-text">
            <!--Table-->
            <table class="table table-hover">

                <!--Table head-->
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>訂單編號</th>
                    <th>訂單狀態</th>
                    <th>產生日期</th>
                    <th>總價</th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr class="order-row">
                        <td scope="row">
                            <a href="{{action('OrderController@getOrderDetail', $data[$i]->id)}}" class="order-details">
                                {{$data[$i]->id}}
                            </a>
                        </td>
                        <td>{{$data[$i]->GetState()}}</td>
                        <td>{{$data[$i]->order_time}}</td>
                        <td>{{$data[$i]->final_cost}}</td>
                    </tr>
                @endfor
                </tbody>
                <!--Table body-->
            </table>
            <!--Table-->
        </div>
    </div>
@endsection
@section('eofScript')
    <script>
        $('.order-row').on('click', function (e) {
            location.href = this.querySelector('a.order-details').href;
        });
    </script>
@endsection