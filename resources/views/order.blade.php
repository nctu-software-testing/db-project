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
                <tr>
                     <td  scope="row"><a href="{{action('ProductController@getItem', $data[$i]->product->id)}}" style="color:#0275d8;"> 編號{{$data[$i]->	GetID()}} </a></td>
                     <td>{{$data[$i]->Getstate()}}</td>
                     <td>{{$data[$i]->order_time}}</td>
                     <td>{{$data[$i]->	final_cost}}</td>
                </tr>
            @endfor
        </tbody>
        <!--Table body-->
    </table>
    <!--Table-->

@endsection
@section('eofScript')
@endsection