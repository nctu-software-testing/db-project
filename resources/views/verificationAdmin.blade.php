<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
    </head>
    <body>

    <div>
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
              <td>驗證</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->id}}</td>
                　<td>{{$data[$i]->user_id}}</td>
                　<td>{{$data[$i]->name}}</td>
                　<td>{{$data[$i]->account}}</td>
                　<td>{{$data[$i]->role}}</td>
                　<td><button onclick="OpenImage('{{$data[$i]->front_picture}}')">圖片</button></td>
                　<td><button onclick="OpenImage('{{$data[$i]->front_picture}}')">圖片</button></td>
                　<td>{{$data[$i]->verify_result}}</td>
                　<td>{{$data[$i]->sign_up_datatime}}</td>
                　<td>{{$data[$i]->datetime}}</td>
                  <td>{{$data[$i]->description}}</td>
                  <td>
                  @if($data[$i]->verify_result=="未驗證")
                    <button onclick="Verification('{{$data[$i]->id}}')">驗證</button>
                  @endif
                  </td>
            </tr>
        @endfor
    </table>
    </div>
    </body>
    <script>
        function OpenImage(path) {
            var truepath='{{ env('IMAGE_URL') }}'+path;
            window.open(truepath, 'Image', config='height=500,width=500');
        }
        function Verification(id) {
            var result = "未驗證";
            var reason = "";
            var ok=confirm("驗證通過?");
            if(ok)
            {
                result = "驗證成功";
            }
            else
            {
                reason=prompt("理由?");
                result = "驗證失敗";
            }
            $.post("verification",
                {
                    id:id,
                    result: result,
                    reason: reason
                },
                function(data){
                    location.reload();
                });
        }
    </script>

</html>
