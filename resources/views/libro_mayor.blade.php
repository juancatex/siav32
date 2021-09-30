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
    table {
        
        
        width: 100%;
       
        caption-side: top; 
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
    .izquierda{
        text-align:left;
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
   
    div{
        background-image:url("{{ asset('img/borrador.png')}}");
    }
    .borrador{
        background-color: coral;
        color: white;
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
 
    <h2>Filial: {{ $nomfilial }} Unidad: {{ $nomsigla }} </h2>
    @foreach ($arraymayor as $arraym)
        <table>
            <tr>
                <th width=30%><b>CUENTA</b></th>
                <th width=50% class="izquierda"><b>{{ $arraym->codcuenta }} {{ $arraym->nomcuenta }}</b></th>
                <th width=20% class="derecha"><b>Saldo anterior: {{ number_format($arraym->saldo,2) }}</b></th>
            </tr>
        </table>
        
        <table >
            <tr>
                <th width=60px>Fecha</th>
                <th width=60px>Tipo</th>
                <th width=60px>Nº</th>
                <th width=90px>Tipo Doc.</th>
                <th width=60px>Nº Doc.</th>
                <th width=350px>Glosa</th>
                <th width=60px>Debe</th>
                <th width=60px>Haber</th>
                <th width=60px>Saldo</th>

            </tr>
            @foreach($arraym->detalles as $arrayd)
                
                <tr class=" {{ $arrayd->estado==5?'borrador':''}}">
                
                    <td>{{ $arrayd->fechavalidado }}</td>
                    <td>{{ $arrayd->nomtipocomprobante }}</td>
                    <td>{{ $arrayd->cod_comprobante }}</td>
                    <td>{{ $arrayd->tipodocumento }}</td>
                    <td>{{ $arrayd->numdocumento }}</td>
                    <td>{{ $arrayd->glosa }}</td>
                    <td class="derecha">{{ number_format($arrayd->debe,2) }}</td>
                    <td class="derecha">{{ number_format($arrayd->haber,2) }}</td>
                    <td class="derecha">{{ number_format($arrayd->saldofinal,2) }}</td>
                    
                </tr>
                @foreach($arrayd->subdetalles as $subdetalles)
                    <tr>
                        <td colspan=5></td>
                        <td>{{ $subdetalles->subcuenta }} {{ $subdetalles->nombre }}</td>
                        <td class="derecha"><span>{{ number_format($subdetalles->subdebe,2) }}</span></td>
                        <td class="derecha"><span>{{ number_format($subdetalles->subhaber,2) }}</span></td>
                        <td></td>
                    </tr>    
                @endforeach
            @endforeach
            </table>
        <hr>
    @endforeach
    </div> 
</body>
</html>