@section('content')
    <button type="button"  onclick="Self('{{$data->currentPage()}}')">個人</button></td>
    <button type="button"  onclick="Public('{{$data->currentPage()}}')">公共</button></td>
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
              @if($type=="self")
              <td>編輯</td>
              <td>發佈</td>
              <td>刪除</td>
              @endif

        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                  <td>{{$data[$i]->id}}</td>
                　<td><a href="{{$data[$i]->GetLink()}}"> {{$data[$i]->	product_name}} </a></td>
                　<td>{{$data[$i]->product_type}}</td>
                  <td>{{$data[$i]->getUserName()}}</td>
                  <td>{{$data[$i]->price}}元</td>
                　<td>{{$data[$i]->expiration_date}}</td>
                　<td>{{$data[$i]->end_date}}</td>
                  <td>{{$data[$i]->GetState()}}</td>
                  @if($type=="self")
                  <td>
                    @if($data[$i]->GetState()=="草稿" and $data[$i]->user_id == $selfid )
                        <button onclick="Edit('{{$data[$i]->id}}','{{$data->currentPage()}}')">編輯</button>
                    @endif
                  </td>
                  <td>
                     @if($data[$i]->GetState()=="草稿" and $data[$i]->user_id == $selfid )
                            <button onclick="Release('{{$data[$i]->id}}')">發佈</button>
                     @endif
                  </td>
                  <td>
                    @if($data[$i]->GetState()=="草稿" and $data[$i]->user_id == $selfid )
                        <button onclick="Delete('{{$data[$i]->id}}')">刪除</button>
                    @endif
                  </td>
                  @endif
            </tr>
        @endfor
    </table>
    {{ $data->links() }}<br>
    <button type="button"  onclick="Sell()">上架/編輯商品</button></td>
    <div hidden id="lo">
            @include("sellForm")
    </div>
@endsection
@section('eofScript')
    <script>
        @if($id!=0)
        Sell()
        @endif
        function Sell() {
            $("#lo").toggle();
        }
        function Edit(i,p) {
            location.href="/any_buy/public/product?id="+i+"&page="+p;
        }
        function Delete(id) {
            var ok=confirm("確認刪除?");
            if(ok)
            {
                $.post("deleteProduct",
                    {
                        id:id,
                    },
                    function(data){
                        location.reload();
                    });
            }
        }
        function Release(id) {
            var ok=confirm("確認發佈? 發佈後不可進行修改即及刪除");
            if(ok)
            {
                $.post("releaseProduct",
                    {
                        id:id,
                    },
                    function(data){
                        location.reload();
                    });
            }
        }
        function Self(p) {
            location.href="/any_buy/public/product?type="+"self";
        }
        function Public(p) {
            location.href="/any_buy/public/product";
        }
    </script>
@endsection
@include('base')