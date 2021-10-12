<?php

namespace App\AsinalssClass; 
 
use App\Par_Producto;
use App\Par_Prestamos;
use App\Par_productos_perfilcuenta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
 
Class MetodoAmortizacion
{ 
    function seguro_desgravamen($monto){
        $seguro=0.78; 
        return $this->redondeo_valor(($monto*($seguro))/1000);
    }
    function TEM($tasa){
        return $tasa / 12;
    }
    function redondeo_valor($valor){
        return round($valor,2);
    }
    function getproductocobranza($producto){ 
        return Par_Producto::select('cobranza_perfil_ascii','tasa','desembolso_perfil','desembolso_perfil_refi') 
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


    public function metodofrances($idproducto,$meses,$montosolicitado,$interesDiferido,$segurovalidate){
        $idperfilcobranzaascii=$this->getproductocobranza($idproducto)[0]->cobranza_perfil_ascii;
        $tasa=$this->getproductocobranza($idproducto)[0]->tasa;
        $fechas=DB::select('select checkcut() as corte, getfecha() as fecha, getfechas() as fechas')[0];
        $fechaspuntero=json_decode($fechas->fechas);
        $ted = $this->TEM($tasa)/100;
        $punterofechas = $fechas->corte==1? 1 : 0;
        $valorresta = (intval(date('d', strtotime($fechaspuntero[0])))-intval(date('d', strtotime($fechas->fecha))))+1; 
        // aplicacion del metodo frances  
        $cuotageneral = $this->redondeo_valor(   ($ted * pow((1 + $ted), $meses) / (pow((1 + $ted), $meses) - 1)) * $montosolicitado );
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
                                        if($segurovalidate==1){
                                            $seguro=$this->seguro_desgravamen($monto_anterior);
                                        }else{
                                            $seguro=0;
                                        }
/////////////////////////////////////////////////////////////////////////////////////////////////calculo de cargos/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $perfilcobranza =$this->getperfil($idproducto,$idperfilcobranzaascii); 
                                        for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                                       
                                        $sumacargos=0;
                                        /////// parametros para el calculo de cargos
                                        $total_cuotas=$meses;
                                        $cuota=$this->redondeo_valor($cuotaacu+$seguro);
                                        $cuotaf=$this->redondeo_valor($cuotaacu+$seguro);
                                        $intereses=$interes_periodo;
                                        $capital=$amortizacion;

                                        /////////
                                        foreach($perfilcobranza as $perfil){ 
                                                $abc="$".$perfil->valor_abc; 
                                                eval($abc."=".$perfil->formulaphp.";");
                                                if($perfil['iscargo']==1){ 
                                                    $cargoin=$this->redondeo_valor(eval("return ".$abc.";")); 
                                                    $sumacargos=$this->redondeo_valor($sumacargos + $cargoin);  
                                                }
                                        } 
                                       // echo $cuotaacu."<br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                  
                                    
                                $outdata[$periodo]=array(
                                    'pe'=>$periodo,
                                    'fp'=> date('Y-m-d', strtotime($fechaspuntero[$punterofechas])),
                                    'di'=> $diasc,
                                    'seg'=> $seguro,
                                    'cut'=> $this->redondeo_valor($cuotaacu + $sumacargos+ $cuotadiferido + $seguro),
                                    'cuota'=> $cuotaacu,
                                    'in'=> $interes_periodo,
                                    'indi'=> $cuotadiferido,
                                    'am'=> $amortizacion,
                                    'ca'=> $montototal,
                                    'ca_an'=> $monto_anterior,
                                    'car'=> $sumacargos
                                );
                                $punterofechas++;
                    if ($cuota_final < ($cuotaacu + $sumacargos+ $cuotadiferido + $seguro)) {
                        $cuota_final = $this->redondeo_valor($cuotaacu + $sumacargos+ $cuotadiferido + $seguro);
                    } 
            }else{
                $perildesembolso=$this->getproductocobranza($idproducto)[0];
                

                                         $perfilcobranza =$this->getperfil($idproducto,$perildesembolso->desembolso_perfil>0?$perildesembolso->desembolso_perfil:$perildesembolso->desembolso_perfil_refi); 
                                         
                                        for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                                       
                                        $sumacargos=0;
                                        /////// parametros para el calculo de cargos 
                                        /////////
                                        foreach($perfilcobranza as $perfil){  
                                                if($perfil['iscargo']==1){ 
                                                    $abc="$".$perfil->valor_abc; 
                                                    eval($abc."=".$perfil->formulaphp.";");
                                                    $cargoin=$this->redondeo_valor(eval("return ".$abc.";")); 
                                                    $sumacargos=$this->redondeo_valor($sumacargos + $cargoin);  
                                                }
                                        } 


                $outdata[$periodo]=array(
                  'pe'=>$periodo,
                  'fp'=> date('Y-m-d', strtotime($fechas->fecha)),
                  'di'=> 0,
                  'seg'=> 0,
                  'cut'=> 0,
                  'cuota'=> 0,
                  'in'=> 0,
                  'indi'=> 0,
                  'am'=> 0,
                  'ca'=> $montototal,
                  'ca_an'=> $montototal,
                  'car'=> $sumacargos
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
    public function getRefinanciamientoMetodoFraces($idsocio,$cuotatransito,$numdoc,$idmodulo,$idprestamoin){
        $prestamos_refinanciar=Par_Prestamos::select(DB::raw('DAY(par__prestamos.fechardesembolso) as diadesembolso'),DB::raw('MONTH(par__prestamos.fechardesembolso) as mesdesembolso'),
        'par__prestamos.idprestamo','par__prestamos.apro_conta','par__monedas.tipocambio','par__productos.tasa',
        'par__productos.cobranza_perfil_refi','par__productos.idproducto','par__prestamos.plazo','par__prestamos.seguro','socios.numpapeleta')
        ->join('socios','par__prestamos.idsocio','=','socios.idsocio') 
        ->join('par__productos','par__prestamos.idproducto','=','par__productos.idproducto') 
        ->join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
        ->whereBetween('par__prestamos.idestado',[2,3]) 
        ->where('par__productos.cobranza_perfil_refi','!=','0') 
        ->where('par__prestamos.idsocio','=',$idsocio)->get(); 
        $outdata = array();
        $cargoscobranza = array(); 
        $validadoconta=0; 
        $total_refinanciar=0;
        $suma_cuota=0; 
        $fecha=(DB::select("select fechaSistema as fecha  from par__fecha__sistemas where activo=1"))[0]->fecha; 
        foreach($prestamos_refinanciar as $prestamo){ 
            if($prestamo->apro_conta==1){
                                
                                /////////////////////////////////////////////TEM////////////////////////////////////////////////////////////////////////////////
                                $tem=0;
                                $prestamo->tasa;
                                if($prestamo->tasa>0){ 
                                    $tem = $this->TEM($prestamo->tasa)/100;
                                }
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////suma de interes diferido///////////////////////////////////////////////////////////
                                            $varinteres= DB::table('par__prestamos__plans')->where('idprestamo',$prestamo->idprestamo); 
                                            if($cuotatransito==1){
                                                $varinteres=$varinteres->where(function($query) {
                                                    $query->where('idestado',2)
                                                        ->orwhere('idestado',10);
                                                    });
                                            }else{
                                                $varinteres=$varinteres->where('idestado',2);
                                            }

                                 $sum_in_diferido=$varinteres->sum('indi');
                                    
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////ultima cuota vigente   ///////////////////////////////////////////////////////////
                                $datosprestamo= DB::table('par__prestamos__plans')
                                ->select('ca_an','pe','fp','di',DB::raw('DAY(fp) as dias_fecha_plan'),DB::raw('MONTH(fp) as mes_fecha_plan'))->where('idprestamo',$prestamo->idprestamo); 
                                if($cuotatransito==1){
                                    $datosprestamo=$datosprestamo->where(function($query) {
                                        $query->where('idestado',2)
                                            ->orwhere('idestado',10);
                                        });
                                }else{
                                    $datosprestamo=$datosprestamo->where('idestado',2);
                                }

                                $datosprestamo=$datosprestamo->orderby('pe','asc')->limit(1)->get()[0]; 
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////////////////calculo metodo frances/////////////////////////////////////////////////
                                    if($tem>0){
                                        $datesistema=(DB::select("select getfecha() as fecha,month(getfecha()) as mesactual,day(getfecha()) as diaactual"))[0];
                                        if($datesistema->mesactual==$prestamo->mesdesembolso){
                                            $dias=($datesistema->diaactual-$prestamo->diadesembolso)+1;

                                        }elseif( $datosprestamo->mes_fecha_plan<$datesistema->mesactual){
                                            $dias=($datesistema->diaactual+$datosprestamo->di);

                                        }elseif( $datosprestamo->mes_fecha_plan==$datesistema->mesactual){
                                            $dias=($datesistema->diaactual+($datosprestamo->di-$datosprestamo->dias_fecha_plan))+1; 
                                        }
                                       $interes_periodo = $this->redondeo_valor(($datosprestamo->ca_an * $tem * $dias) / 30);
                                       $cuotafinal = $this->redondeo_valor($datosprestamo->ca_an + $interes_periodo + $sum_in_diferido);
                                    }else{
                                        $cuotafinal = $datosprestamo->ca_an;
                                    }
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
                                ////////////////////////////////////----- obtiene el perfil de cobranza segun el producto del presamo------///////////////////////////////////////////////////
                                $perfilcobranza =$this->getperfil($prestamo->idproducto,$prestamo->cobranza_perfil_refi); 
                                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                
                                 if($prestamo->seguro==1){
                                    $segurodesgravamen= $this->seguro_desgravamen($datosprestamo->ca_an);
                                 }else{
                                    $segurodesgravamen=0;
                                 } 
                                 
                                $total_cuotas = $prestamo->plazo;
                                $cuota =$this->redondeo_valor($cuotafinal + $segurodesgravamen);/// verificar si en caso se sigue pagando cargos en este proceso
                                $cuotaf = $this->redondeo_valor($cuotafinal + $segurodesgravamen); /// verificar si en caso se sigue pagando cargos en este proceso
                                $capital = $datosprestamo->ca_an;
                                $intereses = $interes_periodo;
                                $interes_diferido =$sum_in_diferido;
                                $cargoscobranza = array();
                                $sumacargos=0; 
                                                                                                 ////////////////////////////////////obtencion de cargos/////////////////////////////////////////////////////
                                                                                                    ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                                                                                                            for($i=65; $i<=90; $i++) {  
                                                                                                                $abc="$".chr($i); 
                                                                                                                eval($abc."=0;");
                                                                                                            }
                                                                                                    ////////////////////////////////////////////////////////////////////////////////////
                                                                                                    foreach($perfilcobranza as $perfil){ 
                                                                                                        $abc="$".$perfil['valor_abc'];
                                                                                                        eval($abc."=".$perfil['formulaphp'].";");
                                                                                                        if($perfil['iscargo']==1){ 
                                                                                                                $cargoin=eval("return round(".$abc.",2);"); 
                                                                                                                $sumacargos=$this->redondeo_valor($sumacargos+$cargoin); 
                                                                                                                array_push($cargoscobranza,['idperfilcuentadetalle'=>$perfil['idperfilcuentadetalle'],'cargo'=>$cargoin,'rev'=>(strpos($perfil['formulaphp'],'$total_cuotas')>0)?1:0]);
                                                                                                            }
                                                                                                        }
                                                                                                    ///////////////////////////////////////////////////////////////////////////////////////// 
     
                               $cuotaf =  $this->redondeo_valor($cuota+$sumacargos); 
                                $arrayDetalle = array();  
                                $debe=0;
                                $haber=0;
                                ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                                for($i=65; $i<=90; $i++) {  
                                    $abc="$".chr($i); 
                                    eval($abc."=0;");
                                }
                                ////////////////////////////////////////////////////////////////////////////////////
                                foreach($perfilcobranza as $perfil){ 
                                    $abc="$".$perfil['valor_abc'];
                                    eval($abc."=".$perfil['formulaphp'].";");
                                    $value=$this->redondeo_valor(eval("return ".$abc.";"));
                                    $value=$this->redondeo_valor($value*$prestamo->tipocambio);  
                                    if($value>0){ 
                                        if($perfil['tipocargo']=='h'){ 
                                            $haber=$this->redondeo_valor($haber+$value);
                                            $value*= -1;
                                        }else{ 
                                            $debe=$this->redondeo_valor($debe+$value); 
                                        }
                                            ///////////////////////// registrar el perfil con su valor en un array
                                            array_push($arrayDetalle,array('idcuenta'=>$perfil['idcuenta'],
                                            'subcuenta'=>$prestamo->numpapeleta, 
                                            'documento'=>'Automatico', 
                                            'moneda'=>'bs',	 
                                            'monto'=>$value
                                            )); 
                                    }else{
                                        $value*= -1;
                                        if($value>0){ 
                                            if($perfil['tipocargo']=='h'){
                                                $haber=$this->redondeo_valor($haber+$value);
                                                $value*= -1;
                                            }else{
                                                $debe=$this->redondeo_valor($debe+$value); 
                                            }
                                                ///////////////////////// registrar el perfil con su valor en un array
                                            array_push($arrayDetalle,array('idcuenta'=>$perfil['idcuenta'],
                                            'subcuenta'=>$prestamo->numpapeleta, 
                                            'documento'=>'Automatico', 
                                            'moneda'=>'bs',	 
                                            'monto'=>$value
                                            )); 
                                        }

                                    }
                                }
                               ////////////////////////////////////////////////////////////////////////////////////  
                             
                   if($debe!=$haber){ 
                    $outdata = array();
                    $validadoconta=2;
                   }  
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $cuotafinal=$this->redondeo_valor($cuotaf*$prestamo->tipocambio);  
                    $total_refinanciar=$this->redondeo_valor($total_refinanciar+$cuotafinal); 
                   
                    array_push($outdata,array('cuotafinal'=>$cuotafinal,'idprestamo'=> $prestamo->idprestamo,
                    'asiento'=> array(
                        'cobranza_perfil_refi'=>$prestamo->cobranza_perfil_refi,
                        'numdoc'=>$numdoc,
                        'arraydetalle'=>$arrayDetalle,
                        'idmodulo'=>$idmodulo,
                        'fechaasiento'=>$fecha,
                        'idprestamoin'=>$idprestamoin

                    ),'db'=>array(
                        'idprestamo'=> $prestamo->idprestamo,
                        'pe'=> 500,
                        'fp'=> $fecha,
                        'di'=> $dias,
                        'ca_an'=> $datosprestamo->ca_an,
                        'am'=> $datosprestamo->ca_an,
                        'in'=> $interes_periodo,
                        'indi'=> $sum_in_diferido,
                        'ca'=> 0,
                        'seg'=> $segurodesgravamen,
                        'car'=> $sumacargos,
                        'cut'=> $cuotaf,
                        'cuota'=> $cuota,
                        'fecharegistro'=> $fecha,
                        'fechaCobranza'=> $fecha,
                        'tipo'=> 3,
                        'idestado'=> 13,
                        'idtransaccionC'=> "R-".$idprestamoin."-".$prestamo->idprestamo,
                        'numDocC'=> $numdoc,
                        'glosa'=> 'Cobranza del prestamo - Refinanciamiento - automatico',
                        'tipocambio'=> $prestamo->tipocambio,
                        'importe'=> $cuotafinal,
                        'idusuario'=> Auth::id()
                     )));
                   
                    
                                         
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            }else{
                $outdata = array();
                $validadoconta=1;
                break;
            }
        }
        return ['conta'=>$validadoconta,'data'=>$outdata,'total'=>$total_refinanciar,'cargosdata'=>$cargoscobranza];      
    }

    public function cobranza_manual($idprestamo,$cuotatransito,$numdoc,$idmodulo,$idprestamoin){
        $prestamo=Par_Prestamos::select(DB::raw('DAY(par__prestamos.fechardesembolso) as diadesembolso'),DB::raw('MONTH(par__prestamos.fechardesembolso) as mesdesembolso'),
        'par__prestamos.idprestamo','par__prestamos.apro_conta','par__monedas.tipocambio','par__productos.tasa',
        'par__productos.cobranza_perfil','par__productos.idproducto','par__prestamos.plazo','par__prestamos.seguro','socios.numpapeleta')
        ->join('socios','par__prestamos.idsocio','=','socios.idsocio') 
        ->join('par__productos','par__prestamos.idproducto','=','par__productos.idproducto') 
        ->join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
        ->whereBetween('par__prestamos.idestado',[2,3])              
        ->where('par__prestamos.idprestamo','=',$idprestamo)->get()[0];  
        $outdata = array(); 
        $arrayDetalle = array(); 
        $validadoconta=0;  
        $suma_cuota=0; 
        $fecha=(DB::select("select fechaSistema as fecha  from par__fecha__sistemas where activo=1"))[0]->fecha; 
        if($prestamo->cobranza_perfil!=0){ 
            if($prestamo->apro_conta==1){
                                
                                /////////////////////////////////////////////TEM////////////////////////////////////////////////////////////////////////////////
                                $tem=0;
                                $prestamo->tasa;
                                if($prestamo->tasa>0){ 
                                    $tem = $this->TEM($prestamo->tasa)/100;
                                }
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////suma de interes diferido///////////////////////////////////////////////////////////
                                            $varinteres= DB::table('par__prestamos__plans')->where('idprestamo',$prestamo->idprestamo); 
                                            if($cuotatransito==1){
                                                $varinteres=$varinteres->where(function($query) {
                                                    $query->where('idestado',2)
                                                        ->orwhere('idestado',10);
                                                    });
                                            }else{
                                                $varinteres=$varinteres->where('idestado',2);
                                            }

                                 $sum_in_diferido=$varinteres->sum('indi');
                                    
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////ultima cuota vigente   ///////////////////////////////////////////////////////////
                                $datosprestamo= DB::table('par__prestamos__plans')
                                ->select('ca_an','pe','fp','di',DB::raw('DAY(fp) as dias_fecha_plan'),DB::raw('MONTH(fp) as mes_fecha_plan'))->where('idprestamo',$prestamo->idprestamo); 
                                if($cuotatransito==1){
                                    $datosprestamo=$datosprestamo->where(function($query) {
                                        $query->where('idestado',2)
                                            ->orwhere('idestado',10);
                                        });
                                }else{
                                    $datosprestamo=$datosprestamo->where('idestado',2);
                                }

                                $datosprestamo=$datosprestamo->orderby('pe','asc')->limit(1)->get()[0]; 
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////////////////calculo metodo frances/////////////////////////////////////////////////
                                    if($tem>0){
                                        $datesistema=(DB::select("select getfecha() as fecha,month(getfecha()) as mesactual,day(getfecha()) as diaactual"))[0];
                                        if($datesistema->mesactual==$prestamo->mesdesembolso){
                                            $dias=($datesistema->diaactual-$prestamo->diadesembolso)+1;

                                        }elseif( $datosprestamo->mes_fecha_plan<$datesistema->mesactual){
                                            $dias=($datesistema->diaactual+$datosprestamo->di);

                                        }elseif( $datosprestamo->mes_fecha_plan==$datesistema->mesactual){
                                            $dias=($datesistema->diaactual+($datosprestamo->di-$datosprestamo->dias_fecha_plan))+1; 
                                        }
                                       $interes_periodo = $this->redondeo_valor(($datosprestamo->ca_an * $tem * $dias) / 30);
                                       $cuotafinal = $this->redondeo_valor($datosprestamo->ca_an + $interes_periodo + $sum_in_diferido);
                                    }else{
                                        $cuotafinal = $datosprestamo->ca_an;
                                    }
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
                                ////////////////////////////////////----- obtiene el perfil de cobranza segun el producto del presamo------///////////////////////////////////////////////////
                                $perfilcobranza =$this->getperfil($prestamo->idproducto,$prestamo->cobranza_perfil); 
                                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                
                                 if($prestamo->seguro==1){
                                    $segurodesgravamen= $this->seguro_desgravamen($datosprestamo->ca_an);
                                 }else{
                                    $segurodesgravamen=0;
                                 } 
                                 
                                $total_cuotas = $prestamo->plazo;
                                $cuota =$this->redondeo_valor($cuotafinal + $segurodesgravamen);/// verificar si en caso se sigue pagando cargos en este proceso
                                $cuotaf = $cuota; /// verificar si en caso se sigue pagando cargos en este proceso
                                $capital = $datosprestamo->ca_an;
                                $intereses = $interes_periodo;
                                $interes_diferido =$sum_in_diferido;
                                $cargoscobranza = array();
                                $sumacargos=0; 
                                                   ////////////////////////////////////obtencion de cargos/////////////////////////////////////////////////////
                                                    ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                                                            for($i=65; $i<=90; $i++) {  
                                                                $abc="$".chr($i); 
                                                                eval($abc."=0;");
                                                            }
                                                    ////////////////////////////////////////////////////////////////////////////////////
                                                    foreach($perfilcobranza as $perfil){ 
                                                        $abc="$".$perfil['valor_abc'];
                                                        eval($abc."=".$perfil['formulaphp'].";");
                                                        if($perfil['iscargo']==1){ 
                                                                $cargoin=eval("return round(".$abc.",2);"); 
                                                                $sumacargos=$this->redondeo_valor($sumacargos+$cargoin); 
                                                                array_push($cargoscobranza,['idperfilcuentadetalle'=>$perfil['idperfilcuentadetalle'],'cargo'=>$cargoin,'rev'=>(strpos($perfil['formulaphp'],'$total_cuotas')>0)?1:0]);
                                                            }
                                                        }
                                                    ///////////////////////////////////////////////////////////////////////////////////////// 
     
                                $cuotaf =  $this->redondeo_valor($cuota+$sumacargos); 
                                $arrayDetalle = array();  
                                $debe=0;
                                $haber=0;
                                ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                                for($i=65; $i<=90; $i++) {  
                                    $abc="$".chr($i); 
                                    eval($abc."=0;");
                                }
                                ////////////////////////////////////////////////////////////////////////////////////
                                foreach($perfilcobranza as $perfil){ 
                                    $abc="$".$perfil['valor_abc'];
                                    eval($abc."=".$perfil['formulaphp'].";");
                                    $value=$this->redondeo_valor(eval("return ".$abc.";"));
                                    $value=$this->redondeo_valor($value*$prestamo->tipocambio); 
                               
                                    if($value>0){ 
                                        if($perfil['tipocargo']=='h'){ 
                                            $haber=$this->redondeo_valor($haber+$value);
                                            $value*= -1;
                                        }else{ 
                                            $debe=$this->redondeo_valor($debe+$value); 
                                        }
                                            ///////////////////////// registrar el perfil con su valor en un array
                                            array_push($arrayDetalle,array('idcuenta'=>$perfil['idcuenta'],
                                            'subcuenta'=>$prestamo->numpapeleta, 
                                            'documento'=>'Automatico', 
                                            'moneda'=>'bs',	 
                                            'monto'=>$value
                                            )); 
                                    }else{
                                        $value*= -1;
                                        if($value>0){ 
                                            if($perfil['tipocargo']=='h'){
                                                $haber=$this->redondeo_valor($haber+$value);
                                                $value*= -1;
                                            }else{
                                                $debe=$this->redondeo_valor($debe+$value); 
                                            }
                                                ///////////////////////// registrar el perfil con su valor en un array
                                            array_push($arrayDetalle,array('idcuenta'=>$perfil['idcuenta'],
                                            'subcuenta'=>$prestamo->numpapeleta, 
                                            'documento'=>'Automatico', 
                                            'moneda'=>'bs',	 
                                            'monto'=>$value
                                            )); 
                                        }

                                    }
                                }
                               ////////////////////////////////////////////////////////////////////////////////////  
                             
                   if($debe!=$haber){  
                    $outdata = array(); 
                    $validadoconta=2;
                   }  
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $cuotafinal=$this->redondeo_valor($cuotaf*$prestamo->tipocambio);   
                   
                   $outdata = array('cuotafinal'=>$cuotafinal,'cuota'=>$cuotaf,'idprestamo'=> $prestamo->idprestamo,
                    'asiento'=> array(
                        'cobranza_perfil'=>$prestamo->cobranza_perfil,
                        'numdoc'=>$numdoc,
                        'arraydetalle'=>$arrayDetalle,
                        'idmodulo'=>$idmodulo,
                        'fechaasiento'=>$fecha,
                        'idprestamoin'=>$idprestamoin

                    ),'db'=>array(
                        'idprestamo'=> $prestamo->idprestamo,
                        'pe'=> 500,
                        'fp'=> $fecha,
                        'di'=> $dias,
                        'ca_an'=> $datosprestamo->ca_an,
                        'am'=> $datosprestamo->ca_an,
                        'in'=> $interes_periodo,
                        'indi'=> $sum_in_diferido,
                        'ca'=> 0,
                        'seg'=> $segurodesgravamen,
                        'car'=> $sumacargos,
                        'cut'=> $cuotaf,
                        'cuota'=> $cuota,
                        'fecharegistro'=> $fecha,
                        'fechaCobranza'=> $fecha,
                        'tipo'=> 0,
                        'idestado'=> 11,
                        'idtransaccionC'=> "C-".$prestamo->idprestamo,
                        'numDocC'=> $numdoc,
                        // 'glosa'=> 'Cobranza del prestamo - automatico',
                        'tipocambio'=> $prestamo->tipocambio,
                        'importe'=> $cuotafinal,
                        'idusuario'=> Auth::id()
                     ));
                   
                    
                                         
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            }else{
                $outdata = array();
                $validadoconta=1; 
            }
        }else{
            $outdata = array();
            $validadoconta=3; 
        }
        return ['conta'=>$validadoconta,'data'=>$outdata,'cargosdata'=>$cargoscobranza];      
    }
    public function cobranza_refinanciamiento($idprestamo,$cuotatransito,$numdoc,$idmodulo,$idprestamoin){
        $prestamo=Par_Prestamos::select(DB::raw('DAY(par__prestamos.fechardesembolso) as diadesembolso'),DB::raw('MONTH(par__prestamos.fechardesembolso) as mesdesembolso'),
        'par__prestamos.idprestamo','par__prestamos.apro_conta','par__monedas.tipocambio','par__productos.tasa',
        'par__productos.cobranza_perfil','par__productos.idproducto','par__prestamos.plazo','par__prestamos.seguro','socios.numpapeleta')
        ->join('socios','par__prestamos.idsocio','=','socios.idsocio') 
        ->join('par__productos','par__prestamos.idproducto','=','par__productos.idproducto') 
        ->join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
        ->whereBetween('par__prestamos.idestado',[2,3])              
        ->where('par__prestamos.idprestamo','=',$idprestamo)->get()[0];  
        $outdata = array(); 
        $arrayDetalle = array(); 
        $validadoconta=0;  
        $suma_cuota=0; 
        // $fecha=(DB::select("select fechaSistema as fecha  from par__fecha__sistemas where activo=1"))[0]->fecha; 
        $fecha=DB::table('par__fecha__sistemas')->select('fechaSistema')->where('activo',1)->first()->fechaSistema;
        if($prestamo->cobranza_perfil!=0){ 
            if($prestamo->apro_conta==1){
                                
                                /////////////////////////////////////////////TEM////////////////////////////////////////////////////////////////////////////////
                                $tem=0;
                                $prestamo->tasa;
                                if($prestamo->tasa>0){ 
                                    $tem = $this->TEM($prestamo->tasa)/100;
                                }
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////suma de interes diferido///////////////////////////////////////////////////////////
                                            $varinteres= DB::table('par__prestamos__plans')->where('idprestamo',$prestamo->idprestamo); 
                                            if($cuotatransito==1){
                                                $varinteres=$varinteres->where(function($query) {
                                                    $query->where('idestado',2)
                                                        ->orwhere('idestado',10);
                                                    });
                                            }else{
                                                $varinteres=$varinteres->where('idestado',2);
                                            }

                                 $sum_in_diferido=$varinteres->sum('indi');
                                    
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////ultima cuota vigente   ///////////////////////////////////////////////////////////
                                $datosprestamo= DB::table('par__prestamos__plans')
                                ->select('ca_an','pe','fp','di',DB::raw('DAY(fp) as dias_fecha_plan'),DB::raw('MONTH(fp) as mes_fecha_plan'))->where('idprestamo',$prestamo->idprestamo); 
                                if($cuotatransito==1){
                                    $datosprestamo=$datosprestamo->where(function($query) {
                                        $query->where('idestado',2)
                                            ->orwhere('idestado',10);
                                        });
                                }else{
                                    $datosprestamo=$datosprestamo->where('idestado',2);
                                }

                                $datosprestamo=$datosprestamo->orderby('pe','asc')->limit(1)->get()[0]; 
                                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                /////////////////////////////////////////////////////////calculo metodo frances/////////////////////////////////////////////////
                                    if($tem>0){
                                        $datesistema=(DB::select("select getfecha() as fecha,month(getfecha()) as mesactual,day(getfecha()) as diaactual"))[0];
                                        if($datesistema->mesactual==$prestamo->mesdesembolso){
                                            $dias=($datesistema->diaactual-$prestamo->diadesembolso)+1;

                                        }elseif( $datosprestamo->mes_fecha_plan<$datesistema->mesactual){
                                            $dias=($datesistema->diaactual+$datosprestamo->di);

                                        }elseif( $datosprestamo->mes_fecha_plan==$datesistema->mesactual){
                                            $dias=($datesistema->diaactual+($datosprestamo->di-$datosprestamo->dias_fecha_plan))+1; 
                                        }
                                       $interes_periodo = $this->redondeo_valor(($datosprestamo->ca_an * $tem * $dias) / 30);
                                       $cuotafinal = $this->redondeo_valor($datosprestamo->ca_an + $interes_periodo + $sum_in_diferido);
                                    }else{
                                        $cuotafinal = $datosprestamo->ca_an;
                                    }
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
                                ////////////////////////////////////----- obtiene el perfil de cobranza segun el producto del presamo------///////////////////////////////////////////////////
                                $perfilcobranza =$this->getperfil($prestamo->idproducto,$prestamo->cobranza_perfil); 
                                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                
                                 if($prestamo->seguro==1){
                                    $segurodesgravamen= $this->seguro_desgravamen($datosprestamo->ca_an);
                                 }else{
                                    $segurodesgravamen=0;
                                 } 
                                 
                                $total_cuotas = $prestamo->plazo;
                                $cuota =$this->redondeo_valor($cuotafinal + $segurodesgravamen);/// verificar si en caso se sigue pagando cargos en este proceso
                                $cuotaf = $cuota; /// verificar si en caso se sigue pagando cargos en este proceso
                                $capital = $datosprestamo->ca_an;
                                $intereses = $interes_periodo;
                                $interes_diferido =$sum_in_diferido;
                                $cargoscobranza = array();
                                $sumacargos=0; 
                                                   ////////////////////////////////////obtencion de cargos/////////////////////////////////////////////////////
                                                    ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                                                            for($i=65; $i<=90; $i++) {  
                                                                $abc="$".chr($i); 
                                                                eval($abc."=0;");
                                                            }
                                                    ////////////////////////////////////////////////////////////////////////////////////
                                                    foreach($perfilcobranza as $perfil){ 
                                                        $abc="$".$perfil['valor_abc'];
                                                        eval($abc."=".$perfil['formulaphp'].";");
                                                        if($perfil['iscargo']==1){ 
                                                                $cargoin=eval("return round(".$abc.",2);"); 
                                                                $sumacargos=$this->redondeo_valor($sumacargos+$cargoin); 
                                                                array_push($cargoscobranza,['idperfilcuentadetalle'=>$perfil['idperfilcuentadetalle'],'cargo'=>$cargoin,'rev'=>(strpos($perfil['formulaphp'],'$total_cuotas')>0)?1:0]);
                                                            }
                                                        }
                                                    ///////////////////////////////////////////////////////////////////////////////////////// 
     
                                $cuotaf =  $this->redondeo_valor($cuota+$sumacargos); 
                                $arrayDetalle = array();  
                                $debe=0;
                                $haber=0;
                                ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                                for($i=65; $i<=90; $i++) {  
                                    $abc="$".chr($i); 
                                    eval($abc."=0;");
                                }
                                ////////////////////////////////////////////////////////////////////////////////////
                                foreach($perfilcobranza as $perfil){ 
                                    $abc="$".$perfil['valor_abc'];
                                    eval($abc."=".$perfil['formulaphp'].";");
                                    $value=$this->redondeo_valor(eval("return ".$abc.";"));
                                    $value=$this->redondeo_valor($value*$prestamo->tipocambio); 
                               
                                    if($value>0){ 
                                        if($perfil['tipocargo']=='h'){ 
                                            $haber=$this->redondeo_valor($haber+$value);
                                            $value*= -1;
                                        }else{ 
                                            $debe=$this->redondeo_valor($debe+$value); 
                                        }
                                            ///////////////////////// registrar el perfil con su valor en un array
                                            array_push($arrayDetalle,array('idcuenta'=>$perfil['idcuenta'],
                                            'subcuenta'=>$prestamo->numpapeleta, 
                                            'documento'=>'Automatico', 
                                            'moneda'=>'bs',	 
                                            'monto'=>$value
                                            )); 
                                    }else{
                                        $value*= -1;
                                        if($value>0){ 
                                            if($perfil['tipocargo']=='h'){
                                                $haber=$this->redondeo_valor($haber+$value);
                                                $value*= -1;
                                            }else{
                                                $debe=$this->redondeo_valor($debe+$value); 
                                            }
                                                ///////////////////////// registrar el perfil con su valor en un array
                                            array_push($arrayDetalle,array('idcuenta'=>$perfil['idcuenta'],
                                            'subcuenta'=>$prestamo->numpapeleta, 
                                            'documento'=>'Automatico', 
                                            'moneda'=>'bs',	 
                                            'monto'=>$value
                                            )); 
                                        }

                                    }
                                }
                               ////////////////////////////////////////////////////////////////////////////////////  
                             
                   if($debe!=$haber){  
                    $outdata = array(); 
                    $validadoconta=2;
                   }  
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $cuotafinal=$this->redondeo_valor($cuotaf*$prestamo->tipocambio);   
                   
                   $outdata = array('cuotafinal'=>$cuotafinal,'cuota'=>$cuotaf,'idprestamo'=> $prestamo->idprestamo,
                    'asiento'=> array(
                        'cobranza_perfil'=>$prestamo->cobranza_perfil,
                        'numdoc'=>$numdoc,
                        'arraydetalle'=>$arrayDetalle,
                        'idmodulo'=>$idmodulo,
                        'fechaasiento'=>$fecha,
                        'idprestamoin'=>$idprestamoin

                    ),'db'=>array(
                        'idprestamo'=> $prestamo->idprestamo,
                        'pe'=> 500,
                        'fp'=> $fecha,
                        'di'=> $dias,
                        'ca_an'=> $datosprestamo->ca_an,
                        'am'=> $datosprestamo->ca_an,
                        'in'=> $interes_periodo,
                        'indi'=> $sum_in_diferido,
                        'ca'=> 0,
                        'seg'=> $segurodesgravamen,
                        'car'=> $sumacargos,
                        'cut'=> $cuotaf,
                        'cuota'=> $cuota,
                        'fecharegistro'=> $fecha,
                        'fechaCobranza'=> $fecha,
                        'tipo'=> 0,
                        'idestado'=> 11,
                        'idtransaccionC'=> "C-".$prestamo->idprestamo,
                        'numDocC'=> $numdoc,
                        // 'glosa'=> 'Cobranza del prestamo - automatico',
                        'tipocambio'=> $prestamo->tipocambio,
                        'importe'=> $cuotafinal,
                        'idusuario'=> Auth::id()
                     ));
                   
                    
                                         
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            }else{
                $outdata = array();
                $validadoconta=1; 
            }
        }else{
            $outdata = array();
            $validadoconta=3; 
        }
        return ['conta'=>$validadoconta,'data'=>$outdata,'cargosdata'=>$cargoscobranza];      
    }
}


