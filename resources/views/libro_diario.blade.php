<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<style>


.body_wrapper {
    padding: 10px 20px 10px 20px;
    background: rgb(255, 255, 255) none;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    max-width: 1050px;
    margin: 0 auto;
    
}
   
    body{/* quitar el body para la impresion*/
        font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        position: relative;
        font-size:12px;
    }
    .derecha{
        text-align:right;
        padding-right: 10px;
    }
    .izquierda{
        text-align: left;
    }
    .centro{
        text-align: center;
    }
    span {
        font-size:10px;
        padding-left:50px;
    }
    div{
        background-image:url("{{ asset('img/borrador.png')}}");
    }
    table{
        width: 100%;
        /* border: 1px solid #024a86;
        border-collapse: collapse; */
        
    }
    .tabla2 th, .tabla2 td{
        /* border: 1px solid #024a86; */
        border-bottom: 1px solid #024a86;
        
    }
    th{
        font-size: 14px;
        border-bottom: 1px solid #024a86;
    }
    td{
        /* border-right: 1px solid #024a86; */
        border-bottom: 1px solid #024a86;
    }
    .tabla2{
        width: 100%;
       /*  border: 1px solid #024a86;
        border-collapse: collapse; */

    }
    hr {
        border: 3px solid green;
        border-radius: 2px;
    }
     
</style>
<body style="background-color: cadetblue;">
<div class="body_wrapper">

    <!-- {{ $arraydiario }} -->
    <div class="centro">
        <h1>LIBRO DIARIO</h1>
        <h2>ASCINALSS</h2>
        <h3>REPORTE PRELIMINAR</h3>
    </div>
    <table>
    @foreach ($arraydiario as $arrayd)
        <tr>
            <th style="width:7%;">Fecha</th>
            <th style="width:8%;">Tipo</th>
            <th style="width:10%;">Tipo Doc.</th>
            <th style="width:25%;">Glosa</th>
            <th colspan="2" class="izquierda">Filial: {{ $arrayd->sigla}} </th>
            <th colspan="3" class="izquierda">Unidad: {{ $arrayd->nomunidad}} </th>
        </tr>
        <tr>
            <td class="centro">{{ $arrayd->fechavalidado }}</td>
            <td>{{ $arrayd->nomtipocomprobante }} </br>{{ $arrayd->cod_comprobante }}</td>
            <td>{{ $arrayd->tipodocumento }}</br> {{ $arrayd->numdocumento }}</td>
            <td>{{ $arrayd->glosa }}</td>
            
            <td colspan=5>
                <table class="tabla2">
                    <tr >
                        <th >Cod Cuenta</th>
                        <th>Nom Cuenta</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th>T/C</th>
                    </tr>
                @foreach($arrayd->asientodetalle as $detalle)
                    <tr>
                        <td>{{ $detalle->codcuenta }}</td>
                        <td>{{ $detalle->nomcuenta }}</td>
                        <td class="derecha"><b> {{ number_format($detalle->debe,2) }} </b></td>
                        <td class="derecha"><b>{{ number_format($detalle->haber,2) }} </b> </td>
                        <td>1.00000</td>
                    </tr>
                    @if(count($detalle->asientosubdetalles)>0)
                        @foreach($detalle->asientosubdetalles as $subdetalles)
                            <tr>
                                <td colspan=2><span>{{ $subdetalles->subcuenta }} {{ $subdetalles->nombre }}</span></td>
                                <td class="derecha" ><span>{{ number_format($subdetalles->subdebe,2) }}</span></td>
                                <td class="derecha"><span>{{ number_format($subdetalles->subhaber,2) }}</span></td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                <tr>
                    <th colspan=2 class="derecha"><b>TOTAL COMPROBANTE</b></th>
                    <th class="derecha"><b>{{ number_format($arrayd->sdebe,2) }}</b></th>
                    <th class="derecha"><b>{{ number_format($arrayd->shaber,2) }}</b></th>
                    <th></th>
                </tr>  
                </table>
            </td>
        </tr>
        
        <tr>
            <th colspan="9"><hr></th>
        </tr>      
    

       
    @endforeach
    </table>
    <hr>
        
    <table>
        <tr>
            <th class="derecha" style="width:770px"><b>TOTAL GENERAL</b></th>
            <th class="derecha" style="width:88px"><b>{{ number_format($totaldebe,2) }}</b></th>
            <th class="derecha" style="width:88px"><b>{{ number_format($totalhaber,2) }}</b></th>
            <th></th>
        </tr>
    </table>

    
    </div> 
</body>
</html>