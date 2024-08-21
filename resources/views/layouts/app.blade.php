<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Login HRTech</title>
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('/images/logo-circle.png')}}">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <!-- Lineawesome CSS -->
        <link rel="stylesheet" href="assets/css/line-awesome.min.css">
        <!-- Select2 CSS -->
        <link rel="stylesheet" href="assets/css/select2.min.css">
        <!-- Datetimepicker CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
        <!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="assets/css/toastr.min.css">
        <script src="assets/js/toastr_jquery.min.js"></script>
        <script src="assets/js/toastr.min.js"></script>
    </head>
    <body class="account-page error-page">
        <style>    
            .invalid-feedback{
                font-size: 14px;
            }
        </style>

        <!-- Main Wrapper -->
        @yield('content')
        <!-- /Main Wrapper -->
         
        <!-- jQuery -->
        <script src="assets/js/jquery-3.5.1.min.js"></script>
        <!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- Slimscroll JS -->
        <script src="assets/js/jquery.slimscroll.min.js"></script>
        <!-- Select2 JS -->
        <script src="assets/js/select2.min.js"></script>
        <!-- Datetimepicker JS -->
        <script src="assets/js/moment.min.js"></script>
        <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
        <!-- Custom JS -->
        <script src="assets/js/app.js"></script>
        @yield('script')
    </body>
</html>
