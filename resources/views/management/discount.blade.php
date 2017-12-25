@extends('management.base')
@section('content')

    <div class="card card-body">
        <h4 class="card-title">管理折扣</h4>
        <div class="card-text">
            <table class="table table-bordered table-striped table-middle">
                <thead class="blue-grey lighten-4">
                <tr>
                    　
                    <th>id</th>
                    <th>活動名稱</th>
                    <th>折扣類型</th>
                    <th>動作</th>
                    <th>開始日期</th>
                    <th>結束日期</th>
                    <th>折扣數值</th>
                    <th>優惠代碼</th>
                </tr>
                </thead>

                @foreach($data as $d)
                    <tr>
                        <td>
                            {{$d->id}}
                        </td>
                        <td>
                            {{$d->name}}
                        </td>
                        <td>
                            {{$d->type}}
                        </td>
                        <td>
                            <button onclick="DisableDiscount({{$d->id}})" type="button"  class="btn btn-red btn-sm">
                                停用
                            </button>
                        </td>
                        <td>
                            {{$d->start_discount_time}}
                        </td>
                        <td>
                            {{$d->end_discount_time}}
                        </td>
                        <td>
                            {{$d->value}}
                        </td>
                        <td>
                            {{$d->getEncodedCode()}}
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $data->appends(request()->except('page'))->render('pagination::mdb') }}
            <button onclick="CreateDiscount()" class="btn btn-amber">新增折扣</button></td>

            <div id="lo" style="display:none">
                <form action="{{action('DiscountController@createDiscount')}}" method="post">

                    折扣類型:
                    <select name="type" id="type">
                        <option value="A">A 不限品項打折</option>
                        <option value="B">B 優惠折扣</option>
                        <option value="C">C 特定分類打折</option>
                    </select>
                    折扣名稱: <input id="name" type="text" name="name" required><br>
                    <div class="discountExplain">
                        不限品項打折 ex: 0.2 表示不限品項 20% off
                    </div>
                    <input id="value" type="text" name="value" required><br>
                    <div class="discount_category" style="display: none">
                        折扣分類: <select class="mdb-select" name="category" required id="category_id"  name="category_id"></select>
                    </div>
                    <div>
                        折扣開始:
                        <input type="datetime-local" name="start_date" id="d1" required>
                    </div>
                    <div>
                        折扣結束:
                        <input type="datetime-local" name="end_date" id="d2" required>
                    </div>

                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <button type="submit" class="btn btn-rounded btn-blue-grey"><i class="fa fa-floppy-o pr-2" aria-hidden="true"></i>
                        確認新增
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('eofScript')
    <script>
        $(function () {
            let AllCategory = [];
            ajax('GET', '{{action('CategoryController@getCategory')}}', {type: 'json'})
                .then(d => AllCategory = d.result)
                .then(function () {
                    console.log('分類讀取完成');
                    console.log(AllCategory);
                    let selectEle = $("#category_id");
                    selectEle.empty();
                    AllCategory.forEach(function(d){
                        let op = $('<option></option>');
                        op.prop('value', d.id);
                        op.text(d.product_type);
                        selectEle.append(op);
                    });
                });
        });
        function DisableDiscount(id) {
            if (confirm('確定停用該折扣嗎?')) {
                ajax('POST', '{{action('DiscountController@disableDiscount')}}', {id: id})
            .then(d => {
                    if (d.success) {
                        toastr.success(d.result);
                        location.reload();
                    } else {
                        toastr.error(d.result);
                    }
                });
            }
        }
        $( "#type" ).change(function() {
            $(".discount_category").hide();
            if ($("#type").val() == 'A')
            {
                $(".discountExplain").text("不限品項打折 ex: 0.2 表示不限品項 20% off");
            }
            else if ($("#type").val() == 'B')
            {
                $(".discountExplain").text("優惠折扣 ex: 100 表示該訂單總金額 -100 元");
            }
            else if ($("#type").val() == 'C')
            {
                $(".discountExplain").text("特定分類打折 ex: 0.2 表示該分類的品項20% off");
                $(".discount_category").show();
            }
        });
        function CreateDiscount() {
            $("#lo").toggle();
        }

    </script>
@endsection