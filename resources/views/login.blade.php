<div id="temppp">
@if (session('user.enable', 0)!==1)
<script>
    $(()=>toastr.warning('你未通過驗證，快去驗證', '', {
        positionClass: 'toast-bottom-right',
        timeOut: 1e20,
        onclick: ()=>location.href = '{{action('VerificationController@getVerification')}}'
    }));
</script>
@endif

<div>
    <br/>
    <br/>
    <br/>
    <a href="register">
        <Button>(A)註冊</Button>
    </a>
    <a href="verification">
        <Button>(B)會員驗證</Button>
    </a>
    <a href="userinfo">
        <Button>(C)會員資料</Button>
    </a>
    <a href="location">
        <Button>(D)地址管理</Button>
    </a>
    <a href="category">
        <Button>(E)類別管理</Button>
    </a>
    <a href="product">
        <Button>(F)商品瀏覽</Button>
    </a>
    <a href="discount">
        <Button>(G)折價管理</Button>
    </a>
    <a href="order">
        <Button>(H)訂單管理</Button>
    </a><br>
</div>
</div>