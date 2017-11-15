<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include("header")
    <title>Laravel</title>
</head>
<body>
@include("log")
@include("login")

@yield('content')
@yield('eofScript')
</body>
</html>
