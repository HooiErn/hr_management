<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Interview Video Conference">
    <title>{{ config('app.name', 'HRTech') }} - Interview Session</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::to('assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">
</head>
<body class="account-page">
    @yield('content')

    <!-- jQuery -->
    <script src="{{ URL::to('assets/js/jquery-3.6.0.min.js') }}"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="{{ URL::to('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html> 