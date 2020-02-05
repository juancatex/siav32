<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png">
    <title>SIA - ASCINALSS</title> 
    <link href="{{ asset('css/login.css') }}" rel="stylesheet"> 
</head>
<body>
    <div id="app"> 
        @yield('content')
    </div> 
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
