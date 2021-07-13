<?php

namespace App\AsinalssClass; 
 
use App\Par_Producto;
use App\Par_Prestamos;
use App\Par_productos_perfilcuenta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
 
Class MetodoAmortizacion
{ 
    function TEM($tasa){
        return $tasa / 12;
    }
    function redondeo_valor($valor){
        return round($valor,2);
    }
    function getproductocobranza($producto){ 
        return Par_Producto::select('cobranza_perfil_ascii','tasa') 
         ->where('idproducto','=',$producto) 
         ->limit(1)
         ->get();
     }
     function getperfil($producto,$idperfil){ 
        return Par_productos_perfilcuenta::join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
         ->select('par__productos__perfilcuentas.idperfilcuentamaestro',
         'par__productos__perfilcuentas.idperfilcuentadetalle',
         'par__productos__perfilcuentas.valor_abc',
         'par__productos__perfilcuentas.valor_abc_php',
         'par__productos__perfilcuentas.formula',
         'par__productos__perfilcuentas.formulaphp',
         'par__productos__perfilcuentas.iscomision',
         'con__perfilcuentadetalles.tipocargo',
         'con__perfilcuentadetalles.idcuenta',
         'par__productos__perfilcuentas.iscargo')
         ->where('par__productos__perfilcuentas.activo','=','1')  
         ->where('par__productos__perfilcuentas.idproducto','=',$producto)
         ->where('par__productos__perfilcuentas.idperfilcuentamaestro','=',$idperfil)
         ->orderBy('par__productos__perfilcuentas.valor_abc', 'asc')
         ->get();
     }


    public function metodofrances($idproducto,$meses,$montosolicitado,$interesDiferido){
        $idperfilcobranzaascii=$this->getproductocobranza($idproducto)[0]->cobranza_perfil_ascii;
        $tasa=$this->getproductocobranza($idproducto)[0]->tasa;
        $fechas=DB::select('select checkcut() as corte, getfecha() as fecha, getfechas() as fechas')[0];
        $fechaspuntero=json_decode($fechas->fechas);
        $ted = $this->TEM($tasa)/100;
        $punterofechas = $fechas->corte==1? 1 : 0;
        $valorresta = (intval(date('d', strtotime($fechaspuntero[0])))-intval(date('d', strtotime($fechas->fecha))))+1; 
        // aplicacion del metodo frances
        $in2 = pow((1 + $ted), $meses);
        $in3 = $ted * $in2;
        $int4 = $in2 - 1;
        $cuotageneral = $this->redondeo_valor(($montosolicitado * ($in3 / $int4)));
        // aplicacion del metodo frances 
        $interes_periodo=0;
        $amortizacion=0;
        $cuota_final=0;
        $montototal = $montosolicitado;
        
        $outdata = array();
        
        $cuotaacu = $cuotageneral;
        $controldiferido=0;
        $cuotadiferido = $this->redondeo_valor($interesDiferido / $meses);
          
        for ($periodo = 0; $periodo <= $meses; $periodo++) {
          
            if ($periodo>0) { 
                    $date = intval(date('d', strtotime($fechaspuntero[$punterofechas])));
                    $diasc = (($periodo == 1 ? ($fechas->corte==1 ? $date + $valorresta : $valorresta) :$date));
                    $interes_periodo = ($periodo == 1) ?
                    (($fechas->corte==1) ?
                    ($this->redondeo_valor(($montototal * $ted * ($date + $valorresta)) / 30)) :
                    ($this->redondeo_valor(($montototal * $ted * ($valorresta)) / 30))) :
                    ($this->redondeo_valor(($montototal * $ted * $date) / 30)); // ver si lo dejamos por dia o lo ponemos por 30
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
                if($interes_periodo>$cuotaacu){
                    $auxinteres = $this->redondeo_valor($interes_periodo - $cuotaacu);
                    $cuotaacu = $this->redondeo_valor($cuotageneral + $auxinteres);
                }else{
                    $cuotaacu = $cuotageneral;
                }
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    if ($montototal >= $this->redondeo_valor($cuotaacu - $interes_periodo)){
                        if ($periodo == $meses) {
                            if ($montototal == $this->redondeo_valor($cuotaacu - $interes_periodo)) {
                              $amortizacion = $this->redondeo_valor($cuotaacu - $interes_periodo);
                            } else {
                              $aux = $this->redondeo_valor($cuotaacu - $interes_periodo);
                              $aux = $this->redondeo_valor($montototal - $aux);
                              $cuotaacu = $this->redondeo_valor($cuotaacu + $aux);
                              $amortizacion = $this->redondeo_valor($cuotaacu - $interes_periodo);
                            }
                          } else {
                            $amortizacion = $this->redondeo_valor($cuotaacu - $interes_periodo); 
                          }
                    }else{
                        $aux = $this->redondeo_valor($cuotaacu - $interes_periodo);
                        $aux = $this->redondeo_valor($aux-$montototal); 
                        $cuotaacu = $this->redondeo_valor($cuotaacu - $aux);
                        $amortizacion = $this->redondeo_valor($cuotaacu - $interes_periodo); 
                    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $amortizacion = ($amortizacion>=0)?$amortizacion:0;
                                        $monto_anterior = $montototal;
                                        $montototal = $this->redondeo_valor($montototal - $amortizacion);
                                        
                                        if ($periodo == $meses) { 
                                                if ($controldiferido === $interesDiferido) {
                                                    $controldiferido = $this->redondeo_valor($controldiferido+$cuotadiferido);
                                                }else{
                                                       if ($controldiferido > $interesDiferido) { 
                                                        $resta = $this->redondeo_valor($controldiferido-$interesDiferido);  
                                                        $cuotadiferido = $this->redondeo_valor($cuotadiferido - $resta);
                                                        } else { 
                                                        $suma = $this->redondeo_valor($interesDiferido-$controldiferido); ;
                                                        $cuotadiferido = $this->redondeo_valor($cuotadiferido + $suma);
                                                        }
                                                } 
                                        } else {
                                            $controldiferido = $this->redondeo_valor($controldiferido+$cuotadiferido);
                                        }
/////////////////////////////////////////////////////////////////////////////////////////////////calculo de cargos/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $perfilcobranza =$this->getperfil($idproducto,$idperfilcobranzaascii); 
                                        for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                                       
                                        $sumacargos=0;
                                        /////// parametros para el calculo de cargos
                                        $total_cuotas=$meses;
                                        $cuota=$cuotaacu;
                                        $cuotaf=$cuotaacu;
                                        $intereses=$interes_periodo;
                                        $capital=$amortizacion;

                                        /////////
                                        foreach($perfilcobranza as $perfil){ 
                                                $abc="$".$perfil->valor_abc; 
                                                eval($abc."=".$perfil->formulaphp.";");
                                                if($perfil['iscargo']==1){ 
                                                    $cargoin=eval("return round(".$abc.",2);");
                                                    $sumacargos+=$cargoin;
                                                    $sumacargos=round($sumacargos,2);  
                                                }
                                        } 
                                       // echo $cuotaacu."<br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
                                $outdata[$periodo]=array(
                                    'pe'=>$periodo,
                                    'fp'=> date('Y-m-d', strtotime($fechaspuntero[$punterofechas])),
                                    'di'=> $diasc,
                                    'cut'=> $this->redondeo_valor($cuotaacu + $sumacargos+ $cuotadiferido),
                                    'cuota'=> $cuotaacu,
                                    'in'=> $interes_periodo,
                                    'indi'=> $cuotadiferido,
                                    'am'=> $amortizacion,
                                    'ca'=> $montototal,
                                    'ca_an'=> $monto_anterior,
                                    'car'=> $sumacargos
                                );
                                $punterofechas++;
                    if ($cuota_final < ($cuotaacu + $sumacargos)) {
                        $cuota_final = $this->redondeo_valor($cuotaacu + $sumacargos);
                    } 
            }else{
                 
                $outdata[$periodo]=array(
                  'pe'=>$periodo,
                  'fp'=> date('Y-m-d', strtotime($fechas->fecha)),
                  'di'=> 0,
                  'cut'=> 0,
                  'cuota'=> 0,
                  'in'=> 0,
                  'indi'=> 0,
                  'am'=> 0,
                  'ca'=> $montototal,
                  'ca_an'=> $montototal,
                  'car'=> 0 
                );
            }
        } 
   
  return ['cuota'=> $cuota_final,
  'cuotafinal'=> $cuotageneral,
  'data'=> $outdata];
    }
     
    public function metododiario(){

        $tasa=9;
        $meses=36;
        $montosolicitado=5000;
        $interesDiferido=0;
        $idproducto=6;
        $idperfilcobranzaascii=$this->getproductocobranza($idproducto)[0]->cobranza_perfil_ascii;
        $fechas=DB::select('select checkcut() as corte, getfecha() as fecha, getfechas() as fechas')[0];
        $fechaspuntero=json_decode($fechas->fechas);

        $punterofechas = $fechas->corte==1? 1 : 0;
        $valorresta = (intval(date('d', strtotime($fechaspuntero[0])))-intval(date('d', strtotime($fechas->fecha))))+1;
///////////////////////////////////////////////////////////////
        $tna=(((pow((1+($tasa/100)),1/12)-1)*12)*365)/360;

///////////////////////////////////////////////////////////////




        $interes_periodo=0;
        $amortizacion=0;
        $cuota_final=0;
        $montototal = $montosolicitado;
        
        $outdata = array();
        
       
        $controldiferido=0;
        $cuotadiferido = $this->redondeo_valor($interesDiferido / $meses);
        
        
        

        $outdata[0]=array(
            'pe'=>0,
            'fp'=> date('Y-m-d', strtotime($fechas->fecha)),
            'di'=> 0,
            'cut'=> 0,
            'cuota'=> 0,
            'in'=> 0,
            'indi'=> 0,
            'am'=> 0,
            'ca'=> $montototal,
            'ca_an'=> $montototal,
            'car'=> 0 
          );

          

        $cuotageneral=0;
          
        for ($periodo = 1; $periodo <= $meses; $periodo++) {
          
            
                    $date = intval(date('d', strtotime($fechaspuntero[$punterofechas])));
                     
                      
                    
                    if($periodo == 1){
                        $diasc = $fechas->corte==1 ? $date + $valorresta : $valorresta;
                        
 
                        
                        if($diasc>31){
                            $i= (($tna/365)*$diasc);  
                            $interes=round($montototal*$i,2); 
                            $cuota=$this->redondeo_valor($montosolicitado*($i/(1-pow(1+$i,($meses*-1)))));
                            

                            $amortizacion = $this->redondeo_valor($cuota - $interes);
                            $montototal = $this->redondeo_valor($montototal - $amortizacion);

                            echo "periodo:".$periodo."dias:".$diasc. "interes:".$interes."cuota:".$cuota."amortizacion:".$amortizacion."montototal:".$montototal."<br>";
                        }else{
                         $i= (($tna/365)*31);  
                         $cuotageneral=$this->redondeo_valor($montototal*($i/(1-pow(1+$i,(($meses)*-1)))));
                        }

                    }else{ 
                        $i= (($tna/365)*$date);  
                        $interes=round($montototal*$i,2); 
                        $amortizacion = $this->redondeo_valor($cuotageneral - $interes);
                        $montototal = $this->redondeo_valor($montototal - $amortizacion);

                        echo "periodo:".$periodo."dias:".$date. "interes:".$interes."cuota:".$cuotageneral."amortizacion:".$amortizacion."montototal:".$montototal."<br>";
                    }

                

/////////////////////////////////////////////////////////////////////////////////////////////////calculo de cargos/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $sumacargos=0;
                                        $monto_anterior = $montototal;
                                        /*   $perfilcobranza =$this->getperfil($idproducto,$idperfilcobranzaascii); 
                                        for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                                        
                                        /////// parametros para el calculo de cargos
                                        $total_cuotas=$meses;
                                        $cuota=$cuotaacu;
                                        $cuotaf=$cuotaacu;
                                        $intereses=$interes_periodo;
                                        $capital=$amortizacion;

                                        /////////
                                        foreach($perfilcobranza as $perfil){ 
                                                $abc="$".$perfil->valor_abc; 
                                                eval($abc."=".$perfil->formulaphp.";");
                                                if($perfil['iscargo']==1){ 
                                                    $cargoin=eval("return round(".$abc.",2);");
                                                    $sumacargos+=$cargoin;  
                                                }
                                        }  */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
                                // $outdata[$periodo]=array(
                                //     'pe'=>$periodo,
                                //     'fp'=> date('Y-m-d', strtotime($fechaspuntero[$punterofechas])),
                                //     'di'=> $diasc,
                                //     'cut'=> $this->redondeo_valor($cuotaacu + $sumacargos+ $cuotadiferido),
                                //     'cuota'=> $cuotaacu,
                                //     'in'=> $interes_periodo,
                                //     'indi'=> $cuotadiferido,
                                //     'am'=> $amortizacion,
                                //     'ca'=> $montototal,
                                //     'ca_an'=> $monto_anterior,
                                //     'car'=> $sumacargos
                                // );
                                $punterofechas++;
                   
            
        } 


        return  $i;
    }
    public function getRefinanciamientoMetodoFraces($idsocio,$cuotatransito){
        $prestamos_refinanciar=Par_Prestamos::select(DB::raw('MONTH(par__prestamos.fechardesembolso) as mesdesembolso'),'par__prestamos.idprestamo','par__prestamos.apro_conta','par__monedas.tipocambio',
        'par__productos.cobranza_perfil_refi','par__productos.idproducto','par__prestamos.plazo','socios.numpapeleta')
        ->join('socios','par__prestamos.idsocio','=','socios.idsocio') 
        ->join('par__productos','par__prestamos.idproducto','=','par__productos.idproducto') 
        ->join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
        ->whereBetween('par__prestamos.idestado',[2,3]) 
        ->where('par__productos.cobranza_perfil_refi','!=','0') 
        ->where('par__prestamos.idsocio','=',$idsocio)->get(); 
        $outdata = array(); 
        $validadoconta=0;
        $sum_in_diferido=0;
        foreach($prestamos_refinanciar as $prestamo){
            if($prestamo->apro_conta==1){

               /////////////////////////////////////////////suma de interes diferido///////////////////////////////////////////////////////////
               $var= DB::table('par__prestamos__plans')->where('idprestamo',4); 
               if($cuotatransito==1){
                   $var=$var->where(function($query) {
                       $query->where('idestado',2)
                           ->orwhere('idestado',10);
                       });
               }else{
                   $var=$var->where('idestado',2);
               }
               $sum_in_diferido=$var->sum('indi');
               echo $prestamo->idprestamo;
               ////////////////////////////////////////////////////////////////////////////////////////////////////////

            }else{
                $outdata = array();
                $validadoconta=1;
                break;
            }
        }
        return ['conta'=>$validadoconta,'data'=>$prestamos_refinanciar];      
    }
}


