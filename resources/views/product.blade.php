@section('content')
    <table border="1">
        <tr>
            　<td>id</td>
              <td>Title</td>
              <td>Category</td>
              <td>Price</td>
              <td>上架日期</td>
              <td>下架日期</td>
              <td>狀態</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                  <td>{{$data[$i]->id}}</td>
                　<td><a href="{{$data[$i]->GetLink()}}"> {{$data[$i]->	product_name}} </a></td>
                　<td>{{$data[$i]->product_type}}</td>
                  <td>{{$data[$i]->price}}元</td>
                　<td>{{$data[$i]->expiration_date}}</td>
                　<td>{{$data[$i]->end_date}}</td>
                  <td>{{$data[$i]->GetState()}}</td>
            </tr>
        @endfor
    </table>
    {{ $data->links() }}<br>
    <button onclick="Sell()">上架商品</button></td>
    <div hidden id="lo">
        @include("sellForm")
    </div>
@endsection
@section('eofScript')
    <script>
        function Sell() {
            $("#lo").toggle();
        }
    </script>
@endsection
@include('base')