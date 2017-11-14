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
    <form action="register" method="post">
        註冊<br>
        帳號:<input type="text" name="account" required><br>
        密碼:<input type="password" name="password" required><br>
        類型:
        <input type=radio value="C" name="role" checked="checked" required> 顧客
        <input type=radio value="B" name="role" required> 商家
        <br>
        姓名/商家名稱:<input type="text" name="name" required><br>
        身分證字號/商家統一編號:<input type="text" name="sn" required><br>
        性別:
        <input type=radio value="男" name="gender" checked="checked" required> 男
        <input type=radio value="女" name="gender" required> 女
        <br>
        信箱:<input type="text" name="email" required><br>
        生日:<input type="date" id="bookdate" value="2014-09-18" name="birthday" required><br>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="送出">

    </form>
    </body>
</html>


