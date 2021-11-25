<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>SIA - ASCINALSS</title> 
    <link rel="shortcut icon" type="image/png" href="img/favicon.png">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="wrapper">   
            @yield('content') 
    </div> 
    <script src="{{ asset('js/login.js') }}"></script>
    <script>
         $('.login').on('submit', function(e) {
                e.preventDefault();
                if (this.working) return;
                this.working = true;
                var $this = $(this),
                $state = $this.find('button > .state');
                $this.addClass('loading');
                $state.html('Verificando datos'); 
                setTimeout(function() {
                    e.currentTarget.submit();
                  }, 2000);
              });
    </script>
</body>
</html>
 