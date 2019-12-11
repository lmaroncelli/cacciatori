<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/skins/skin-green.min.css') }}" rel="stylesheet">

     <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">

  <link rel="stylesheet" href="{{ asset('css/google_font/google.css') }}">
{{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> --}}
    
    @yield('header_css')

    <link href="{{ asset('css/AdminLTE.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
</head>

<body class="hold-transition skin-green layout-top-nav">
    <!-- Main Header -->
    <header class="main-header">
      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Logo -->
        <a href="{{ route('pagina_esterna') }}" class="logo" data-toggle="tooltip" title="Gestione Piano Venatorio">
          <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Piano <b>Venatorio</b></span>
      </a>
      </nav>
    </header>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <div class="container">
            <!-- Main content -->    
            <section class="content container-fluid">
                @yield('content')
            </section>
          </div> <!-- /.container -->
        </div>   <!-- /.content-wrapper -->
        @include('layouts.footer')
        <div class="control-sidebar-bg"></div>
    </div> <!-- ./wrapper -->
    
    <!-- Scripts -->
    
    <!-- jQuery 3 -->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    
    <script src="{{ asset('js/bootstrap.js') }}"></script>
   <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>

    @yield('script_footer')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
       });
    </script>

</body>
</html>
