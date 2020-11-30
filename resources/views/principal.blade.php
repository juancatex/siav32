<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema ASCINALSS">
    <meta name="author" content="Dep. Sistemas">
    <meta name="keyword" content="Sistema ASCINALSS">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png">
    <title>Sistema Integrado ASCINALSS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="css/plantilla.css" rel="stylesheet">   
</head>

<body >
    <div class="onload"><div class="onloadDiv"><h1>Bienvenido</h1> <h2>Sistema ASCINALSS</h2><div class="lds-dual-ring"></div></div></div>
    <div id="onloaditem" class="onloaditem"><div class="onloadDivitem"><div class="lds-dual-ring-item"></div></div></div>
    <div id="app" style="display:none" class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    
    <header class="app-header navbar noprint">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>      
        <font class="nav-link">SIA - ASCINALSS </font> 
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
            <span class="navbar-toggler-icon"></span>
        </button> 

        <span class="ml-auto"><b>
            <div class="alert alert-danger" style="margin: 0 !important;" role="alert">Fecha del Sistema:
                <?php use App\Http\Controllers\AdmUserController;
                echo  AdmUserController::fecha_sistema() ?></b>
            </div>            
        </span>

         @yield('bodyheader') 
    </header>
    @if(Auth::check())
    <div class="app-body">
            @include('plantilla.sidebar') 
            @yield('contenido')
        </div>
    @endif
    <!-- Cambios realizados por ing. Juan Carlos-->
    <footer class="app-footer">
        <span><a href="http://www.ascinalss.org/ascinalss/index.html">ASCINALSS</a> &copy; <?php echo date('Y')?></span>    
        <span class="ml-auto">Desarrollado por <a href="http://www.ascinalss.org/ascinalss/index.html">Dep. Sistemas</a></span>
    </footer>  
    </div>  
    <!-- <script src="js/fire.js"></script>   -->
    <script src="js/app.js"></script>  
    <script src="js/plantilla.js"></script>
    <script>
        //obtiene el tiempo de la session y lo muestra en pantalla.
    
    //  $(document).ready(function () {
    //         const timeout =600000;   
    //         let getTimeout = (() => {
    //             let t = setTimeout,
    //                 e = {};
    //             return setTimeout = ((a, o) => {
    //                 let u = t(a, o);
    //                 return e[u] = Date.now() + o, u
    //             }), t => e[t] ? Math.max(e[t] - Date.now(), 0) : NaN
    //         })();
    //         var idleTimer = setTimeout(function () {
    //             window.location.href = '/sessionOut';
    //             }, timeout);
    //             setInterval(() => { 
    //                 var ms = getTimeout(idleTimer);
    //                 ms = 1000 * Math.round(ms / 1000);
    //                 var d = new Date(ms);
    //                 $('#timeout').text((parseInt(d.getUTCMinutes())>9?parseInt(d.getUTCMinutes()):'0'+parseInt(d.getUTCMinutes())) + ':' + (parseInt(d.getUTCSeconds())>9?parseInt(d.getUTCSeconds()):'0'+parseInt(d.getUTCSeconds())));
    //             }, 1000); 
    //         $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
    //             clearTimeout(idleTimer); 
    //            idleTimer = setTimeout(function () {
    //             window.location.href = '/sessionOut';
    //             }, timeout);
               
    //         });
    //         $("body").trigger("mousemove"); 
    //     });
     
     $("#clockSession").click(function () {
                 $("#valueSession").animate({
                     width: "toggle"
                 }, function () {
                     if ($('#clockSession a').hasClass("spring")) {
                         $('#clockSession a').removeClass("spring");
                     } else {
                         $('#clockSession a').addClass("spring");
                     }
                 }); 
     });
       $("#valueSession").animate({width: "toggle"});
    </script>
</body>
</html>