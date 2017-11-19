@section('content')
    <button type="button"  onclick="Self('{{$data->currentPage()}}')">個人</button></td>
    <button type="button"  onclick="Public('{{$data->currentPage()}}')">公共</button></td>
    <button type="button"  onclick="Shoppingcar()">購物車</button></td>
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
              @if($type!="self")
              <td>購買</td>
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
                    @if($data[$i]->GetState()=="草稿" and $data[$i]->user_id == $uid )
                        <td>
                            <button onclick="Edit('{{$data[$i]->id}}')">編輯</button>
                        </td>
                        <td>
                            <button onclick="Release('{{$data[$i]->id}}')">發佈</button>
                        </td>
                        <td>
                            <button onclick="Delete('{{$data[$i]->id}}')">刪除</button>
                        </td>
                    @endif
                  @endif
                  @if($type!="self")
                    <td>
                        <button onclick="Buy('{{$data[$i]->id}}')">購買</button>
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
        function Shoppingcar() {
            window.open('/any_buy/public/shoppingcar ', '購物車', config='height=600,width=600');
        }
        function Edit(i) {
            location.href=location.href+"&id="+i;
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
        function Buy(id) {

                var amount=prompt("請輸入購買數量!", "1");
               if (isNaN(amount)||amount<0||!amount)  {
                    alert("請輸入正確數字");
                    return;
                }
                $.post("buy",
                    {
                        id:id,
                        amount:amount,
                    },
                    function(data){
                        location.reload();
                    });
        }
    </script>
@endsection
@include('base')