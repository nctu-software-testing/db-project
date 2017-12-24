@extends('management.base')
@section('content')

    <div class="card card-body">
        <h4 class="card-title">管理運費</h4>
        <div class="card-text">
            <!--Table-->
            <table class="table table-bordered table-striped table-middle">

            <!--Table head-->
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>總價下限</th>
                    <th>總價上限</th>
                    <th>運費</th>
                    <th>操作</th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                        <td>{{$data[$i]->lower_bound}}</td>
                        <td>{{$data[$i]->upper_bound}}</td>
                        <td>{{$data[$i]->price}}</td>
                        <td>
                            <button onclick="DeleteShipping({{$data[$i]->id}})" type="button" class="btn btn-rounded btn-red">
                                刪除運費
                            </button>
                        </td>
                    </tr>
                @endfor
                </tbody>

                <!--Table body-->
            </table>
            <!--Table-->
            <button id="newshipping" onclick="CreateShipping()" type="button" class="btn btn-rounded btn-blue-grey">
                新增運費
            </button>


            <div id="sh" style="display: none">
                <form action="{{action('DiscountController@createShipping')}}" method="post">

                    總價下限:<input type="text" name="lower_bound" required><br>
                    總價上限:<input type="text" name="upper_bound" required><br>
                    運費:<input type="text" name="price" required><br>
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <button type="submit" class="btn btn-rounded btn-blue-grey"><i class="fa fa-floppy-o pr-2" aria-hidden="true"></i>
                        儲存運費
                    </button>
                    <button onclick="CancelShipping()" type="button" class="btn btn-rounded btn-red"><i
                                class="fa fa-rotate-right pr-2" aria-hidden="true"></i>取消
                    </button>

                </form>
            </div>

        </div>
    </div>
@endsection
@section('eofScript')
    <script>
        function DeleteShipping(id) {
            if (confirm('你要刪除嗎?')) {
                ajax('DELETE', '{{action('DiscountController@deleteShipping')}}', {id: id})
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

        function CreateShipping() {
            document.getElementById('sh').style.display = '';
            document.getElementById('newshipping').style.display = 'none';
        }

        function CancelShipping() {
            document.getElementById('sh').style.display = 'none';
            document.getElementById('newshipping').style.display = '';
        }
    </script>
@endsection