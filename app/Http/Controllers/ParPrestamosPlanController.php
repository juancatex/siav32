<?php

namespace App\Http\Controllers;

use App\Socio;
use App\Par_prestamos_plan;
use App\Par_Prestamos;
use App\Con_Asientomaestro;
use App\Par_prestamos_plan_cargo;
use App\Par_productos_perfilcuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;   
use App\AsinalssClass\AsientoMaestroClass;
use Illuminate\Database\Eloquent\ModelNotFoundException; 
 

class ParPrestamosPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fecha=(DB::select("select getfecha() as total"))[0]->total; 
        $prestamo = new Par_prestamos_plan(); 
        $prestamo->idprestamo=$request->idprestamo;
        $prestamo->pe=$request->pe;
        $prestamo->fp=$request->fp;
        $prestamo->di=$request->di;
        $prestamo->am=$request->am;
        $prestamo->in=$request->in;
        $prestamo->indi=$request->indi;
        $prestamo->car=$request->car;
        $prestamo->cut=$request->cut;
        $prestamo->ca=$request->ca;
        $prestamo->ca_an=$request->ca_an;
        $prestamo->cuota=$request->cuota;
        $prestamo->fecharegistro=$fecha; 
        if($request->pe==0){
            $prestamo->idestado=20;
        }
        $prestamo->save();

 // se quito el registro de cargos, porque se registrara cada ves que se realize una cobranda.

        // $cargosin=json_decode($request->cargos,true);
        // $idplann=$prestamo->idplan; 
        // foreach ($cargosin as $clave => $val){ 
   
        //     $cargosadicionales = new Par_prestamos_plan_cargo();  
        //     $cargosadicionales->idplan=$idplann;
        //     $cargosadicionales->idperfilcuentadetalle=$val['id'];
        //     $cargosadicionales->cargo=$val['value'];    
        //     $cargosadicionales->rev=$val['rev'];
        //     $cargosadicionales->save(); 
        // }
         
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_prestamos_plan  $par_prestamos_plan
     * @return \Illuminate\Http\Response
     */
    public function get_cobranza_refinanciamiento(Request $request)
    {
     if (!$request->ajax()) return redirect('/');
 
     // $cargosin=json_decode('{"total":7004.67,"prestamos":[31,32]}');
     /*round(planpre.am*mo.tipocambio,2)as am,
           round(planpre.in*mo.tipocambio,2)as inn,
           round(planpre.indi*mo.tipocambio,2)as indi, 
           round(planpre.cut*mo.tipocambio,2)as cut,
           round(planpre.cuota*mo.tipocambio,2)as cuota, 
           round(pre.monto*mo.tipocambio,2)as monto, */
    $cargosin=json_decode($request->json);
    $salida=[];
     foreach($cargosin->prestamos as $prestamoid){

           $conta= DB::select('SELECT 
           planpre.idplan,
           planpre.am,
           planpre.in as inn,
           planpre.indi, 
           planpre.cut,
           planpre.cuota, 
           pre.monto, 
           pre.plazo,  
           pre.idasiento,  
           so.numpapeleta,
           mo.tipocambio 
           FROM 
           par__prestamos pre,
           socios so,
           par__prestamos__plans planpre,
           par__productos pro,
           par__monedas mo 
           WHERE pre.idproducto=pro.idproducto 
           and pro.moneda=mo.idmoneda 
           and pre.idprestamo=planpre.idprestamo
           and pre.idsocio=so.idsocio
           and planpre.pe=500 
           and planpre.tipo=3 
           and planpre.idestado =13
           and pre.idprestamo=?',array($prestamoid));
        $formulascobranza = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil','=','par__productos__perfilcuentas.idperfilcuentamaestro')
        ->join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
        ->join('par__prestamos','par__productos.idproducto','=','par__prestamos.idproducto')
        ->select( 'par__productos.linea','par__productos.cobranza_perfil','par__productos__perfilcuentas.idperfilcuentadetalle',
        'par__productos__perfilcuentas.valor_abc',
        'par__productos__perfilcuentas.formula',
        'par__productos__perfilcuentas.iscomision',
        'con__perfilcuentadetalles.tipocargo',
        'con__perfilcuentadetalles.idcuenta',
        'par__productos__perfilcuentas.iscargo')
        ->where('par__productos__perfilcuentas.activo','=','1')
        ->where('par__prestamos.idprestamo','=',$prestamoid)
        ->get()->toArray();

         array_push($salida, array("datos"=>$conta,"formula"=>$formulascobranza));
         
        }

        
 
         return $salida;
    }

    public function grabar_estado_plan(Request $request) 
    {
        if (!$request->ajax()) return redirect('/');
        $fecha=(DB::select("select getfecha() as total"))[0]->total; 
        $cargosin=json_decode($request->cargos);
        
        foreach ($cargosin as $clave => $val){  
            // echo $val->idplan.'<br>';
            
                   $arrayDetalle=[];
                   $idmaestro=0;
                    foreach ($val->value as $valin){ 
                        $idmaestro=$valin->idmaestro;
                        array_push($arrayDetalle,array('idcuenta'=>$valin->id,
                        'subcuenta'=>$valin->num, 
                        'documento'=>'Automatico', 
                        'moneda'=>'bs',	 
                        'monto'=>$valin->value
                        ));  
                    }
                      
            
                    $asientomaestro= new AsientoMaestroClass();
                    $respuesta_perfil=$asientomaestro->AsientosMaestroArray($idmaestro,
                    '', 
                    '',  
                    'Liquidación del prestamo - automatico', 
                    $arrayDetalle,
                    $request->idmodulo,$fecha,'',$request->agrup);
             
                    
                    $prestamo = Par_prestamos_plan::findOrFail($val->idplan); 
                    $prestamo->idasiento =$respuesta_perfil;   
                    $prestamo->save(); 
                    
        } 
    }

    public function cobranza_ascii(Request $request) 
    {
        if (!$request->ajax()) return redirect('/');

        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);
        DB::beginTransaction();
        try{
             
            $prestamosOrder = array(); 
            foreach ($request->prestamos as  $valorder){ 
                if (array_key_exists($valorder['idproducto'],$prestamosOrder)){
                    $array=$prestamosOrder[$valorder['idproducto']];
                    array_push($array, $valorder);
                    $prestamosOrder[$valorder['idproducto']]=$array;
                }else{
                    $prestamosOrder[$valorder['idproducto']]=array($valorder);
                }
            }
          
    $fecha=(DB::select("select getfecha() as total"))[0]->total; 

     foreach ($prestamosOrder as  $idproducto=>$valorproducto){ 
           
            $idmaestro = 0;
            $arrayDetalle = array();
            $cargoscobranza = array();
            $cobrados_plans = array();
            $debe=0;
            $haber=0;

            foreach ($valorproducto as  $val){ 
                if($val['estatus']==1){ 
                      $idmaestro=$val['cobranza_perfil_ascii'];
                      $perfilcobranza =$this->getperfil($val['idproducto'],$val['cobranza_perfil_ascii']); 
                      
                      $total_cuotas = $val['plazo'];   
                      $cuota =$val['cuota'];  
                      $cuotaf =$val['cut'];  
                      $capital = $val['am'];  
                      $intereses = $val['inn'];  
                    
                       
                    if($val['inn']>($val['cut']-$val['indi']-$val['car'])){
                                 $capital = 0;
                                 $interesnew=$val['cut']-$val['indi']-$val['car'];
                                 $intereses = $interesnew;
                                 $intermedio=round(($val['inn']/$val['di']),2);  
                                 $newdias=round($interesnew/$intermedio); 

                                $nuevocobro=new Par_prestamos_plan(); 
                                $nuevocobro->file=$val['file'];
                                $nuevocobro->send_ascii=$val['send_ascii'];
                                $nuevocobro->cuota=$val['cuota'];
                                $nuevocobro->ca_an=$val['ca_an'];
                                $nuevocobro->ca=$val['ca'];
                                $nuevocobro->cut=$val['cut']; 
                                $nuevocobro->indi=$val['indi'];
                                $nuevocobro->fp=$val['fp'];
                                $nuevocobro->pe=$val['pe'];
                                $nuevocobro->idprestamo=$val['idprestamo']; 
                                $nuevocobro->in=$interesnew;
                                $nuevocobro->am=$capital;
                                $nuevocobro->di=$newdias; 
                                $nuevocobro->fecharegistro=$fecha;
                                $nuevocobro->tipo=2; 
                                $nuevocobro->idusuario=Auth::id();
                                $nuevocobro->save(); 
                                array_push($cobrados_plans,$nuevocobro->idplan); 


                                $planpago = Par_prestamos_plan::findOrFail($val['idplan']);  
                                $planpago->di=(intval($val['di']-$newdias)>0)?intval($val['di']-$newdias):1;
                                $planpago->in=round(($val['inn']-$interesnew),2); 
                                $planpago->indi=0;
                                $planpago->send_ascii=0;
                                $planpago->tipocambio=0; 
                                $planpago->file='';
                                $planpago->save();  

                                
                                $planpago = Par_prestamos_plan::findOrFail($val['idplan']);  
                                $auxliarcapital=round($planpago->cut-$planpago->car-$planpago->indi-$planpago->in);
                                $planpago->am=($auxliarcapital>0)?$auxliarcapital:0; 
                                $planpago->save();  
                                $this->recalcularPrestamos($val['idprestamo']);
                    }else{ 
                        array_push($cobrados_plans,$val['idplan']);  
                    }
                      
                            for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                                    
                                                        foreach($perfilcobranza as $perfil){ 
                                                        $abc="$".$perfil['valor_abc'];
                                                        eval($abc."=".$perfil['formulaphp'].";");
                                                        $value=eval("return round(".$abc.",2);");
                                                        $value=round($value*$val['tipocambio'],2);  
                                                                if($value>0){ 
                                                                            if($perfil['tipocargo']=='h'){
                                                                                $haber+=$value;
                                                                                $value*= -1;
                                                                            }else{
                                                                                $debe+=$value;
                                                                            }
                                                                        ///////////////////////// registrar el perfil con su valor en un array
                                                                    if (array_key_exists($perfil['idcuenta'],$arrayDetalle)){
                                                                        $array=$arrayDetalle[$perfil['idcuenta']];
                                                                        $array['monto']=round($array['monto']+$value,2);
                                                                        $arrayDetalle[$perfil['idcuenta']]=$array;
                                                                    }else{
                                                                      $arrayDetalle[$perfil['idcuenta']]=array('idcuenta'=>$perfil['idcuenta'],
                                                                        'subcuenta'=>'ascii', 
                                                                        'documento'=>'Automatico', 
                                                                        'moneda'=>'bs',	 
                                                                        'monto'=>$value
                                                                        );  
                                                                    }
                                                                        

                                                                }else{
                                                                    $value*= -1;
                                                                    if($value>0){ 
                                                                            if($perfil['tipocargo']=='h'){
                                                                                $haber+=$value;
                                                                                $value*= -1;
                                                                            }else{
                                                                                $debe+=$value;
                                                                            }
                                                                            ///////////////////////// registrar el perfil con su valor en un array
                                                                            if (array_key_exists($perfil['idcuenta'],$arrayDetalle)){
                                                                                $array=$arrayDetalle[$perfil['idcuenta']];
                                                                        $array['monto']=round($array['monto']+$value,2);
                                                                        $arrayDetalle[$perfil['idcuenta']]=$array;
                                                                            }else{
                                                                              $arrayDetalle[$perfil['idcuenta']]=array('idcuenta'=>$perfil['idcuenta'],
                                                                                'subcuenta'=>'ascii', 
                                                                                'documento'=>'Automatico', 
                                                                                'moneda'=>'bs',	 
                                                                                'monto'=>$value
                                                                                );  
                                                                            }
                                                                    }

                                                                }
                                                        }
                            if($val['idestado']==3){
                                Par_Prestamos::where('idprestamo','=',$val['idprestamo'])
                                ->update(['idestado' => 2]); 
                               $montoVigente = $this->getMontoSaldoTotal($val['idprestamo']);
                                //acumular el monto del capital del prestamo que esta ntrando a mora para generar su asiento contable
                            } 
                            Socio::where('idsocio','=',$val['idsocio'])
                            ->update(['idestprestamos' => 0]);
                    }else{
                        $this->recalcularPrestamos($val['idprestamo']);
                        
                        if($val['idestado']==2){
                            Par_Prestamos::where('idprestamo','=',$val['idprestamo'])
                            ->update(['idestado' => 3]); 
                            $montoMora = $this->getMontoSaldoTotal($val['idprestamo']);
                            //acumular el monto del capital del prestamo que esta ntrando a mora para generar su asiento contable
                            //verificar si tiene registros de cargos en la tabla par_prestamos_plan_cargo por la cuota enviada pero no cobrada....
                        } 
                        Socio::where('idsocio','=',$val['idsocio'])
                        ->update(['idestprestamos' => 1]); 
                    }
                }
                // if($debe!=$haber){ 
                if(bccomp($debe,$haber,2)!=0){ 
                    throw new ModelNotFoundException("Existe una variacion de valores en el asiento contable
                     al momento de realizar la cobranza por ascii, comuniquese con el administrador del sistema.
                     (idproducto = ".$idproducto." , !$debe! - !$haber! ".bccomp($debe,$haber,2).")"); 
                } 
                $asientomaestro= new AsientoMaestroClass();
                $respuesta_perfil=$asientomaestro->AsientosMaestroArrayASCII($idmaestro,
                '', 
                'ASCII',  
                $request->obs, 
                $arrayDetalle,
                $request->idmodulo,$fecha,''); 

                foreach ($cobrados_plans as $id){
                    $pruebamodifi=Par_prestamos_plan::findOrFail($id) ;
                    $pruebamodifi->idestado=12;
                    $pruebamodifi->idasiento=$respuesta_perfil;
                    $pruebamodifi->idtransaccionC='C-ASCII-'.$respuesta_perfil;
                    $pruebamodifi->numDocC='ASCII';
                    $pruebamodifi->glosa='Cobranza por ascii';
                    $pruebamodifi->fechaCobranza=$fecha; 
                    $pruebamodifi->importe=$pruebamodifi->send_ascii;
                    $pruebamodifi->idusuario=Auth::id();
                    $pruebamodifi->save();
                    } 
                
    }
 
                DB::commit();
                return ['status'=>1];
            }
            catch(\Exception $e)
            {
                DB::rollback(); 
                return ['status'=>0,'mensaje'=>$e->getMessage()];
            }

        
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
         ->get()->toArray();
     }

     function getMontoSaldoTotal($id){
        return (DB::select("select ROUND(plan.ca_an*mo.tipocambio,2) as ca_an from par__prestamos__plans plan,par__prestamos pp,par__productos pro,par__monedas mo 
        where mo.idmoneda=pro.moneda and pp.idproducto=pro.idproducto and pp.idprestamo=plan.idprestamo and plan.idprestamo=? 
                and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1", array($id)))[0]->ca_an; 
        // return (DB::select("select plan.ca_an from par__prestamos__plans plan where plan.idprestamo=? 
        // and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1", array($id)))[0]->ca_an; 
     }
     function recalcularPrestamos($prestamo)
     {  
         $total=DB::select("select * from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($prestamo));
         $tasain=DB::select("select p.tasa,pp.plazo,p.idproducto,p.cobranza_perfil_ascii from par__productos p, par__prestamos pp where p.idproducto=pp.idproducto and pp.idprestamo=?", array($prestamo));
         $tasa=$tasain[0]->tasa;
         $plazo_prestamo=$tasain[0]->plazo;
         $idproducto=$tasain[0]->idproducto;
         $idperfilcobranzaascii=$tasain[0]->cobranza_perfil_ascii;
         $tem =($tasa/12)/100; 
         $punterofechas=0;
         $sumatotal=0;
         $totaldias=0;
         $fp_base='';
         $pruebaarray=array(); 
         foreach($total as $valor)
                {     
                 $punterofechas++;
              
                     if($valor->idestado==10){
                        if($valor->pe==$plazo_prestamo){
                            $fp_base=$valor->fp; 
                            $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                            $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                            
                            $sumatotal=$valor->ca_an; 
                            $aux=$sumatotal;
                            $totaldias=intval($valor->di+$dias[0]->dias);
                            $interes=round(($aux*$tem*$totaldias)/30,2); 
                            $cuota=round($aux+$interes,2);


                            $total_cuotas = $plazo_prestamo;    
                            $cuotaf =round($cuota+$valor->indi+$valor->car,2);  
                            $capital = $aux;  
                            $intereses = $interes; 
        
        //////////////////////////////////////////////////////////////////////////// 
                               $perfilcobranza =$this->getperfil($idproducto,$idperfilcobranzaascii); 
                               for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                                $cargoFinal=0;
                                       foreach($perfilcobranza as $perfil){ 
                                               $abc="$".$perfil['valor_abc'];
                                               eval($abc."=".$perfil['formulaphp'].";");
                                               if($perfil['iscargo']==1){ 
                                                   $cargoin=eval("return round(".$abc.",2);");
                                                   $cargoFinal+=$cargoin;  
                                               }
                                       } 
        ////////////////////////////////////////////////////////////////////////////
        
                            array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$aux,'di'=>$totaldias,'in'=>$interes,
                            'car'=>$cargoFinal,'ca'=>0,'cut'=>round($cuota+$valor->indi+$cargoFinal,2),'ca_an'=>$aux,'cuota'=>$cuota,'tipo'=>2,'idestado'=>2)));
                              
                        }else { 
                            $fp_base=$valor->fp;   
                            $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                            $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                            
                            $totaldias=intval($valor->di+$dias[0]->dias);
                            $interes=round(($valor->ca_an*$tem*$totaldias)/30,2);
                            $auz_am=round((($valor->cut-$valor->car)-$valor->indi)-$interes,2);
                            $amortiza=($auz_am>0)?$auz_am:0;
                            $sumatotal=round(($valor->ca_an-$amortiza),2); 
                            array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$amortiza,'di'=>$totaldias,'in'=>$interes,'ca'=>$sumatotal,'tipo'=>2,'idestado'=>2)));
                        }
                     }elseif($valor->pe==$plazo_prestamo){
                         $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                         $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                         $aux=$sumatotal;
                         $interes=round(($aux*$tem*$dias[0]->dias)/30,2); 
                         $cuota=round($aux+$interes,2);
                        
                         $total_cuotas = $plazo_prestamo;    
                         $cuotaf =round($cuota+$valor->indi+$valor->car,2);  
                         $capital = $aux;  
                         $intereses = $interes;
     //////////////////////////////////////////////////////////////////////////// 
                            $perfilcobranza =$this->getperfil($idproducto,$idperfilcobranzaascii); 
                            for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                             $cargoFinal=0;
                                    foreach($perfilcobranza as $perfil){ 
                                            $abc="$".$perfil['valor_abc'];
                                            eval($abc."=".$perfil['formulaphp'].";");
                                            if($perfil['iscargo']==1){ 
                                                $cargoin=eval("return round(".$abc.",2);");
                                                $cargoFinal+=$cargoin;  
                                            }
                                    } 
     ////////////////////////////////////////////////////////////////////////////
     
                         array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$aux,'di'=>$dias[0]->dias,'in'=>$interes,
                         'car'=>$cargoFinal,'ca'=>0,'cut'=>round($cuota+$valor->indi+$cargoFinal,2),'ca_an'=>$aux,'cuota'=>$cuota,'tipo'=>2,'idestado'=>2)));
                          
     
     
                     }else{
                        $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                        $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                        $aux=$sumatotal;
                        $interes=round(($aux*$tem*$dias[0]->dias)/30,2);
                        $auz_am=round((($valor->cut-$valor->car)-$valor->indi)-$interes,2);
                        $amortiza=($auz_am>0)?$auz_am:0;
                        $sumatotal=round(($aux-$amortiza),2);
                        array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$amortiza,'di'=>$dias[0]->dias,
                        'in'=>$interes,'ca'=>$sumatotal,'ca_an'=>$aux,'tipo'=>2,'idestado'=>2)));
                         
                     } 
     
     
                } 
               
              foreach($pruebaarray as $valor){  
                     $planpago = Par_prestamos_plan::findOrFail($valor["key"]); 
                     foreach($valor["value"] as $akey=>$avalue) { $planpago->$akey=$avalue; }
                     $planpago->save(); 
                }  
      
                 
     } 

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
public function recalcularPrestamos_sinmora($prestamo)
{  
    $total=DB::select("select * from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($prestamo));
    $tasain=DB::select("select p.tasa,pp.plazo,p.idproducto,p.cobranza_perfil_ascii from par__productos p, par__prestamos pp where p.idproducto=pp.idproducto and pp.idprestamo=?", array($prestamo));
    $tasa=$tasain[0]->tasa;
    $plazo_prestamo=$tasain[0]->plazo;
    $idproducto=$tasain[0]->idproducto;
    $idperfilcobranzaascii=$tasain[0]->cobranza_perfil_ascii;
    $tem =($tasa/12)/100; 
    $punterofechas=0;
    $sumatotal=0;
    $totaldias=0;
    $fp_base='';
    $pruebaarray=array(); 
    foreach($total as $valor)
           {     
            $punterofechas++;
         
                if($valor->idestado==10){
                   if($valor->pe==$plazo_prestamo){
                        
                       $aux=$valor->ca_an; 
                       $totaldias=intval($valor->di);
                       $interes=round(($aux*$tem*$totaldias)/30,2); 
                       $cuota=round($aux+$interes,2);
    
                       array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('am'=>$aux,'di'=>$totaldias,'in'=>$interes,
                       'car'=>$cargoFinal,'ca'=>0,'cut'=>round($cuota+$valor->indi+$cargoFinal,2),'ca_an'=>$aux,'cuota'=>$cuota,'tipo'=>2,'idestado'=>2)));
                         
                   }else { 
                       $fp_base=$valor->fp;   
                       $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                       $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                       
                       $totaldias=intval($valor->di+$dias[0]->dias);
                       $interes=round(($valor->ca_an*$tem*$totaldias)/30,2);
                       $auz_am=round((($valor->cut-$valor->car)-$valor->indi)-$interes,2);
                       $amortiza=($auz_am>0)?$auz_am:0;
                       $sumatotal=round(($valor->ca_an-$amortiza),2); 
                       array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$amortiza,'di'=>$totaldias,'in'=>$interes,'ca'=>$sumatotal,'tipo'=>2,'idestado'=>2)));
                   }
                }elseif($valor->pe==$plazo_prestamo){
                    $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                    $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                    $aux=$sumatotal;
                    $interes=round(($aux*$tem*$dias[0]->dias)/30,2); 
                    $cuota=round($aux+$interes,2);

//////////////////////////////////////////////////////////////////////////// 
                       $perfilcobranza =$this->getperfil($idproducto,$idperfilcobranzaascii); 
                       for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                        $cargoFinal=0;
                               foreach($perfilcobranza as $perfil){ 
                                       $abc="$".$perfil['valor_abc'];
                                       eval($abc."=".$perfil['formulaphp'].";");
                                       if($perfil['iscargo']==1){ 
                                           $cargoin=eval("return round(".$abc.",2);");
                                           $cargoFinal+=$cargoin;  
                                       }
                               } 
////////////////////////////////////////////////////////////////////////////

                    array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$aux,'di'=>$dias[0]->dias,'in'=>$interes,
                    'car'=>$cargoFinal,'ca'=>0,'cut'=>round($cuota+$valor->indi+$cargoFinal,2),'ca_an'=>$aux,'cuota'=>$cuota,'tipo'=>2,'idestado'=>2)));
                     


                }else{
                   $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                   $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                   $aux=$sumatotal;
                   $interes=round(($aux*$tem*$dias[0]->dias)/30,2);
                   $auz_am=round((($valor->cut-$valor->car)-$valor->indi)-$interes,2);
                   $amortiza=($auz_am>0)?$auz_am:0;
                   $sumatotal=round(($aux-$amortiza),2);
                   array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$amortiza,'di'=>$dias[0]->dias,
                   'in'=>$interes,'ca'=>$sumatotal,'ca_an'=>$aux,'tipo'=>2,'idestado'=>2)));
                    
                } 


           } 
          
         foreach($pruebaarray as $valor){  
                $planpago = Par_prestamos_plan::findOrFail($valor["key"]); 
                foreach($valor["value"] as $akey=>$avalue) { $planpago->$akey=$avalue; }
                $planpago->save(); 
           }  
            
} 
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////











  
    public function getSendedAscii(Request $request)
    {   
       if (!$request->ajax()) return redirect('/'); 
        $valuelinea=[];
      
        // $valores=(DB::select(" select DISTINCT s.numpapeleta, 
        // getasciisended(s.numpapeleta,0) as pres,
        // getasciisended(s.numpapeleta,1) as sumtotal 
        // from socios s,par__prestamos pre,par__prestamos__plans plan  
        // where  pre.idprestamo=plan.idprestamo and plan.idestado=10 and pre.idsocio=s.idsocio"));  
        // $valores=(DB::select(" select   s.numpapeleta,pre.idproducto ,plan.idplan , plan.send_ascii 
        // from socios s,par__prestamos pre,par__prestamos__plans plan  
        // where  pre.idprestamo=plan.idprestamo and plan.idestado=10 and pre.idsocio=s.idsocio order by s.numpapeleta,plan.send_ascii DESC")); 

        /* 
         round(plan.am*mo.tipocambio,2)as am,
        round(plan.in*mo.tipocambio,2)as inn,
        round(plan.indi*mo.tipocambio,2)as indi, 
        round(plan.cut*mo.tipocambio,2)as cut, 
        round(plan.cuota*mo.tipocambio,2)as cuota, 
        round(pre.monto*mo.tipocambio,2)as monto,
        */
        $valores=(DB::select("select  pre.idproducto,plan.idplan,pre.idprestamo,pro.cobranza_perfil_ascii,
        plan.am,
        plan.in as inn,
        plan.indi, 
        plan.cut, 
        plan.cuota, 
        pre.monto,
        round(plan.cut*mo.tipocambio,2)as cut2,
        plan.pe,
        plan.fp,
        plan.car,
        plan.ca,
        plan.ca_an,
        plan.send_ascii,
        plan.file,
        plan.di,
        pre.idestado, 
        s.numpapeleta,pre.plazo,mo.tipocambio,s.idsocio 
     from socios s,par__prestamos pre,par__prestamos__plans plan ,par__productos pro,par__monedas mo 
     where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pre.idprestamo=plan.idprestamo and plan.idestado=10 and pre.idsocio=s.idsocio and (pre.idestado between 2 and 3) 
     order by s.numpapeleta,plan.send_ascii ASC")); 
    //  order by s.numpapeleta,plan.send_ascii DESC")); 
        return $valores; 
    }
    public function delete_planpagos(Request $request) 
    { if (!$request->ajax()) return redirect('/');
        $prestamo = Par_Prestamos::findOrFail($request->id); 
        $prestamo->fecharde_apro_conta =null; 
        $prestamo->apro_conta =0;  
        if(is_int($prestamo->idasiento)){
            $asientomaestro = Con_Asientomaestro::findOrFail($prestamo->idasiento); 
            $asientomaestro->estado = 2;
            $asientomaestro->save(); 
            $prestamo->idasiento =null;
        }  
        $prestamo->save();
        Par_prestamos_plan::where('idprestamo',$request->id)->delete();
        
    }

 
}
