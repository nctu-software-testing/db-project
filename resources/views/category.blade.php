@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <table border="1">
        <tr>
            　<td>Category</td>
              <td>Delete</td>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->product_type}}</td>
                　<td>刪除</td>
            </tr>
        @endfor
    </table>
    {{ $data->links() }}<br>
     <button onclick="CreateCategory()">新增類別</button></td>

    <div hidden id="lo">
        <form action="category" method="post">
            Category:<input type="text" name="product_type" required><br>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="送出">
        </form>
    </div>
@endsection
@section('eofScript')
    <script>
        function CreateCategory() {
            $("#lo").toggle();
        }
    </script>
@endsection
@include('base')