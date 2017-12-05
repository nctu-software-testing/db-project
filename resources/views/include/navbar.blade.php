@if(!session('user'))
    @include('include.navbar-non-login')
@else
    @include('include.navbar-login')
@endif
<nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar" id="headNav">
    <div class="container">
        <a class="navbar-brand" href="{{asset('/')}}">
            <img src="{{asset('images/logo.svg')}}" onerror="this.src='{{asset('images/logo.png')}}'" id="logo-img"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="navbar-nav col-9 flex-row">
                <div class="nav-item">
                    <a class="nav-link waves-effect waves-light" accesskey="f"
                       href="{{action('ProductController@getProducts')}}">商品瀏覽</a>
                </div>

                <form class="form-inline" style="flex: 1"
                      action="{{action('ProductController@getProducts')}}"
                >
                    <input class="form-control mr-sm-4" type="search" placeholder="Search" aria-label="Search" style="width: 100%"
                        name="search[name]" value="{{request('search')['name']??''}}"
                    >
                </form>
            </div>
            <div class="navbar-nav ml-auto">
                @yield('right-nav')
            </div>

        </div>
    </div>

</nav>
<div class="navPadding"></div>