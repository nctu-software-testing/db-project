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
<p class="text-center" style="margin-top: -1.5rem">
    請按住拖動完成上方拼圖
</p>
<script type="text/javascript" nonce="{{$nonce}}">
    (function () {
        let c = new Captcha('#captcha');
        c.Initialize()
            .then(function () {
                if (self !== top && window.frameElement) {
                    let callback = () => {
                        window.frameElement.style.width = '512px';
                        window.frameElement.style.height = '370px';
                    };
                    callback();
                    window.addEventListener('load', callback);
                }
            });

        window.CReset = () => c.Reset();
    })();
</script>
</body>
</html>