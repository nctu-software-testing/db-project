<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include("header")
    </head>
    <body>
    @if (session('user'))
    @if (session('user') and (session('user')->role=='A'))
    @include("verificationAdmin")
    @endif
    @if (session('user') and (session('user')->role!='A'))
    @include("verificationUser")
    @endif
    @endif
    </body>
</html>
