@extends('management.base')
@section('content')
    <div class="row">
        <div class="col">
            <!--Panel-->
            <div class="card card-body">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">商品管理</h4>
                    </div>
                    <div class="col text-right">
                        <a style="margin-top: -10px;
                   margin-bottom: 15px;" href="{{action('ProductController@getSell', 'add')}}"  class="btn btn-amber waves-effect waves-light">上架商品</a>
                    </div>
                </div>
                <!--Table-->
                <table class="table table-middle table-hover table-striped table-bordered table-sm" >

                    <!--Table head-->
                    <thead class="blue-grey lighten-4">
                        <tr>
                            <th>編號</th>
                            <th>名稱</th>
                            <th>上架日期</th>
                            <th>下架日期</th>
                            <th>狀態</th>
                            <th width="290">操作</th>
                        </tr>
                    </thead>
                    <!--Table head-->

                    <!--Table body-->
                    <tbody>
                        @for ($i = 0; $i < count($data); $i++)
                            <tr>
                                <td>{{$data[$i]->id}}</td>
                                <td class="text-left">
                                    <a href="{{action('ProductController@getItem', ['id'=>$data[$i]->id])}}"> {{$data[$i]->	product_name}} </a>
                                </td>
                                <td>{{$data[$i]->start_date}}</td>
                                <td>{{$data[$i]->end_date}}</td>
                                <td>{{$data[$i]->GetState()}}</td>
                                <td nowrap="nowrap">
                                    @if($data[$i]->isAllowChange())
                                    <a href="{{action('ProductController@getSell', $data[$i]->id)}}" class="btn btn-warning btn-md">編輯</a>
                                    <button onclick="Release('{{$data[$i]->id}}')" class="btn btn-primary btn-md">發佈</button>
                                    @endif
                                    <button onclick="Delete('{{$data[$i]->id}}')" class="btn btn-danger btn-md">
                                        @if($data[$i]->isAllowChange())
                                            刪除
                                        @else
                                            下架商品
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>

                    <!--Table body-->
                </table>
                <!--Table-->
            </div>


    {{ $data->appends(request()->except('page'))->render('pagination::mdb') }}


@endsection
@section('eofScript')
    <script type="text/javascript" nonce="{{$nonce}}">
        function Delete(id) {
            var ok = confirm("確認?");
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