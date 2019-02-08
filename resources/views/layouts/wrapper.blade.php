<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MeetaWeb') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css">
    
    @stack("plugin-css")    
    
    <link href="{{ asset('css/style_dark.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">

    @stack("page-css")

</head>

<body>

<div class="wrapper-page">

    @yield('content')

</div>

<script>
    var resizefunc = [];
</script>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>

<script src="{{ asset('js/detect.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/fastclick.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>

<script src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>

@stack('plugin-js')

<script src="{{ asset('js/jquery.core.js') }}"></script>
<script src="{{ asset('js/jquery.app.js') }}"></script>

@stack('page-js')

</body>
</html>
