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
        @if (!session('user'))
    <form action="login" method="post">
        登入<br>
        帳號:<input type="text" name="account"><br>
        密碼:<input type="password" name="password"><br>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="登入">
        @endif
    </form>
        @if (session('user'))
                Hello, {{ session('user')->name}}
                @if (session('user')->enable==1)
                    你已通過驗證，可以正常使用該網站的功能
                    @else
                    你未通過驗證，快去驗證
                    @endif

        <form action="logout" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="登出">
        </form>
            @endif
    </div>

    </body>
</html>


