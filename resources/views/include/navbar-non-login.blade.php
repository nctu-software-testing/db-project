<!-- Navbar -->
<form id="login" class="ajax-auth card p-0" action="{{action('UserController@postLogin')}}" method="POST">
    <div class="card-body mx-4">
        <div class="text-center">
            <h3 class="dark-grey-text mb-5"><strong>登入</strong></h3>
        </div>

        <p class="status"></p>
        <div class="md-form">
            <input type="text" id="account" class="form-control" name="account">
            <label for="account">帳號</label>
        </div>

        <div class="md-form mb-1">
            <input type="password" id="password" class="form-control" name="password">
            <label for="password">密碼</label>
        </div>

        <div class="text-center mt-3 mb-3">
            <button class="submit_button btn blue-gradient btn-block btn-rounded"
                    type="submit" value="{{csrf_token()}}" name="_token">登入
            </button>
        </div>
        <hr>
        <div class="text-right">
            <p class="login-font-small grey-text">還沒有帳號?
                <a href="#" class="blue-text ml-1" id="pop_signup" data-form="#register" accesskey="a">註冊</a></p>
        </div>
    </div>
</form>


<form id="register" class="ajax-auth card p-0" action="{{action('UserController@postReg')}}" method="POST">
    <div class="card-body mx-4">
        <div class="text-center">
            <h3 class="dark-grey-text mb-3"><strong>註冊</strong></h3>
        </div>

        <p class="status"></p>
        <input type="hidden" id="signonsecurity" name="signonsecurity" value="0f1dd8bd1e"/><input type="hidden"
                                                                                                  name="_wp_http_referer"
                                                                                                  value="/components/bootstrap-navs/"/>
        <div class="md-form">
            <input type="text" id="signonname" class="form-control" name="signonname">
            <label for="signonname">Your name</label>
        </div>

        <div class="md-form">
            <input type="text" id="signonusername" class="form-control" name="signonusername">
            <label for="signonusername">Your username</label>
        </div>

        <div class="md-form">
            <input type="text" id="email" class="form-control" name="email">
            <label for="email">Your email</label>
        </div>

        <div class="md-form">
            <input type="password" id="signonpassword" class="form-control" name="signonpassword">
            <label for="signonpassword">Your password</label>
        </div>

        <div class="md-form">
            <input type="password" id="password2" class="form-control" name="password2">
            <label for="password2">Repeat password</label>
        </div>

        <div class="text-center mt-3 mb-3">
            <button class="submit_button btn blue-gradient btn-block btn-rounded" type="submit" value="SIGNUP">Sign Up
            </button>
        </div>

        <hr>
        <div class="text-right">
            <p class="login-font-small grey-text">Already have an account? <a href="#" id="pop_login" class="blue-text">Log
                    in</a></p>
        </div>
        <!--

            <div class="g-recaptcha" data-sitekey="6LflIQ4TAAAAAEx83MeF_efgHI7acclgM_UYe1Ov"></div>
        -->
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
                    }
                });
            e.preventDefault();
        });
    });
</script>

@section('right-nav')
    <li class="nav-item">
        <a id="navbar-static-login" class="nav-link waves-effect waves-light" data-form="#login">
            <i class="fa fa-sign-in mr-1"></i>
            <span class="clearfix d-none d-sm-inline-block">Log In</span>
        </a>
    </li>
@endsection