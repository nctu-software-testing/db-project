<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="public-path" content="{{ asset('/') }}" />
<link rel="shortcut icon" href="{{asset('favicon.ico')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/all.css')}}">

<!-- Include Script -->
<script type="text/javascript" src="{{asset('js/mdbootstrap.4.4.0.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dist/js/app.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jsencrypt.min.js')}}"></script>
<!-- Include Script -->

@yield('extraScript')
<title>Any Buy 任購網</title>