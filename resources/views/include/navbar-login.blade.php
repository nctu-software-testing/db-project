<form action="{{action('UserController@logout')}}" method="POST" class="form-inline" id="logoutForm" hidden></form>
@section('right-nav')
    <div class="flex-center">
        <div class="nav-item" >
            <a href="{{action('ProductController@getShoppingCar')}}" class="nav-link waves-light">
                <i class="material-icons">shopping_cart</i>
                <span class="name">購物車</span>
                <span class="num">87</span>
            </a>
        </div>
        &emsp;
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle waves-effect" href="#" id="navbar-account" data-toggle="dropdown"
               aria-haspopup="true">
                <i class="material-icons">account_circle</i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown"
                 data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" style="position: absolute;">
                <h6 class="dropdown-header">

                <span class="clearfix d-none d-sm-inline-block">
                Hi, {{session('user.name')}}
                </span>
                </h6>

                <a class="dropdown-item waves-effect waves-light" accesskey="c"
                   href="{{action('UserController@getUserInfo')}}">(ABC)會員資料</a>

                <div class="divider"></div>


                <h6 class="dropdown-header">
                    管理
                </h6>
                <a class="dropdown-item waves-effect waves-light" accesskey="d"
                   href="{{action('LocationController@getLocation')}}">(ABC)地址管理</a>
                <a class="dropdown-item waves-effect waves-light" accesskey="b"
                   href="{{action('VerificationController@getVerification')}}">(ABC)會員驗證</a>

                <a class="dropdown-item waves-effect waves-light" accesskey="e"
                   href="{{action('CategoryController@getCategory')}}">(A)類別管理</a>

                <a class="dropdown-item waves-effect waves-light" accesskey="f"
                   href="{{action('ProductController@getSelfProducts')}}">(AB)商品管理</a>

                <a class="dropdown-item waves-effect waves-light" accesskey="g"
                   href="{{action('DiscountController@getDiscount')}}">(A)折價管理</a>

                <a class="dropdown-item waves-effect waves-light" accesskey="h"
                   href="{{action('OrderController@getOrder')}}">(BC)訂單管理</a>


                <div class="divider"></div>
                <button class="dropdown-item  waves-effect" type="submit" value="{{csrf_token()}}" name="_token"
                        form="logoutForm">登出
                </button>
            </div>
        </div>
    </div>
@endsection