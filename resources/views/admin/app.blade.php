<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JAMIPOL SURAKSHA') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/> -->
    <!-- Styles -->
    <link href="{{  asset('css/app.css') }}" rel="stylesheet">
    <link href="{{  asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{  asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{  asset('css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{  asset('css/admin.css')}}" rel="stylesheet">
    <link href="{{  asset('css/fontawesome-free/css/all.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <!-- <link href="{{  asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"> -->
</head>

<body>
    <style>
        .verror {
            border: 1px solid #B10101;
        }

        .verror:focus {
            border: 1px solid #B10101;
        }
    </style>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"></h1>
        </div>
        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('admin.dashboard')}}"><img
                    src="{{ URL::to('images/top_logo.png') }}"> </a>
            <div style="padding: 0px 0px 0px 0px;height: 79px;" class="d-sm-block d-none">
                <img src="{{ URL::to('images/wps.png') }}" style="float:left;">
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" style="margin-top: 2rem">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                </div>
                <nav class="col-md-2 d-md-block sidebar">


                    <nav class="col-md-2 d-md-block sidebar">
                        @include('admin.partials.navbar')
                        @include('admin.partials.site_config')
                    </nav>

                </nav>
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
    <!-- Scripts -->

    <script type="text/javascript" src="{{ asset('js/app.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/sweetalert.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/jszip.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/buttons.html5.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/all.js') }}"> </script>

    <!-- <script type="text/javascript" src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"> </script> -->
    <script type="text/javascript">
        function form_validate() {
            var flag = true;
            $(".rec").each(function (e) {

                if ($(this).val() == "") {
                    $(this).addClass("verror");
                    flag = false;
                }
                else {
                    $(this).removeClass("verror");
                }
            })
            if (flag == true) {
                var c = confirm("Are you sure want to save.");
                if (c) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
    </script>
    @yield('scripts')
</body>

</html>