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
<div class="hidden">
    <a href="register">
        <Button>(A)註冊</Button>
    </a><br>
    <a href="verification">
        <Button>(B)會員驗證</Button>
    </a><br>
    <a href="userinfo">
        <Button>(C)會員資料</Button>
    </a><br>
    <a href="location">
        <Button>(D)地址管理</Button>
    </a><br>
    <a href="category">
        <Button>(E)類別管理</Button>
    </a><br>
    <a href="product">
        <Button>(F)商品瀏覽</Button>
    </a><br>
    <a href="discount">
        <Button>(G)折價管理</Button>
    </a><br>
    <a href="order">
        <Button>(H)訂單管理</Button>
    </a><br>
</div>
