@if(!session('user'))
    @include('include.navbar-non-login')
@else
    @include('include.navbar-login')
@endif
<nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar">
    <a class="navbar-brand" href="{{asset('/')}}">
        <img src="{{asset('images/favicon.png')}}" id="logo-img"/>
        Any Buy
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <!--
            <li class="nav-item active">
                <a class="nav-link waves-effect waves-light" href="{{asset('/')}}">Home <span class="sr-only">(current)</span></a>
            </li>
            -->
            <li class="nav-item">
                <a class="nav-link waves-effect waves-light" href="{{action('ProductController@getProduct')}}">商品瀏覽</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light" id="navAdminBtn" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    管理
                </a>
                <div class="dropdown-menu dropdown-primary" aria-labelledby="navAdminBtn">
                    <a class="dropdown-item waves-effect waves-light" href="{{action('VerificationController@getVerification')}}">會員驗證</a>
                    <a class="dropdown-item waves-effect waves-light" href="{{action('CategoryController@getCategory')}}">類別管理</a>
                </div>
            </li>
            <form class="form-inline">
                <input class="form-control mr-sm-4" type="text" placeholder="Search" aria-label="Search">
            </form>
        </ul>
        <div class="navbar-nav ml-auto">
            @yield('right-nav')
        </div>

    </div>
</nav>