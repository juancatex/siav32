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
     
    <script src="js/app.js"></script>  
    <script src="js/plantilla.js"></script>
    <script>
        //obtiene el tiempo de la session y lo muestra en pantalla.
     var _$_9ba9=["now","max","href","location","/sessionOut","round","getUTCMinutes","0",":","getUTCSeconds","text","#timeout","click keydown keypress keyup submit change mouseenter scroll resize dblclick","bind","*","mousemove","trigger","body","ready"];$(document)[_$_9ba9[18]](function(){const _0x222BB=600000;let _0x2220F=(()=>{let _0x22367=setTimeout,_0x22311={};return setTimeout= ((_0x223BD,_0x22413)=>{let _0x22469=_0x22367(_0x223BD,_0x22413);return _0x22311[_0x22469]= Date[_$_9ba9[0]]()+ _0x22413,_0x22469}),(_0x22367)=>_0x22311[_0x22367]?Math[_$_9ba9[1]](_0x22311[_0x22367]- Date[_$_9ba9[0]](),0):NaN})();var _0x22265=setTimeout(function(){window[_$_9ba9[3]][_$_9ba9[2]]= _$_9ba9[4]},_0x222BB);setInterval(()=>{var _0x22515=_0x2220F(_0x22265);_0x22515= 1000* Math[_$_9ba9[5]](_0x22515/ 1000);var _0x224BF= new Date(_0x22515);$(_$_9ba9[11])[_$_9ba9[10]]((parseInt(_0x224BF[_$_9ba9[6]]())> 9?parseInt(_0x224BF[_$_9ba9[6]]()):_$_9ba9[7]+ parseInt(_0x224BF[_$_9ba9[6]]()))+ _$_9ba9[8]+ (parseInt(_0x224BF[_$_9ba9[9]]())> 9?parseInt(_0x224BF[_$_9ba9[9]]()):_$_9ba9[7]+ parseInt(_0x224BF[_$_9ba9[9]]())))},1000);$(_$_9ba9[14])[_$_9ba9[13]](_$_9ba9[12],function(){clearTimeout(_0x22265);_0x22265= setTimeout(function(){window[_$_9ba9[3]][_$_9ba9[2]]= _$_9ba9[4]},_0x222BB)});$(_$_9ba9[17])[_$_9ba9[16]](_$_9ba9[15])})
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