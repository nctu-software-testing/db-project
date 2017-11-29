@section('content')
    <table border="1">
        <tr>
            　
            <td>id</td>
            <td>Title</td>
            <td>Category</td>
            <td>User</td>
            <td>Price</td>
            <td>上架日期</td>
            <td>下架日期</td>
            <td>狀態</td>
            <td>編輯</td>
            <td>發佈</td>
            <td>刪除</td>

        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                <td>{{$data[$i]->id}}</td>
                　
                <td>
                    <a href="{{action('ProductController@getItem', ['id'=>$data[$i]->id])}}"> {{$data[$i]->	product_name}} </a>
                </td>
                　
                <td>{{$data[$i]->product_type}}</td>
                <td>{{$data[$i]->getUserName()}}</td>
                <td>{{$data[$i]->price}}元</td>
                　
                <td>{{$data[$i]->start_date}}</td>
                　
                <td>{{$data[$i]->end_date}}</td>
                <td>{{$data[$i]->GetState()}}</td>
                <td>
                    <button onclick="Edit('{{$data[$i]->id}}')">編輯</button>
                </td>
                <td>
                    <button onclick="Release('{{$data[$i]->id}}')">發佈</button>
                </td>
                <td>
                    <button onclick="Delete('{{$data[$i]->id}}')">刪除</button>
                </td>
            </tr>
        @endfor
    </table>
    {{ $data->links() }}<br>
    <button type="button" onclick="Sell('add')">上架/編輯商品</button>
@endsection
@section('eofScript')
    <script>
        function Edit(i) {
            Sell(i);
        }

        function Delete(id) {
            var ok = confirm("確認刪除?");
            if (ok) {
                ajax('POST', '{{action('ProductController@delProduct')}}', {id: id})
                    .then(() => location.reload());
            }
        }

        function Release(id) {
            var ok = confirm("確認發佈? 發佈後不可進行修改即及刪除");
            if (ok) {
                ajax('POST', '{{action('ProductController@releaseProduct')}}', {id: id})
                    .then(()=>location.reload());
            }
        }
    </script>
@endsection
@include('base')