<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JAMIPOL Work Permit System') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/> -->
    <!-- Styles -->
    <link href="{{  asset('public/css/app.css') }}" rel="stylesheet" >
    <link href="{{  asset('public/css/jquery.dataTables.min.css') }}" rel="stylesheet" >
    <link href="{{  asset('public/css/buttons.dataTables.min.css') }}" rel="stylesheet" >
    <link href="{{  asset('public/css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{  asset('public/css/admin.css')}}" rel="stylesheet">
    <link href="{{  asset('public/css/fontawesome-free/css/all.min.css') }}">
    
	
	
	
    <!-- <link href="{{  asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"> -->
</head>
<body>

    
                <div class="col-md-12">
                    @yield('content')
                </div>
           
    <!-- Scripts -->
      
    <script type="text/javascript" src="{{ asset('public/js/app.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('public/js/sweetalert.js') }}"> </script>
    
    <script type="text/javascript" src="{{ asset('public/js/jquery.dataTables.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('public/js/dataTables.buttons.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('public/js/jszip.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('public/js/buttons.html5.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('public/js/all.js') }}"> </script>

    <!-- <script type="text/javascript" src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"> </script> --> 
    
    
</body>
</html>