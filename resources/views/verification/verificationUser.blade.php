@section('content')
    <table border="1">
        <tr>
            　<td>id</td>
            　<td>user_id</td>
              <td>name</td>
              <td>account</td>
              <td>role</td>
            　<td>front_picture</td>
            　<td>back_picture</td>
            　<td>verify_result</td>
            　<td>sign_up_time</td>
            　<td>verification_time</td>
              <td>description</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->id}}</td>
                　<td>{{$data[$i]->user_id}}</td>
                　<td>{{$data[$i]->name}}</td>
                　<td>{{$data[$i]->account}}</td>
                　<td>{{$data[$i]->role}}</td>
                　<td><button onclick="OpenImage('{{$data[$i]->id}}','front')">圖片</button></td>
                　<td><button onclick="OpenImage('{{$data[$i]->id}}','back')">圖片</button></td>
                　<td>{{$data[$i]->verify_result}}</td>
                　<td>{{$data[$i]->sign_up_datatime}}</td>
                　<td>{{$data[$i]->datetime}}</td>
                  <td>{{$data[$i]->description}}</td>
            </tr>
        @endfor
    </table>

    <br><br>
    @if (count($data)==0 or $data[count($data)-1]->verify_result=="驗證失敗")
        @include("verification.verificationForm")
    @endif
@endsection
@section('eofScript')
    <script>
        function OpenImage(id,t) {
            var path='verify-image/'+id+'/'+t;
            window.open(path, 'Image', config='height=500,width=500');
        }
    </script>
@endsection

