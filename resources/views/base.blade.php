<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include("include.head")
</head>
<body>
@include("log")
@include("login")
@yield('content')

<script type="text/javascript" src="{{asset('dist/js/app.js')}}"></script>
@yield('eofScript')
</body>
</html>
