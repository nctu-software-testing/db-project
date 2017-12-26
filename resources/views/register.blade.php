@extends('base')
@section('content')
    <h2 class="page-title">註冊帳號</h2>
    <div id="selectRole" class="row base-panel">
        <div class="col-2"></div>
        <div class="col-4">
            <!--Card-->
            <div class="card">
                <!--Card image-->
                <img class="img-fluid" src="{{asset('images/businessman.jpg')}}">

                <!--Card content-->
                <div class="card-body">
                    <!--Title-->
                    <h4 class="card-title">商家</h4>
                    <!--Text-->
                    <p class="card-text">
                        還在煩惱顧客來源嗎？<br/>
                        快來加入我們，尋找你潛在的顧客。
                    </p>
                    <button data-role="B" class="role-btn btn btn-primary btn-block">成為商家</button>
                </div>
            </div>
            <!--/.Card-->
        </div>
        <div class="col-4">
            <!--Card-->
            <div class="card">
                <!--Card image-->
                <img class="img-fluid" src="{{asset('images/customer.jpg')}}">

                <!--Card content-->
                <div class="card-body">
                    <!--Title-->
                    <h4 class="card-title">客戶</h4>
                    <!--Text-->
                    <p class="card-text">
                        想宅在家裡不想出門又可以買東西嗎？<br/>
                        快來加入我們，尋找優良的商家。
                    </p>
                    <button data-role="C" class="role-btn btn btn-primary btn-block">成為顧客</button>
                </div>
            </div>
            <!--/.Card-->
        </div>
        <div class="col-2"></div>
    </div>
    <div class="row base-panel" id="fillInPanel">
        <div class="col-2"></div>
        <div class="col-8">
            <form method="POST" action="{{action('UserController@postReg')}}" id="regForm">

                <section class="form-light">

                    <!--Form without header-->
                    <div class="card">

                        <div class="card-body mx-4">

                            <!--Header-->
                            <div class="text-center">
                                <h3 class="pink-text mb-5"><strong>註冊</strong></h3>
                            </div>
                            <div class="card-content">

                                <!--First row-->
                                <div class="row">
                                    <!--First column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">
                                            <input type="text" id="account" name="account"
                                                   class="form-control validate"
                                                   placeholder="請輸入你的帳號" required>
                                            <label for="account">帳號</label>
                                        </div>
                                    </div>

                                    <!--Second column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">
                                            <input type="password" id="password" name="password"
                                                   class="form-control validate"
                                                   placeholder="請輸入密碼" required>
                                            <label for="password">密碼</label>
                                        </div>
                                    </div>
                                </div>
                                <!--/.First row-->

                                <!--Second row-->
                                <div class="row">
                                    <!--First column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">
                                            <input type="text" id="name" name="name"
                                                   class="form-control validate"
                                                   placeholder="請輸入姓名"
                                                   required>
                                            <label for="name">姓名</label>
                                        </div>
                                    </div>

                                    <!--Second column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">
                                            <input type="text" id="sn" name="sn"
                                                   placeholder="請輸入證件號碼"
                                                   class="form-control validate" required>
                                            <label for="sn">
                                                <span class="for-b">商家統一編號</span>
                                                <span class="for-c">身分證字號</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!--/.Second row-->

                                <!--Third row-->
                                <div class="row">
                                    <!--First column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">
                                            <input type="email" id="email" name="email"
                                                   class="form-control validate"
                                                   placeholder="aqua@water.god"
                                                   required>
                                            <label for="email">信箱</label>
                                        </div>
                                    </div>

                                    <!--Second column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">
                                            <input type="date" id="birthday" name="birthday"
                                                   class="form-control validate"
                                                   value="{{date('Y-m-d')}}"
                                                   placeholder="生日" required>
                                            <label for="birthday" class="active">
                                                生日
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!--/.Third row-->

                                <!--Forth row-->
                                <div class="row">
                                    <!--First column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">
                                            <label class="active">性別</label>

                                            <input name="gender" type="radio" id="gender_boy" value="男" checked>
                                            <label for="gender_boy">男</label>
                                            &emsp;
                                            <input name="gender" type="radio" id="gender_girl" value="女">
                                            <label for="gender_girl">女</label>
                                        </div>
                                    </div>

                                    <!--Second column-->
                                    <div class="col-md-6">
                                        <div class="md-form form-group">

                                        </div>
                                    </div>
                                </div>
                                <!--/.Forth row-->

                                @if($captcha)
                                <!--Fifth row-->
                                <div class="row">

                                    <div class="col">

                                        <div class="md-form  form-group">
                                            <iframe src="{{action('CaptchaController@getIndex')}}" frameborder="0" name="captcha" class="captcha"></iframe>
                                        </div>
                                    </div>
                                </div>
                                <!--/.Fifth row-->
                                @endif

                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center mb-3">
                                            <button type="submit"
                                                    class="btn blue-gradient btn-block btn-rounded z-depth-1a waves-effect waves-light"
                                                    name="_token"
                                                    value="{{csrf_token()}}"
                                                    id="regBtn"
                                            >
                                                註冊
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/Form without header-->

                </section>
                <input type="hidden" id="role" name="role" value=""/>
            </form>

        </div>
        <div class="col-2"></div>
    </div>
@endsection
@section('eofScript')
    <script>
        let setRole = (role) => {
            $('#role').val(role);
            $('#fillInPanel').attr('data-role', role);
        };
        let hideAllPanel = () => $('.base-panel').hide();
        let showSelectRole = () => {
            hideAllPanel();
            $('#selectRole').show();
        };
        let showFillInForm = () => {
            hideAllPanel();
            $('#fillInPanel').show();
        };

        $(".role-btn").on('click', function(){
           let role = this.getAttribute('data-role');
           setRole(role);
           showFillInForm();
        });

        $('#regForm').submit(function(e){
            let regBtn = $("#regBtn")[0];
            regBtn.disabled = true;
            ajax("POST", this.action, this)
                .then((d) => {
                    if (d.success) {
                        toastr.success(d.result);
                        setTimeout(() => location.replace('{{asset('')}}'), 2000);
                    } else {
                        regBtn.disabled = false;
                        toastr.error(d.result, '', {
                            positionClass: 'toast-top-center',
                        });

                        @if($captcha)
                        let c = this.querySelector('.captcha');
                        if(c && c.contentWindow && c.contentWindow.CReset) c.contentWindow.CReset();
                        @endif
                    }
                }).catch(() => regBtn.disabled = false);

            e.preventDefault();
        });
        showSelectRole();
    </script>
@endsection