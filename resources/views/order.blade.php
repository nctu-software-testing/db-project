@extends('base')
@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <table border="1">
        <tr>
              <td>id</td>
            　<td>state</td>
            　<td>產生日期</td>
              <td>價格</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td><a href="{{action('ProductController@getItem', $data[$i]->product->id)}}"> 編號{{$data[$i]->	GetID()}} </a></td>
                  <td>{{$data[$i]->Getstate()}}</td>
                  <td>{{$data[$i]->order_time}}</td>
                　<td>{{$data[$i]->	final_cost}}</td>
            </tr>
        @endfor
    </table>

@endsection
@section('eofScript')
@endsection