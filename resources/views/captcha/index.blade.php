<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="public-path" content="{{ asset('/') }}"/>
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/captcha.css')}}">

    <!-- Include Script -->
    <script type="text/javascript" src="{{asset('js/mdbootstrap.4.4.0.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dist/js/app.js')}}"></script>

</head>
<body>

<div id="captcha"
     data-base="{{action('CaptchaController@getFullImage')}}"
     data-mask="{{action('CaptchaController@getMaskImage')}}"
     data-slice="{{action('CaptchaController@getSliceImage')}}"
     data-verify="{{action('CaptchaController@postVerify')}}"
></div>
<script type="text/javascript">
    //(function () {
        let c = new Captcha('#captcha');
         c.Initialize();
    //})();
</script>
</body>
</html>