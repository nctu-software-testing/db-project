
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
