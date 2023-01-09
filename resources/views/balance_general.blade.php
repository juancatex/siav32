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
    b{
        font-size: 14px;
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
        @php 
           $activos=0;
           @endphp
           @php 
           $sumpasivopatrimiinio=0;
           @endphp
        @foreach ($cuentas as $cuenta1)

           @php 
           $activos+=($cuenta1->codn1==1)?$cuenta1->saldonn:0;
           @endphp
           @php 
           $sumpasivopatrimiinio+=($cuenta1->codn1==2||$cuenta1->codn1==3)?$cuenta1->saldonn:0;
           @endphp
                     @if($nivel>=1)
                        <tr style="    background: aqua;">
                            <td><b>{{ $cuenta1->codn1 }}</b></td>
                            <td colspan=6><b>{{ strtoupper($cuenta1->nomcuentan1)}} </b></td>
                            <td class="derecha">{{ number_format($cuenta1->saldonn,2) }}</td>
                            <td></td>
                        </tr>
                        @endif
                @foreach($cuenta1->cuentan2 as $cuenta2)
                            @if($nivel>=2) 
                                <tr>
                                    <td>&nbsp;<b>{{ $cuenta2->codn2 }}</b></td>
                                    <td></td>
                                    <td colspan=5><b>{{ strtoupper($cuenta2->nomcuentan2) }}</b></td>
                                    <td class="derecha">{{ number_format($cuenta2->saldon2,2) }}</td>
                                    <td></td>
                                </tr>
                                @endif
                        @foreach($cuenta2->cuentan3 as $cuenta3)
                                @if($nivel>=3) 
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $cuenta3->codn3 }}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan=4><b>{{ strtoupper($cuenta3->nomcuentan3) }}</b></td>
                                        <td class="derecha">{{ number_format($cuenta3->saldon3,2) }}</td>
                                    </tr>
                                    @endif
                                @foreach($cuenta3->cuentan4 as $cuenta4)
                                        @if($nivel>=4) 
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $cuenta4->codn4 }}</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td colspan=3><b>{{ strtoupper($cuenta4->nomcuentan4) }}</b></td>
                                            <td class="derecha">{{ number_format($cuenta4->saldon4,2) }}</td>
                                        </tr>
                                        @endif
                                    @foreach($cuenta4->cuentan5 as $cuenta5)
                                            @if($nivel>=5)
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $cuenta5->codn5 }}</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan=2><b>{{ strtoupper($cuenta5->nomcuentan5) }}</b></td>
                                                <td class="derecha">{{ number_format($cuenta5->saldon5,2) }}</td>
                                            </tr> 
                                            @endif

                                        @foreach($cuenta5->cuentas as $cuentas)
                                            @if($cuentas->saldos!=0&&$nivel>=5)
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $cuentas->codcuenta }}</td>
                                                    <td></td>
                                                    <td></td>
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
                            
                        @endforeach
                    
                @endforeach
            
        @endforeach
                         <tr> 
                            <td colspan=7><b>Total activos </b></td>
                            <td colspan=2 class="derecha">{{  $activos}}</td> 
                        </tr>
                        <tr> 
                            <td colspan=7><b>Total pasivos + patrimonio </b></td>
                            <td colspan=2 class="derecha">{{  $sumpasivopatrimiinio}}</td> 
                        </tr>
    </table>
    </div> 
</body>
</html>