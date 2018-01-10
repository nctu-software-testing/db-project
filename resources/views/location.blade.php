@extends('management.base')
@section('content')
    <div class="card card-body">
        <h4 class="card-title">地址管理</h4>
        <div class="card-text">
            <!--Table-->
            <table class="table table-bordered">

                <!--Table head-->
                <thead class="blue-grey lighten-4">
                <tr>
                    <th width="120">郵遞區號</th>
                    <th>地址</th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                        <td>{{$data[$i]->zip_code}}</td>
                        <td>{{$data[$i]->address}}</td>
                    </tr>
                @endfor
                </tbody>

                <!--Table body-->
            </table>
            <!--Table-->

            {{ $data->links() }}<br>
            <button id="newlocation" onclick="CreateLocation()" type="button" class="btn btn-rounded btn-blue-grey">
                新增地址
            </button>
            <a href="{{action('UserController@getUserInfo')}}" type="button" class="btn btn-rounded btn-red"><i
                        class="fa fa-rotate-right pr-2" aria-hidden="true"></i>返回</a>


            <div id="lo" style="display: none">
                <form action="{{action('LocationController@createLocation')}}" method="post">

                    <div id="twzipcode"></div>
                    街道名稱及門牌號碼與樓層:<input type="text" name="address" required><br>
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <button type="submit" class="btn btn-rounded btn-blue-grey"><i class="fa fa-floppy-o pr-2" aria-hidden="true"></i>
                        儲存地址
                    </button>
                    <button onclick="CancelLocation()" type="button" class="btn btn-rounded btn-red"><i
                                class="fa fa-rotate-right pr-2" aria-hidden="true"></i>取消
                    </button>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('eofScript')
    <script type="text/javascript" nonce="{{$nonce}}">

        function CreateLocation() {
            document.getElementById('lo').style.display = '';
            document.getElementById('newlocation').style.display = 'none';
        }

        function CancelLocation() {
            document.getElementById('lo').style.display = 'none';
            document.getElementById('newlocation').style.display = '';
        }

        $('#twzipcode').twzipcode();
        $('[name=zipcode]').attr("readonly", "readonly");
        $('[name=county]').prop("required", "required");
        $('[name=district]').prop("required", "required");
    </script>
@endsection