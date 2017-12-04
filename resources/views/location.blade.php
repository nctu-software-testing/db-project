@extends('base')
@section('extraScript')
    <script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
     <!--Table-->
            <table class="table">

                <!--Table head-->
                <thead class="blue-grey lighten-4">
                    <tr>
                        <th>地址</th>
                        <th>郵遞區號</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                    @for ($i = 0; $i < count($data); $i++)
                        <tr>
                           <td>{{$data[$i]->address}}</td>
                         　<td>{{$data[$i]->zip_code}}</td>
                         　<td><a href="#delete" style="color:#0275d8;"> 刪除</a></td>
                        </tr>
                    @endfor
                </tbody>

                <!--Table body-->
            </table>
     <!--Table-->
    
    {{ $data->links() }}<br>
     <button id="newlocation" onclick="CreateLocation()" type="button" class="btn btn-rounded btn-blue-grey">新增地址</button>

    <div id="lo" style="display: none">
        <form action="location" method="post">
        街道名稱及門牌號碼與樓層:<input type="text" name="address" required><br>

            <div id="twzipcode"></div>

            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <button type="submit" class="btn btn-rounded btn-blue-grey"><i class="fa fa-floppy-o pr-2" aria-hidden="true"></i>儲存地址</button>
            <button onclick="CancelLocation()" type="button" class="btn btn-rounded btn-red"><i class="fa fa-rotate-right pr-2" aria-hidden="true"></i>取消</button>

        </form>
    </div>
@endsection
@section('eofScript')
    <script>
        function CreateLocation() {
            document.getElementById('lo').style.display='';
            document.getElementById('newlocation').style.display='none';
        }
        function CancelLocation() {
            document.getElementById('lo').style.display='none';
            document.getElementById('newlocation').style.display='';
        }
        $( document ).ready(function() {
            $('#twzipcode').twzipcode();
            $('[name=zipcode]').attr("readonly","readonly");
            $('[name=county]').prop("required","required");
            $('[name=district]').prop("required","required");
        });
    </script>
@endsection