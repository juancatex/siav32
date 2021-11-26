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
<body style="background-color: gray; text-align:center">
<div class="body_wrapper">
<div class="left"> <img src="{{$foto}}" width="90%"> </div>
   <h2 style="color: #0c49a5;">REGISTRO DE INGRESO HOSPEDAJE N°  {{ $hospedaje[0]->idasignacion }}</h2>
   <hr>
   <table>
       <tbody>
           <tr style="height: 50px;">
               <td colspan="4" ><strong >DATOS DE INGRESO:</strong>
                   </td>
           </tr>
           <tr >
                <td style="text-align: left;"><strong>Nombre: </strong></td>
                <td style="text-align: left;">{{$hospedaje[0]->nombres}}</td>
                @if($hospedaje[0]->numpapeleta!='')
                <td style="text-align: left;"><strong> Numero de Boleta:</strong></td>
                <td style="text-align: left;">{{ $hospedaje[0]->numpapeleta }}</td>
                @endif
                

           </tr>
           <tr >
                <td style="text-align: left;"><strong>CI:</strong></td>
                <td style="text-align: left;">{{$hospedaje[0]->ci}}</td>
                <td></td>
                <td></td>
           </tr>
           <tr style="height: 50px;">
               <td colspan="4" ><strong> DATOS DE ASIGNACION: </strong></td>
            </tr>
               <td style="text-align: left;"><strong>Habitacion:</strong></td>
                <td style="text-align: left;">{{$hospedaje[0]->codambiente}}</td>
                <td style="text-align: left;"><strong>Fecha de Ingreso:</strong></td>
                <td style="text-align: left;">{{$hospedaje[0]->fechaentrada}} - {{$hospedaje[0]->horaentrada}}</td>
           </tr>
           <tr>
               <td colspan="4" style="text-align: left; height: 50px;" ><strong>Implementos Entregados:</strong></td>
           </tr>
           @foreach ($implementos as $impl)
           <tr>
               <td style="width: 100px;"></td>
               <td colspan="3" style="text-align: left;">{{ $impl->nomimplemento }}</td>
           </tr>
           @endforeach
           <tr>
               <td colspan="4" style="text-align: left;"><strong>Observaciones:</strong>{{$hospedaje[0]->obs1}}</td>
           </tr>
           <tr style="height: 100px;">
               <td colspan="2" style="text-align:center;" valign="bottom" >FIRMA CLIENTE</td>
               <td colspan="2" style="text-align:center;" valign="bottom">FIRMA ENCARGADO(A)</td>
           </tr>
       </tbody>
       
       
   </table>
   <h3 style="text-align: left;">NOTA:</h3>
   <span class="span">Estimado Cliente ASCINALSS Comunica a usted lo siguiente:
<ul style="text-align: left;">
    <li>
        La hora maxima establecida para la entrega de las piezas es hasta Hrs. 12:30
pasada esta hora se ejecutara automaticamente un dia mas de Tarifa
    </li>
    <li>
    Toda pertenencia de valor que no sea registrada en la administracion del Hotel, en
caso de perdida no sera responsabilidad de ASCINALSS
    </li>
    <li>
    La llave de la pieza debe dejarse en la administracion de ASCINALSS
    </li>
</ul> 

   
    
    </div> 
</body>
</html>