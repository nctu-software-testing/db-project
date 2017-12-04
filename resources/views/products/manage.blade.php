@extends('management.base')
@section('content')
    <!--Table-->
    <table class="table" style="word-break: keep-all">

        <!--Table head-->
        <thead class="blue-grey lighten-4">
            <tr>
                <td valign="center" align="center">編號</td>
                <td valign="center" align="center">名稱</td>
                <td valign="center" align="center">分類</td>
                <td valign="center" align="center">上架者</td>
                <td valign="center" align="center">價格</td>
                <td valign="center" align="center">上架日期</td>
                <td valign="center" align="center">下架日期</td>
                <td valign="center" align="center">狀態</td>
                <td valign="center" align="center">操作</td>
            </tr>
        </thead>
        <!--Table head-->

        <!--Table body-->
        <tbody>
            @for ($i = 0; $i < count($data); $i++)
                <tr>
                    <td align="center">{{$data[$i]->id}}</td>
                    <td align="center">
                        <a href="{{action('ProductController@getItem', ['id'=>$data[$i]->id])}}"> {{$data[$i]->	product_name}} </a>
                    </td>
                    <td valign="center" align="center">{{$data[$i]->product_type}}</td>
                    <td valign="center" align="center">{{$data[$i]->getUserName()}}</td>
                    <td valign="center" align="center">{{$data[$i]->price}}元</td>
                    <td valign="center" align="center">{{$data[$i]->start_date}}</td>
                    <td valign="center" align="center">{{$data[$i]->end_date}}</td>
                    <td valign="center" align="center">{{$data[$i]->GetState()}}</td>
                    <td valign="center" nowrap="nowrap" align="center">
                        <a href="{{action('ProductController@getSell', $data[$i]->id)}}" class="btn btn-warning">編輯</a>
                        <button onclick="Release('{{$data[$i]->id}}')" class="btn btn-primary">發佈</button>
                        <button onclick="Delete('{{$data[$i]->id}}')" class="btn btn-danger">刪除</button>
                    </td>
                </tr>
            @endfor
        </tbody>

        <!--Table body-->
    </table>
    <!--Table-->


    {{ $data->appends(request()->except('page'))->render('pagination::mdb') }}

    <a href="{{action('ProductController@getSell', 'add')}}">上架商品</a>
@endsection
@section('eofScript')
    <script>
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