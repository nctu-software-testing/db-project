@extends('management.base')
@section('content')

    <div class="card card-body">
        <h4 class="card-title">管理折扣</h4>
        <div class="card-text">
            <table class="table table-bordered table-striped table-middle">
                <thead class="blue-grey lighten-4">
                <tr>
                    　
                    <th>id</th>
                    <th>name</th>
                    <th>type</th>
                    <th>Delete</th>
                    <th>Start_time</th>
                    <th>End_time</th>
                    <th>value</th>
                    <th>Discount Code</th>
                    <th>Action</th>
                </tr>
                </thead>

                @foreach($data as $d)
                    <tr>
                        <td>
                            {{$d->id}}
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            {{$d->getEncodedCode()}}
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            </table>

            {{ $data->appends(request()->except('page'))->render('pagination::mdb') }}
            <button onclick="CreateDiscount()" class="btn btn-amber">新增折扣</button></td>

            <div id="lo" style="display:none">
                <form action="{{action('DiscountController@createShipping')}}" method="post">

                    折扣類型:
                    <div class="form-group ">
                        <label for="dis_A">
                            總價打折
                        </label>
                        <input id="dis_A" type="radio" name="type" value="A"/>
                    </div>
                    <div class="form-group ">
                        <label for="dis_B">
                            總價折扣XX元
                        </label>
                        <input id="dis_B" type="radio" name="type" value="B"/>
                    </div>
                    <div class="form-group ">
                        <label for="dis_C">
                            特定分類打折
                        </label>
                        <input id="dis_C" type="radio" name="type" value="C"/>
                    </div>

                    折扣名稱: <input type="text" name="name" required><br>

                    折扣數值: <input type="text" name="value" required><br>

                    折扣的分類: <select id="category_id" name="category_id"></select>

                    不知道怎弄
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

        function CreateDiscount() {
            $("#lo").toggle();
        }
    </script>
@endsection