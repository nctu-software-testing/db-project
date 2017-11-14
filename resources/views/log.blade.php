<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

</html>
<script>
    window.onload=function() {
        @if (session('log'))
            alert("{{session('log')}}");
        @endif
    }
</script>

