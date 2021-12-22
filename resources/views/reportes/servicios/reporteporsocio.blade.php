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
        
        border-collapse: collapse; 
        width: 100%;
        font-size: 12px; 
        caption-side: top;  
    }
    
    body{/* quitar el body para la impresion*/
        font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        position: relative;
        font-size:12px;  
    }
    p{
        text-align: justify;
        font-size: 16px;
    }
    span{
        font-weight: bold;
    }
    .span2{
        font-size: 10px;
    }
    .derecha{
        text-align:right;
    }
</style>
<body style="text-align:center">
<div class="body_wrapper">
<div class="left"> <img src="{{$foto}}" width="90%"> </div>
   <h2 style="color: #0c49a5;">REPORTE DE HOSPEDAJE POR SOCIO</h2>
   <h3 style="color: #0c49a5;"><b>Socio:</b>  <i>{{ $nombrec }}</i></h3>
   <h4 style="color: #0c49a5;"> </h4>
   <hr>
   <h4 style="color: #0c49a5;">Registro de Salidas:</h4>
   <table>
       <thead>
           <tr>
           <th>Nº</th>
           <!-- <th>Fuerza</th>
           <th>Nombres y Apellidos</th> -->
           <th>Fecha Ingreso</th>
           <th>Fecha Salida</th>
           <th>Habitacion</th>
           <th>Factura</th>
           <th>Monto Cancelado</th>
           <th>Tipo</th>
           </tr>
           
       </thead>
       <tbody>
           @php 
           $i=1;
           $desc=0;
           $conta=0;
           $total=0;
           @endphp
           @foreach ($data as $sal)
           <tr>
               <td>{{$i}}</td>
               <!-- @if($sal->tipocliente==1)
                    <td style="text-align: left;">{{$sal->nomfuerza}}</td>
               @elseif($sal->tipocliente==2)
                    <td style="text-align: left;">Civil</td>
                @else
                    <td style="text-align: left;">Familiar</td>
               @endif 
               <td style="text-align: left;">{{$sal->nombres}}</td>-->
               <td style="text-align: center;    padding-bottom: 15px;">{{$sal->fechaentrada}} {{$sal->horaentrada}}<br/><b>Usuario: </b><i>{{$sal->uentrada}}</i></td>
              
               
               @if($sal->estado==0)
               <td style="text-align: center;    padding-bottom: 15px;">{{$sal->fechasalida}} {{$sal->horasalida}}<br/><b>Usuario: </b><i>{{$sal->usalida}}</i></td>
               @else
               <td style="text-align: center;    padding-bottom: 15px;">{{$sal->fechasalida}} {{$sal->horasalida}}</td>
               @endif

               <td style="text-align: center;">{{$sal->codambiente}}</td>
               <td style="text-align: center;">{{$sal->numerofactura}}</td>
               <td style="text-align: right;">{{$sal->monto}} Bs.</td>
               
               @if($sal->estado==0)
                    @if($sal->descuento==0)
                        @php    $conta=$conta+$sal->monto; @endphp
                            <td style="text-align: center;">Contado</td>
                    @else
                            <td style="text-align: center;">Descuento</td>
                        @php   $desc=$desc+$sal->monto;  @endphp
                    @endif
               @else
               <td></td>
               @endif
               
           </tr> 
           @php 
           $i=$i+1
           @endphp
           @endforeach
           @php   $total=$total+$desc+$conta;  @endphp
       </tbody>
   </table>
<hr>
   <table style="width: 50%;">
        <thead>
            <tr>
                <th colspan="2">Resumen:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Efectivo:</strong></td>
                <td style="text-align: right;">{{ $conta }} Bs.</td>
            </tr>
            <tr>
                <td><strong>Descuento:</strong></td>
                <td style="text-align: right;">{{ $desc }} Bs.</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td style="text-align: right;">{{ $total }} Bs.</td>
            </tr>
        </tbody>
    
   </table>

  

   
    
    </div> 
</body>
</html>