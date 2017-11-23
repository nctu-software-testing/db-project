<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include("include.head")
</head>
<body class="mdb-skin-custom currency-eur">
@include('include.navbar')
@include("log")
@include("login")
<main>
    <div class="container">
        @yield('content')
    </div>
</main>
@include('include.egg')
@yield('eofScript')
</body>
</html>
