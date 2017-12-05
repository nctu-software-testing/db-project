@extends('base')
@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <table border="1">
        <tr>
            　<td>id</td>
              <td>name</td>
            　<td>type</td>
              <td>Delete</td>
              <td>Start_time</td>
              <td>End_time</td>
              <td>Discount_percent</td>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
            <tr>

            </tr>
        @endfor
    </table>
    {{ $data->links() }}<br>
     <button onclick="CreateDiscount()">新增折扣</button></td>

    <div hidden id="lo">
        @include("discountForm");
    </div>
@endsection
@section('eofScript')
    <script>
        function CreateDiscount() {
            $("#lo").toggle();
        }
    </script>
@endsection