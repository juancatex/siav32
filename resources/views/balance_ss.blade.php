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
    <h3>BALANCE GENERAL</h3>
    <table>
        <tr>
            <th><b>CUENTA</b></th>
            <th class="izquierda" colspan=4><b>DETALLE</b></th>
            <th colspan=2><b>SALDO</b></th>
        </tr>
        @foreach ($cuentas as $cuenta1)
            @if($cuenta1->saldonn!=0)    
                <tr>
                    <td>{{ $cuenta1->codn1 }}</td>
                    <td colspan=4>{{ $cuenta1->nomcuentan1}}</td>
                    <td class="derecha">{{ number_format($cuenta1->saldonn,2) }}</td>
                    <td></td>
                </tr>
                @foreach($cuenta1->cuentan2 as $cuenta2)
                    @if($cuenta2->saldon2!=0)
                        <tr>
                            <td>&nbsp;{{ $cuenta2->codn2 }}</td>
                            <td></td>
                            <td colspan=3>{{ $cuenta2->nomcuentan2 }}</td>
                            <td class="derecha">{{ number_format($cuenta2->saldon2,2) }}</td>
                            <td></td>
                        </tr>
                        @foreach($cuenta2->cuentan3 as $cuenta3)
                            @if($cuenta3->saldon3!=0)
                                <tr>
                                    <td>&nbsp;&nbsp;{{ $cuenta3->codn3 }}</td>
                                    <td></td>
                                    <td></td>
                                    <td colspan=2>{{ $cuenta3->nomcuentan3 }}</td>
                                    <td class="derecha">{{ number_format($cuenta3->saldon3,2) }}</td>
                                </tr>
                                @foreach($cuenta3->cuentan4 as $cuenta4)
                                    @foreach($cuenta4->cuentan5 as $cuenta5)
                                        @foreach($cuenta5->cuentas as $cuentas)
                                            @if($cuentas->saldos!=0)
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;{{ $cuentas->codcuenta }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $cuentas->nomcuenta }}</td>
                                                    <td></td>
                                                    
                                                    <td class="derecha">{{ number_format($cuentas->saldos,2) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    </table>
    </div> 
</body>
</html>