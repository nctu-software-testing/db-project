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
                　<td>{{$data[$i]->title}}</td>
                　<td>{{$data[$i]->product_type}}</td>
                  <td>{{$data[$i]->price}}元</td>
                　<td>{{$data[$i]->start_date}}</td>
                　<td>{{$data[$i]->end_date}}</td>
                <td>{{$data[$i]->GetState()}}</td>
            </tr>
        @endfor
    </table>
    <h1>商品介紹:</h1><br>
    <textarea rows="4" cols="50" readonly style="resize: none;">{{$data[0]->product_information}}
    </textarea>
    <h1>商品圖片:</h1><br>
    @for ($i = 0; $i < $count; $i++)
        <img class="preview{{$i}}" style="max-width: 150px; max-height: 150px;" src="product-image/{{$data[0]->id}}/{{$i}}")>
    @endfor
@endsection
@section('eofScript')

@endsection
@include('base')