<form action="{{action('UserController@logout')}}" method="POST" class="form-inline" id="logoutForm" hidden></form>
@section('right-nav')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle waves-effect" href="#" id="navbar-account" data-toggle="dropdown"
           aria-haspopup="true">
            <i class="fa fa-user"></i> <span class="clearfix d-none d-sm-inline-block">
                Hi, {{session('user.name')}}
                </span>
        </a>
        <div class="dropdown-menu dropdown-ins dropdown-menu-right" aria-labelledby="userDropdown"
             data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" style="position: absolute;">

            <a class="dropdown-item waves-effect waves-light" accesskey="c"
               href="{{action('UserController@getUserInfo')}}">會員資料</a>

            <a class="dropdown-item waves-effect waves-light" accesskey="d"
               href="{{action('LocationController@getLocation')}}">地址管理</a>


            <div class="divider"></div>
            <button class="dropdown-item  waves-effect" type="submit" value="{{csrf_token()}}" name="_token"
                    form="logoutForm">登出
            </button>
        </div>
    </li>
@endsection