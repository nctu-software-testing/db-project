<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include("include.head")
</head>
<body class="mdb-skin-custom currency-eur page-{{$pageName ?? 'base'}}">
@include('include.navbar')
@include("log")
@yield('header')
<main>
    @hasSection('content-full')
        @yield('content-full')
    @else
        <div class="container">
            @yield('content')
        </div>
    @endif

</main>
@include('include.footer')
@include('include.egg')
@include("verification.notice")
@yield('eofScript')
</body>
</html>
