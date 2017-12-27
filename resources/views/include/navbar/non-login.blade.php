<!-- Navbar -->
@if(!($hide_login??false))
<form id="login" class="ajax-auth card p-0" action="{{action('UserController@postLogin')}}" method="POST">
    <div class="card-body mx-4">
        <div class="text-center">
            <h3 class="dark-grey-text mb-5"><strong>登入</strong></h3>
        </div>

        <p class="status"></p>

        <div class="row">
            <div class="col">
                <div class="md-form">
                    <input type="text" id="nav_account" class="form-control" name="account">
                    <label for="nav_account">帳號</label>
                </div>
            </div>
            <div class="col">
                <div class="md-form mb-1">
                    <input type="password" id="nav_password" class="form-control" name="password">
                    <label for="nav_password">密碼</label>
                </div>
            </div>
        </div>

        @if(config('app.captcha'))
        <div class="md-form">
            <iframe src="{{action('CaptchaController@getIndex')}}" frameborder="0" name="captcha" class="captcha"></iframe>
        </div>
        @endif

        <div class="text-center mt-3 mb-3">
            <button class="submit_button btn blue-gradient btn-block btn-rounded"
                    type="submit" value="{{csrf_token()}}" name="_token">登入
            </button>
        </div>
        <hr>
        <div class="text-right">
            <p class="login-font-small grey-text">還沒有帳號?
                <a href="{{action('UserController@getReg')}}" class="blue-text ml-1" id="pop_signup" accesskey="a">註冊</a></p>
        </div>
    </div>
</form>
<script>
    $(function () {
        $("#login").submit(function (e) {
            let statusBar = this.querySelector('.status');
            statusBar.textContent = '登入中...';
            ajax(this.method, this.action, this)
                .then((result) => {
                    statusBar.textContent = result.result;
                    if (result.success) {
                        location.reload(!0);
                    } else {
                        @if(config('app.captcha'))
                        let c = this.querySelector('.captcha');
                        if(c && c.contentWindow && c.contentWindow.CReset) c.contentWindow.CReset();
                        @endif
                    }
                });
            e.preventDefault();
        });
    });
</script>
@else
<style>#navbar-static-login{display:none}</style>
@endif
@section('right-nav')
    <li class="nav-item">
        <a id="navbar-static-login" class="nav-link waves-effect waves-light" data-form="#login">
            <i class="fa fa-sign-in mr-1"></i>
            <span class="clearfix d-none d-sm-inline-block">登入</span>
        </a>
    </li>
@endsection