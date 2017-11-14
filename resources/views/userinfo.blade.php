<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include("header")
        <title>Laravel</title>
    </head>
    <body>
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
            <tr>
                　<td>{{$data[0]->account}}</td>
                　<td>{{$data[0]->sn}}</td>
                　<td>{{$data[0]->name}}</td>
                　<td>{{$data[0]->role}}</td>
                　<td>{{$data[0]->birthday}}</td>
                  <td>{{$data[0]->gender}}</td>
                  <td>{{$data[0]->email}}</td>
                  <td>{{$data[0]->enable}}</td>
            </tr>
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
    </body>
    <script>
        function ChagePassword() {
            $("#ps").toggle();
        }
        function ChageEmail() {
            $("#em").toggle();
        }
    </script>

</html>
