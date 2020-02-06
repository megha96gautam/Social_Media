<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Social Networking App | Dashboard</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/plugins/fontawesome-free/css/all.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Tempusdominus Bbootstrap 4 -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
      <!-- iCheck -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      <!-- JQVMap -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/plugins/jqvmap/jqvmap.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/dist/css/adminlte.min.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
      <!-- Daterange picker -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/plugins/daterangepicker/daterangepicker.css">
      <!-- summernote -->
      <link rel="stylesheet" href="{{url('/')}}/resources/assets/plugins/summernote/summernote-bs4.css">
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

      <link rel="stylesheet" href="{{url('/')}}/resources/assets/css/style.css">

      @yield('current_page_css')
   </head>
   <body class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">

         <!-- Navbar Header -->
         @include('admin.layout.header')

         <!-- Main Sidebar Container -->         
         @include('admin.layout.sidebar')
         
         @yield('content')

         <!-- /.Footer -->
         @include('admin.layout.footer')
         
      </div>
      <!-- ./wrapper -->
      <!-- jQuery -->
      <script src="{{url('/')}}/resources/assets/plugins/jquery/jquery.min.js"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="{{url('/')}}/resources/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
      <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
      <script>
         $.widget.bridge('uibutton', $.ui.button)
      </script>
      <!-- Bootstrap 4 -->
      <script src="{{url('/')}}/resources/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- ChartJS -->
      <script src="{{url('/')}}/resources/assets/plugins/chart.js/Chart.min.js"></script>
      <!-- Sparkline -->
      <script src="{{url('/')}}/resources/assets/plugins/sparklines/sparkline.js"></script>
      <!-- JQVMap -->
      <script src="{{url('/')}}/resources/assets/plugins/jqvmap/jquery.vmap.min.js"></script>
      <script src="{{url('/')}}/resources/assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
      <!-- jQuery Knob Chart -->
      <script src="{{url('/')}}/resources/assets/plugins/jquery-knob/jquery.knob.min.js"></script>
      <!-- daterangepicker -->
      <script src="{{url('/')}}/resources/assets/plugins/moment/moment.min.js"></script>
      <script src="{{url('/')}}/resources/assets/plugins/daterangepicker/daterangepicker.js"></script>
      <!-- Tempusdominus Bootstrap 4 -->
      <script src="{{url('/')}}/resources/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
      <!-- Summernote -->
      <script src="{{url('/')}}/resources/assets/plugins/summernote/summernote-bs4.min.js"></script>
      <!-- overlayScrollbars -->
      <script src="{{url('/')}}/resources/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
      <!-- AdminLTE App -->
      <script src="{{url('/')}}/resources/assets/dist/js/adminlte.js"></script>
      <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
      <script src="{{url('/')}}/resources/assets/dist/js/pages/dashboard.js"></script>
      <!-- AdminLTE for demo purposes -->
      <script src="{{url('/')}}/resources/assets/dist/js/demo.js"></script>

      @yield('current_page_js')
   </body>
</html>