<?php

namespace App\Payment_plans; 
 
Class PaymentPlansClass
{
    var $fecharegistro; 
    

    public function __construct()
    {
         
    } 
    
    public function tem(){

    }
    
    public function ted(){

    }

    public function getFeeFrench(){

    }

    public function getFeeGerman(){

    }
/*  $aplica es TRUE para enviar el calculo de intereses de los dias restantes del mes actual al calculo de siguiente mes
    $aplica es FALSE para calcular el interes de los dias restantes del mes actual al periodo actual */
    public function getTem($tasa){
       return pow((1+($tasa/100)),(1/12))-1; 
    }
    public function getFeeByAccumulationOfInterest($tea=0,$meses=1,$montosolicitado=0,$fechaSistema='',$aplica=TRUE){
        $mesesNombre=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $tablaacumulacion=' <div class="col-md-12"> 
<table class="table table-bordered">
                <tr>
                  <th style="width: 10px">Periodo</th>
                  <th>Mes</th>
                  <th>Dias</th>
                  <th>Cuota</th> 
                  <th>Interes</th>
                  <th>Amortizacion</th>
                  <th>Capital</th>
                  <th>Capital Amortizado</th>
                </tr>';


        $fechaactual=date($fechaSistema);  

        $tem= $this->getTem($tea); 
        $ted=pow((1+$tem),(1/30))-1;  
        
        $interescompuesto=0;
        $diasacumulados=0;
        $capital_amortizado=0; 
        $valorresta=0;


        $valorresta=date("t", strtotime($fechaactual))-date("j", strtotime($fechaactual))+1;

        
        if($aplica){
			$diasacumulados+=$valorresta;
			 $fechaactual = date("Y-m-d",strtotime ( '+1 month' , strtotime ( $fechaactual ) )); 
		 }
      
         for ($periodo = 1; $periodo <= $meses; $periodo++) {  
            $diasacumulados+=(($periodo==1)?($aplica?date("t", strtotime($fechaactual)):$valorresta):date("t", strtotime($fechaactual)));
            $interescompuesto+=(1/( pow((1+$ted),$diasacumulados))); 
            $fechaactual = date("Y-m-d",strtotime ( '+1 month' , strtotime ( $fechaactual ) )); 
         }

        $fechaactual=date($fechaSistema); 
      
        $amortizacion=0;
        $interes_periodo=0;
        $cuotaacu=$montosolicitado/$interescompuesto; 
        $montototal=$montosolicitado;

    for ($periodo = 1; $periodo <= $meses; $periodo++) {  
        if($periodo==1){
            if($aplica){
                $fechaactual = date("Y-m-d",strtotime ( '+1 month' , strtotime ( $fechaactual ) )); 
                $interes_periodo=$montototal*((pow((1+$ted),date("t", strtotime($fechaactual))+$valorresta))-1); 
            }else{
              $interes_periodo=$montototal*((pow((1+$ted),$valorresta))-1);  
            }
         }else{
             $interes_periodo=$montototal*((pow((1+$ted),date("t", strtotime($fechaactual))))-1); 
         } 
       
        
         $amortizacion=$cuotaacu-$interes_periodo;  
         $montototal-=$amortizacion;
         $capital_amortizado+=$amortizacion;
         $tablaacumulacion.='<tr>
         <td align="center">'.round($periodo).'</td> 
         <td align="center">'.date("Y",strtotime($fechaactual)).' '.$mesesNombre[date("n",strtotime($fechaactual))-1].' </td>  
         <td align="center">'.(($periodo==1?( $aplica?date("t", strtotime($fechaactual))+$valorresta:$valorresta):date("t", strtotime($fechaactual)))).'</td>   
         <td align="center">'.round($cuotaacu,2).'</td>  
         <td align="center">'.round($interes_periodo,2).'</td>  
         <td align="center">'.round($amortizacion,2).'</td>  
         <td align="center">'.round($montototal,2).'</td>  
         <td align="center">'.round($capital_amortizado,2).'</td>  
          </tr>';
          $fechaactual = date("Y-m-d",strtotime ( '+1 month' , strtotime ( $fechaactual ) )); 
          
     }
     $tablaacumulacion.='
     </table>
     </div>  
          
       '; 


        return ['cuota'=>round($cuotaacu,2),'plan'=>$tablaacumulacion];
        

    }
}

?>