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
b{
    font-size:18px;
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
        padding-right: 10px;
    }
    .izquierda{
        text-align:left;
    }
    span {
        font-size:10px;
        padding-left:50px;
    }
  
   
    div{
        background-image:url("{{ asset('img/borrador.png')}}");
    }
    .borrador{
        background-color: coral;
        color: white;
    }
    table{
        width: 100%;
        /* border: 1px solid #024a86;
        border-collapse: collapse; */
        
    }
    th{
        font-size: 14px;
        border-bottom: 1px solid #024a86;
    }
    td{
        /* border-right: 1px solid #024a86; */
        border-bottom: 1px solid #024a86;
    }
    hr {
        border: 3px solid green;
        border-radius: 2px;
    }
</style>
<body style="background-color: cadetblue;">
<div class="body_wrapper">
 
    <h2>Filial: {{ $nomfilial }} Unidad: {{ $nomsigla }} </h2>
    @foreach ($arraymayor as $arraym)
        <table >
            <tr>
                <th colspan="2"><b>CUENTA</b></th>
                <th colspan="2" class="izquierda"><b>{{ $arraym->codcuenta }} {{ $arraym->nomcuenta }}</b></th>
                <th colspan="4" class="derecha"><b>Saldo anterior: {{ number_format($arraym->saldo,2) }}</b></th>
            <tr>
                <th style="width: 8%;" >Fecha</th>
                <th style="width: 8%;">Tipo</th>
                <!-- <th >Nº</th> -->
                <th style="width: 14%;">Tipo Doc.</th>
                <!-- <th >Nº Doc.</th> -->
                <th style="width: 35%;">Glosa</th>
                <th style="width: 10%;">Debe</th>
                <th style="width: 10%;">Haber</th>
                <th style="width: 15%;">Saldo</th>

            </tr>
            @foreach($arraym->detalles as $arrayd)
                
                <tr class=" {{ $arrayd->estado==5?'borrador':''}}">
                
                    <td>{{ $arrayd->fechavalidado }}</td>
                    <td>{{ $arrayd->nomtipocomprobante }} </br>{{ $arrayd->cod_comprobante }}</td>
                   <!--  <td></td> -->
                    <td>{{ $arrayd->tipodocumento }} </br>{{ $arrayd->numdocumento }}</td>
                    <!-- <td></td> -->
                    <td>{{ $arrayd->glosa }}</td>
                    <td class="derecha">{{ number_format($arrayd->debe,2) }}</td>
                    <td class="derecha">{{ number_format($arrayd->haber,2) }}</td>
                    <th class="derecha">{{ number_format($arrayd->saldofinal,2) }}</th>
                    
                </tr>
                @foreach($arrayd->subdetalles as $subdetalles)
                    <tr>
                        <td colspan=2></td>
                        <td colspan="2">{{ $subdetalles->subcuenta }} {{ $subdetalles->nombre }}</td>
                        <td class="derecha"><span>{{ number_format($subdetalles->subdebe,2) }}</span></td>
                        <td class="derecha"><span>{{ number_format($subdetalles->subhaber,2) }}</span></td>
                        
                    </tr>    
                @endforeach
            @endforeach
            </table>
        <hr>
    @endforeach
    </div> 
</body>
</html>