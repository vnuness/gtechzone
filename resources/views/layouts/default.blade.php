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
    <link href="{{ asset('plugins/switchery/switchery.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css">

    @stack("plugin-css")

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @stack("page-css")

    <script src="{{ asset('js/modernizr.min.js') }}"></script>

</head>
<body class="fixed-left">

<div id="wrapper">

    <div class="topbar">

        <div class="topbar-left">
            <div class="text-center">
                <a href="{{route('home')}}" class="logo">
                    <img src="{{asset('images/logo.png')}}" style="max-height:40px;" alt="MeetaWeb">
                    <span>{{ config('app.name', 'meetaweb') }}</span>
                </a>
            </div>
        </div>

        @include('layouts.default-parts.header')

    </div>

    @include('layouts.default-parts.menu-left')

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <h4 class="page-title">@yield('page-title','Titulo')</h4>
                            @include('layouts.default-parts.breadcrumb')
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> Alerta!</h4>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <strong><i class="fa fa-check-circle"></i> </strong> {{ session()->get('success') }}.
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <strong><i class="fa fa-check-circle"></i> </strong> {{ session('status') }}
                    </div>
                @endif

                @yield('content')

            </div>


            <!-- end container -->
        </div>
        <!-- end content -->

        <footer class="footer">
            {{ date('Y') }} © MeetaWeb Solutions
        </footer>

    </div>

    @include('layouts.default-parts.right-bar')

    @stack('plugin-html')

</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/detect.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/fastclick.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/jquery.blockUI.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>

@stack('plugin-js')

<script src="{{ asset('js/jquery.core.js') }}"></script>
<script src="{{ asset('js/jquery.app.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

@stack('page-js')

<script>
    var resizefunc = [];
</script>

</body>
</html>
