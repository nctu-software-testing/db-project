@section('content')
    <div>
    <table border="1">
        <tr>
            　<td>account</td>
            　<td>sn</td>
              <td>name</td>
              <td>身份</td>
              <td>birthday</td>
            　<td>gender</td>
            　<td>email</td>
            　<td>驗證</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->account}}</td>
                　<td>{{$data[$i]->sn}}</td>
                　<td>{{$data[$i]->name}}</td>
                　<td>{{$data[$i]->role}}</td>
                　<td>{{$data[$i]->birthday}}</td>
                  <td>{{$data[$i]->gender}}</td>
                  <td>{{$data[$i]->email}}</td>
                  <td>{{$data[$i]->enable}}</td>
            </tr>
        @endfor
    </table>
        　<td><button onclick="ChagePassword()">修改密碼</button></td>
        　<td><button onclick="ChageEmail()">修改信箱</button></td>
    </div>
    <div hidden id="ps">
        <form action="changepassword" method="post">
        舊密碼:<input type="password" name="oldpassword" required><br>
        新密碼:<input type="password" name="newpassword" required><br>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="送出">
        </form>
    </div>
    <div hidden id="em">
        <form action="changeemail" method="post">
            信箱:<input type="text" name="email" required><br>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="送出">
        </form>
    </div>
@endsection
@section('eofScript')
    <Script>
        function ChagePassword() {
            $("#ps").toggle();
        }
        function ChageEmail() {
            $("#em").toggle();
        }
    </Script>
@endsection
@include('base')

