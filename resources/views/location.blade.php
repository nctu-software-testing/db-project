@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <table border="1">
        <tr>
            　<td>address</td>
            　<td>zip_code</td>
              <td>Delete</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->address}}</td>
                　<td>{{$data[$i]->zip_code}}</td>
            </tr>
        @endfor

    </table>
        　<td><button onclick="CreateLocation()">新增地址</button></td>
    </div>
    <div hidden id="lo">
        <form action="location" method="post">
        Address:<input type="text" name="address" required><br>
            <div id="twzipcode"></div>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="送出">
        </form>
    </div>
@endsection
@section('eofScript')
    <script>
        function CreateLocation() {
            $("#lo").toggle();
        }
        $( document ).ready(function() {
            $('#twzipcode').twzipcode();
            $('[name=zipcode]').attr("readonly","readonly");
            $('[name=county]').prop("required","required");
            $('[name=district]').prop("required","required");
        });
    </script>
@endsection
@include('base')