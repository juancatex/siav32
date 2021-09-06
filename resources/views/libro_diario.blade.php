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
    table {
        
        
        width: 100%;
       
        caption-side: top; 
    }
    tbody {
        /* border-top: 1px solid #000;
        border-bottom: 1px solid #000; */
    }
    tbody th, tfoot th {
        
    }
    body{/* quitar el body para la impresion*/
        font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        position: relative;
        font-size:12px;
        
       /*    
        margin: 8px auto 8px auto;
         */

    }
    .derecha{
        text-align:right;
    }
    span {
        font-size:10px;
        padding-left:50px;
    }
    th{
        padding:5px;
        padding-left: 10px; 
        border-right: 1px solid #999;
        /* border-left: 1px solid #999; */
        

    }
    td{
        padding:5px;
        padding-left: 10px;
    }
    th{
        /* border-top:1px solid #999; */
        
    }
    div{
        background-image:url("{{ asset('img/borrador.png')}}");
    }
   /*  table,td,  th{
        border:1px solid black ;
        width: 800px;
        


    }
    table{
       border-collapse:collapse;
       
    }
    
     */
</style>
<body style="background-color: cadetblue;">
<div class="body_wrapper">

    <!-- {{ $arraydiario }} -->
    @foreach ($arraydiario as $arrayd)
        <table >
            <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Nº</th>
            <th>Tipo Doc.</th>
            <th>Nº Doc.</th>
            <th>Glosa</th>
            <td>Filial: {{ $arrayd->sigla}} </td>
            <td>Unidad: {{ $arrayd->nomunidad}} </td>

           
        </tr>
        <tr>
            <td>{{ $arrayd->fechavalidado }}</td>
            <td>{{ $arrayd->nomtipocomprobante }}</td>
            <td>{{ $arrayd->cod_comprobante }}</td>
            <td>{{ $arrayd->tipodocumento }}</td>
            <td>{{ $arrayd->numdocumento }}</td>
            <td>{{ $arrayd->glosa }}</td>
            
            <td colspan=5>
                <table>
                    <tr>
                        <th>Cod Cuenta</th>
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
                                <td class="derecha"><span>{{ number_format($subdetalles->subdebe,2) }}</span></td>
                                <td class="derecha"><span>{{ number_format($subdetalles->subhaber,2) }}</span></td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td colspan=6 class="derecha"><b>TOTAL COMPROBANTE</b>
            <td style="width:100px"> </td>
            <td style="width:100px"></td>
            <td class="derecha"><b>{{ number_format($arrayd->sdebe,2) }}</b></td>
            <td class="derecha"><b>{{ number_format($arrayd->shaber,2) }}</b></td>
            <td></td>
        </tr>        
    

        </table>
        <hr>
    @endforeach
    <table>
        <tr>
            <th colspan=9 class="derecha" style="width:450px"><b>TOTAL GENERAL</b></th>
            <th class="derecha"><b>{{ number_format($totaldebe,2) }}</b></th>
            <th class="derecha"><b>{{ number_format($totalhaber,2) }}</b></th>
        </tr>
    </table>

    
    </div> 
</body>
</html>