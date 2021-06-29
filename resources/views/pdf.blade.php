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
    max-width: 750px;
    margin: 0 auto;
    
}
    table {
        width: 100%;
        
        border-collapse: collapse;
        margin: 0 0 1em 0;
        caption-side: top;
    }
    tbody {
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }
    tbody th, tfoot th {
        border: 0;
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
    td, th{
        padding:5px;
        padding-left: 10px;
        border-right: 1px solid #999;
        border-left: 1px solid #999;
        

    }
    th{
        border-top:1px solid #999;
        
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
<body>
<div class="body_wrapper">

    <table>
        <tr>
            <th style="width: 20%" rowspan=5></th>
            <th style="width: 50%; text-align:center" colspan=2> COMPROBANTE DE {{ strtoupper($maestros[0]->nomtipocomprobante) }} Nº {{ strtoupper($maestros[0]->cont_comprobante) }}</th>
            <th style="width: 30%; text-align:center"> {{ strtoupper($maestros[0]->cod_comprobante) }}</th>
        </tr>
        <tr>
            <th style="width: 100 px; text-align:left">Documento: </th>
            <td>{{ $maestros[0]->tipodocumento }} </td>
            <td rowspan=4>
                <table> 
                    <tr>
                        <th colspan=2>DATOS DEL REPORTE</th>
                    </tr>
                    <tr>
                        <th style="text-align:left" >Hora:</th>
                        <td> {{ date("H:i:s") }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">Fecha: </th>
                        <td>{{ date("d-M-Y") }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">Usuario: </th>
                        <td>{{ Auth::user()->username }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th style="width: 100px; text-align:left">Nº Documento: </th>
            <td> {{ $maestros[0]->numdocumento }}</td>
        </tr>
        <tr>
            <th style="width: 100px; text-align:left; vertical-align:top">Glosa: </th>
            <td> {{ $maestros[0]->glosa }}</td>
        </tr>
        <tr>
            <th style="width: 100px; text-align:left">Fecha </th>
            <td> {{ $maestros[0]->fechavalidado }}</td>
        </tr>
    </table>
</br>
    <table >
        <thead>
            <tr>
                <th style="width: 15%">CUENTA</th>
                <th>DESCRIPCION</th>
                <th style="width: 15%">DEBE</th>
                <th style="width: 15%">HABER</th>
                <th style="width: 5%">T/C</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $det)
            <tr style="border:0">
                
                <td style="vertical-align:top"><strong>{{ $det->codcuenta }}</strong></td>
                <td><strong><u>{{ $det->nomcuenta }}</u> </strong></br>
                    <span>{{ $det->numpapeleta }} {{ $det->apaterno }} {{ $det->amaterno }} {{ $det->nombre }}</span>
                </td>
                <td class="derecha">{{ number_format($det->debe,2) }}</td>
                <td class="derecha">{{ number_format($det->haber,2) }}</td>
                <td class="derecha">1</td>
            <tr>
            @endforeach
        </tbody>
        <tr>
            <th colspan=2 class="derecha"> TOTALES:</th>
            <th class="derecha"><strong>{{ number_format($totald,2) }}</strong></th>
            <th class="derecha"><strong>{{ number_format($totalh,2) }}</strong></th>
            <th></th>
        </tr>
        
    </table>    
    
    </div> 
</body>
</html>