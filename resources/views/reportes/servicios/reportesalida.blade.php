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
        /* border: 1px solid #000; */
        width: 100%;
        font-size: 15px;
        
       
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
   <h2 style="color: #0c49a5;">REGISTRO DE SALIDA HOSPEDAJE N°  {{ $hospedaje[0]->idasignacion }}</h2>
   <hr>
   <table>
       <tbody>
           <tr style="height: 80px;">
               <td colspan="6" ><strong >DATOS DE SALIDA:</strong>
                   </td>
           </tr>
           
           <tr >
               
                <td style="text-align: left;"><strong>Nombre: </strong></td>
                <td colspan="3" style="text-align: left;">{{$hospedaje[0]->nombres}}</td>
                @if($hospedaje[0]->tipocliente==3)
                <td style="text-align: left;"><strong> parentezco:</strong></td>
                <td style="text-align: left;">{{ $hospedaje[0]->parentezco }}</td>
                @endif
                
                @if($hospedaje[0]->numpapeleta!='')
                <td style="text-align: left;"><strong> Numero de Boleta:</strong></td>
                <td style="text-align: left;">{{ $hospedaje[0]->numpapeleta }}</td>
                @endif
           </tr>
           <tr >
                <td style="text-align: left;"><strong>CI:</strong></td>
                <td colspan="5" style="text-align: left;">{{$hospedaje[0]->ci}}</td>
                
           </tr>
           @if($hospedaje[0]->tipocliente==3)
                <tr>   
                    <td><strong>Familiar de:</strong></td>
                    <td colspan="5" style="text-align: left;">{{$hospedaje[0]->nombresocio}}</td>
                </tr>
             @endif
           <tr><td colspan="6" style="color: white;">.</td></tr>
           <tr style="height: 80px;">
               <td colspan="6" ><strong> DATOS DE ASIGNACION: </strong></td>
            </tr>
            <tr>
               <td style="text-align: left;"><strong>Habitacion:</strong></td>
                <td style="text-align: left;" colspan="5">{{$hospedaje[0]->codambiente}}</td>
            </tr>
            <tr>
                <td style="text-align: left;"><strong>Fecha de Ingreso:</strong></td>
                <td style="text-align: left;">{{$hospedaje[0]->fechaentrada}} - {{$hospedaje[0]->horaentrada}}</td>
                <td style="text-align: left;"><strong>Fecha de Salida:</strong></td>
                <td style="text-align: left;">{{$hospedaje[0]->fechasalida}} - {{$hospedaje[0]->horasalida}}</td>
               <td colspan="2"><strong> Noches </strong> {{$hospedaje[0]->cantdias}}</td>
           </tr>
           <tr>
               <td colspan="6" style="text-align: left; height: 50px;" ><strong>Implementos Entregados:</strong></td>
           </tr>
           @foreach ($implementos as $impl)
           <tr>
               <td style="width: 100px;"></td>
               <td colspan="5" style="text-align: left;">{{ $impl->nomimplemento }}</td>
           </tr>
           @endforeach
           @if($hospedaje[0]->numerofactura!=null)
           <tr>
               <td colspan="2"><strong> Numero Factura:</strong></td>
               <td>{{$hospedaje[0]->numerofactura}}</td>
               <td><strong>Razon Social:</strong></td>
               <td colspan="2">{{$hospedaje[0]->razonsocial}}</td>
               
           </tr>
           @endif
           @if($hospedaje[0]->descuento==2)
           <tr>
               
               <td colspan="2"><strong>Monto Sin Descuento:</strong></td>
               <td>{{$hospedaje[0]->montosindescuento}} Bs.</td>
               
           </tr>
           @endif
           <tr>
                @if($hospedaje[0]->descuento==2)
                <td colspan="2"><strong>Monto Cancelado con Descuento:</strong></td>
                @else
                <td colspan="2"><strong>Monto Cancelado:</strong></td>
                @endif
                <td>{{$hospedaje[0]->monto}} Bs.</td>
                
                
                @if($hospedaje[0]->tipodescuento==1)
                <td><strong>Tipo descuento:</strong></td>
                <td colspan="2">Descuento via Min. Def.</td>
                @elseif ($hospedaje[0]->tipodescuento==2)
                <td><strong>Tipo descuento:</strong></td>
                <td colspan="2">Autorización por CEN</td>
                @endif
           </tr>
           <tr><td colspan="6" style="color: white;">.</td></tr>
           <tr><td colspan="6" style="color: white;">.</td></tr>
           <tr><td colspan="6" style="color: white;">.</td></tr>
           <tr><td colspan="6" style="color: white;">.</td></tr>
           <tr><td colspan="6" style="color: white;">.</td></tr>
           <tr><td colspan="6" style="color: white;">.</td></tr> 
           <tr><td colspan="6" style="color: white;">.</td></tr>
           <tr>
               <td colspan="3" style="text-align:center;" valign="bottom" ><strong>FIRMA CLIENTE</strong></td>
               <td colspan="3" style="text-align:center;" valign="bottom"><strong>FIRMA ENCARGADO(A)</strong></td>
           </tr>
           <tr>
               <td colspan="6" style="text-align: left;"><strong>Observaciones:</strong>{{$hospedaje[0]->obs2}}</td>
           </tr>
       </tbody>
       
       
   </table>
  

   
    
    </div> 
</body>
</html>