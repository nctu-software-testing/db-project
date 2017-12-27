<?php
$path = '';
switch (session('user.role')) {
    case 'A':
        $path = 'admin';
        break;
    case 'B':
        $path = 'business';
        break;
    case 'C':
        $path = 'customer';
        break;
}
?><form action="{{action('UserController@logout')}}" method="POST" class="form-inline" id="logoutForm" hidden></form>
@section('right-nav')
    <div class="flex-center">
        <div class="nav-item">
            <a href="{{action('ShoppingCartController@getShoppingCart')}}" class="nav-link waves-light">
                <i class="material-icons">shopping_cart</i>
                <span class="name">購物車</span>
                <span class="num" id="cartNum"></span>
            </a>
            <script>
                function updateShoppingCartCount() {
                    ajax('GET', '{{action('ShoppingCartController@getShoppingCart')}}', {type: 'query'})
                        .then(d => $("#cartNum").text(d.result ? d.result : ''));
                }
                updateShoppingCartCount();
            </script>

        </div>
        &emsp;
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle waves-effect" href="#" id="navbar-account" data-toggle="dropdown"
               aria-haspopup="true">
                <img src="{{session('user')->getAvatarUrl()}}" class="avatar"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown"
                 data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" style="position: absolute;">
                <h6 class="dropdown-header">

                <span class="clearfix d-none d-sm-inline-block">
                Hi, {{session('user.name')}}
                </span>
                </h6>

                <a class="dropdown-item waves-effect waves-light"
                   href="{{action('UserController@getUserInfo')}}">會員資料</a>

                <div class="divider"></div>

                @if(!empty($path))
                    @include('include.navbar.items.'.$path)
                @endif

                <div class="divider"></div>
                <button class="dropdown-item  waves-effect" type="submit" value="{{csrf_token()}}" name="_token"
                        form="logoutForm">登出
                </button>
            </div>
        </div>
    </div>
@endsection