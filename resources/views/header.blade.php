@include("login")
@include("log")
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

    <a href="register"><Button>(A)註冊</Button><a><br>
    <a href="verification"><Button>(B)會員驗證</Button><a><br>
    <a href="userinfo"><Button>(C)會員資料</Button><a><br>


