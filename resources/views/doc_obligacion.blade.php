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
        border: 1px solid #000;
        width: 100%;
        font-size: 15px;
        
       
        caption-side: top; 
    }
    td{
        border: 1px solid #000;
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
        text-align: left;
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
<body style="background-color: cadetblue; text-align:center">
<div class="body_wrapper">
    
   <h1>Documento de Obligacion Cargo de Cuenta</h1>
   <h3 style="text-align: right;"> N°  {{ $solccuenta[0]->numdocobligacion }}&#47;{{$solccuenta[0]->anio}}</h3>

   <p>
        Cuentadante Sr(a).: <span>{{ $solccuenta[0]->nombres }}</span>, Cargo: <span>{{$solccuenta[0]->nomcargo}}</span> 
        suscribe el presente documento de obligacion en favor de la Asociacion Nacional de Suboficiales y sargentos 
        por el importe de: <span>{{ number_format($solccuenta[0]->monto,2) }}</span>  Bs. para cubrir los gastos segun el 
        comprobante de contabilidad N°: <span>{{$solccuenta[0]->cod_comprobante}}</span> mediante Abono de cuenta <span>{{$solccuenta[0]->nomcuenta}}</span>
        correspondientes a la gestion: <span>{{$solccuenta[0]->anio}}</span>  de acuerdo a las partidas contables que a continuacion se detalla:
   </p>
   <table>
       <thead>
           <tr>
               <th>PARTIDA</th>
               <th style="width: 50%; ">DESCRIPCION</th>
               <th style="width: 30%; ">IMPORTE</th>
           </tr>
       </thead>
       <tbody>
           <tr style="height: 100px;">
                <td></td>
                <td style="text-align: left;">{{$solccuenta[0]->glosa}}</td>
                <td>{{ number_format($solccuenta[0]->monto,2) }} Bs.</td>
           </tr>
           <tr>
               <td colspan=2>Total Comprobante:</td>
               <td>{{ number_format($solccuenta[0]->monto,2) }} Bs.</td>
           </tr>
           <tr>
               <td colspan=2><p>Nombre: {{ $solccuenta[0]->nombres }} </p>
                            <p> C.I.: {{ $solccuenta[0]->ci }}</p>
                            <p>fecha: {{ date("d") . " del " . date("m") . " de " . date("Y") }} ;</p>
               </td>
                   
               <td valign="bottom">firma</td>
           </tr>
           <tr>
               <td colspan=2 valign="top" style="text-align: left;">Aprobado para su pago:</td>
               <td valign="top" style="text-align: left;"> Pagado por:</td>
           </tr>
           <tr style="height:150px;">
                <td valign="bottom" style="text-align:center;" colspan="2"> SECRETARIO DE HACIENDA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DIRECTOR ADMINISTRATIVO</td>
                <td valign="bottom" style="text-align:center;"> RESPONSABLE DE TESORERIA</td>

           </tr>
       </tbody>
       
       
   </table>
   <span class="span2"> A la firma del presente documento, acepto tener pleno conocimiento de las recomendaciones suscritas en el anverso del mismo, quedando asi notificado.</span>

   
    
    </div> 
</body>
</html>