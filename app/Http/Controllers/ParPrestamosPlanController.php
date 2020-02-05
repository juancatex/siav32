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

class ParPrestamosPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $cargosin=json_decode($request->cargos,true);
        $idplann=$prestamo->idplan;
        foreach ($cargosin as $clave => $val){ 
   
            $cargosadicionales = new Par_prestamos_plan_cargo();  
            $cargosadicionales->idplan=$idplann;
            $cargosadicionales->idperfilcuentadetalle=$val['id'];
            $cargosadicionales->cargo=$val['value'];    
            $cargosadicionales->rev=$val['rev'];
            $cargosadicionales->save(); 
        }
         
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
            $fecha=(DB::select("select getfecha() as total"))[0]->total; 
               
            foreach ($request->prestamos as $clave => $val){  
                        $arrayDetalle=[];
                        $idmaestro=0;
                            foreach ($val['perfil'] as $valin){ 
                                $idmaestro=$valin['idmaestro'];
                                array_push($arrayDetalle,array('idcuenta'=>$valin['id'],
                                'subcuenta'=>$valin['num'], 
                                'documento'=>'Automatico', 
                                'moneda'=>'bs',	 
                                'monto'=>$valin['value']
                                ));  
                            } 
                           $asientomaestro= new AsientoMaestroClass();
                            $respuesta_perfil=$asientomaestro->AsientosMaestroArray($idmaestro,
                            '', 
                            '',  
                            $request->obs, 
                            $arrayDetalle,
                            $request->idmodulo,$fecha,'',-1); 

                            foreach ($val['plans'] as $id){
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
                            
                           
                            foreach ($val['nuevos'] as $valorinsert){ 
                                $nuevocobro=new Par_prestamos_plan();
                                foreach($valorinsert[0] as $plannombre=>$planvalue) { $nuevocobro->$plannombre=$planvalue; }
                                $nuevocobro->fecharegistro=$fecha;
                                $nuevocobro->tipo=2;
                                $nuevocobro->idestado=12;
                                $nuevocobro->idasiento=$respuesta_perfil;
                                $nuevocobro->idtransaccionC='C-ASCII-'.$respuesta_perfil;
                                $nuevocobro->numDocC='ASCII';
                                $nuevocobro->glosa='Cobranza por ascii';
                                $nuevocobro->fechaCobranza=$fecha;
                                $nuevocobro->importe=$nuevocobro->send_ascii;
                                $nuevocobro->idusuario=Auth::id();
                                $nuevocobro->save();  
                                $planpago = Par_prestamos_plan::findOrFail($valorinsert[1]); 
                                foreach($valorinsert[2] as $akey=>$avalue) { $planpago->$akey=$avalue; } 
                                $planpago->save();  
                                $planpago = Par_prestamos_plan::findOrFail($valorinsert[1]);  
                                $planpago->am=(($planpago->cut-$planpago->car-$planpago->indi-$planpago->in)>0)?($planpago->cut-$planpago->car-$planpago->indi-$planpago->in):0;
                                $planpago->save();  
                                $this->recalcularPrestamos($valorinsert[3]);

                            }
 
 

                        /*    $cambio=Par_Prestamos::whereIn('idprestamo',$val['prestamos']) 
                            ->where('idestado','=',3); 
                                foreach ($cambio->get()->toArray() as  $valor){ 
                                        $sumaprestamos= Par_Prestamos::where('idsocio','=',$valor['idsocio'])
                                        ->whereNotIn('idprestamo',$val['prestamos']) 
                                        ->where('idestado','=',3)->count(); 
                                        if($sumaprestamos==0){
                                            $Socio = Socio::findOrFail($valor['idsocio']);  
                                            $Socio->idestprestamos = '0';  
                                            $Socio->save();
                                        }
                                } 


                            $cambio->update(['idestado' => 2]); */ //para cobranza manual
                }

                foreach ($request->moras as $valuemoras){
                   Par_Prestamos::where('idprestamo','=',$valuemoras['idprestamo'])
                    ->update(['idestado' => 3]);
                    Socio::where('idsocio','=',$valuemoras['idsocio'])
                    ->update(['idestprestamos' => 1]);  
                    $this->mora($valuemoras['idprestamo']); 
                }

                foreach ($request->acreedores as $valueacreedores){
                    
                    
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


 

     function recalcularPrestamos($prestamo)
     {  
         $total=DB::select("select * from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($prestamo));
         $tasain=DB::select("select p.tasa,pp.plazo,p.idproducto from par__productos p, par__prestamos pp where p.idproducto=pp.idproducto and pp.idprestamo=?", array($prestamo));
         $tasa=$tasain[0]->tasa;
         $plazo_prestamo=$tasain[0]->plazo;
         $idproducto=$tasain[0]->idproducto;
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
                         $fp_base=$valor->fp;   
                         $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                         
                         $totaldias=($valor->di);
                         $interes=round(($valor->ca_an*$tem*$totaldias)/30,2);
                         $auz_am=round((($valor->cut-$valor->car)-$valor->indi)-$interes,2);
                         $amortiza=($auz_am>0)?$auz_am:0;
                         $sumatotal=round(($valor->ca_an-$amortiza),2);
     
                         array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$amortiza,'di'=>$totaldias,'in'=>$interes,'ca'=>$sumatotal,'tipo'=>2,'idestado'=>2)));
                         
                     }elseif($valor->pe==$plazo_prestamo){
                         $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                         $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                         $aux=$sumatotal;
                         $interes=round(($aux*$tem*$dias[0]->dias)/30,2); 
                        
     
     //////////////////////////////////////////////////////////////////////////// 
                         Par_prestamos_plan_cargo::where('idplan','=',$valor->idplan)->delete(); 
                         $perfilcobranza = Par_productos_perfilcuenta::select( 'idperfilcuentadetalle','formula')
                         ->where('iscargo','=','1')
                         ->where('activo','=','1')
                         ->where('idproducto','=',$idproducto)
                         ->get()->toArray();
                         $cargoFinal=0;
                         $cuota=round($aux+$interes,2);
                         foreach ($perfilcobranza as  $perfil){  
                             $formula= $perfil['formula'];
                             $rev=0;
                             if(strpos($formula,'total_cuotas')>0){
                               $formula = str_replace("total_cuotas", "\$plazo_prestamo", $formula);
                               $rev=1;
                             }else{
                               $formula = str_replace("operador", "", $formula);
                               $formula = str_replace("cuota", "\$cuota", $formula);
                             }
                           
                           $formula=eval("return round($formula,2);");
     
                           $cargosadicionales = new Par_prestamos_plan_cargo();  
                           $cargosadicionales->idplan=$valor->idplan;
                           $cargosadicionales->idperfilcuentadetalle=$perfil['idperfilcuentadetalle'];
                           $cargosadicionales->cargo=$formula;    
                           $cargosadicionales->rev=$rev;
                           $cargosadicionales->save(); 
                           $cargoFinal+=$formula;
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
public function mora($prestamo)
{  
    $total=DB::select("select * from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($prestamo));
    $tasain=DB::select("select p.tasa,pp.plazo,p.idproducto from par__productos p, par__prestamos pp where p.idproducto=pp.idproducto and pp.idprestamo=?", array($prestamo));
    $tasa=$tasain[0]->tasa;
    $plazo_prestamo=$tasain[0]->plazo;
    $idproducto=$tasain[0]->idproducto;
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
                          
                       $totaldias=($valor->di+$dias[0]->dias); 
                       $aux=$valor->ca_an;
                       $interes=round(($aux*$tem*$totaldias)/30,2);  
    //////////////////////////////////////////////////////////////////////////// 
                        Par_prestamos_plan_cargo::where('idplan','=',$valor->idplan)->delete(); 
                        $perfilcobranza = Par_productos_perfilcuenta::select( 'idperfilcuentadetalle','formula')
                        ->where('iscargo','=','1')
                        ->where('activo','=','1')
                        ->where('idproducto','=',$idproducto)
                        ->get()->toArray();
                        $cargoFinal=0;
                        $cuota=round($aux+$interes,2);
                        foreach ($perfilcobranza as  $perfil){  
                            $formula= $perfil['formula'];
                            $rev=0;
                            if(strpos($formula,'total_cuotas')>0){
                              $formula = str_replace("total_cuotas", "\$plazo_prestamo", $formula);
                              $rev=1;
                            }else{
                              $formula = str_replace("operador", "", $formula);
                              $formula = str_replace("cuota", "\$cuota", $formula);
                            }
                          
                          $formula=eval("return round($formula,2);");
    
                          $cargosadicionales = new Par_prestamos_plan_cargo();  
                          $cargosadicionales->idplan=$valor->idplan;
                          $cargosadicionales->idperfilcuentadetalle=$perfil['idperfilcuentadetalle'];
                          $cargosadicionales->cargo=$formula;    
                          $cargosadicionales->rev=$rev;
                          $cargosadicionales->save(); 
                          $cargoFinal+=$formula;
                         }
                         
    ////////////////////////////////////////////////////////////////////////////
    
                        array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'am'=>$aux,'di'=>$totaldias,'in'=>$interes,
                        'car'=>$cargoFinal,'ca'=>0,'cut'=>round($cuota+$valor->indi+$cargoFinal,2),'ca_an'=>$aux,'cuota'=>$cuota,'tipo'=>2,'idestado'=>2)));    
                       
                    
                    }else{
                    $fp_base=$valor->fp;   
                    $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                    $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
 
                    $totaldias=($valor->di+$dias[0]->dias);
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
                   

//////////////////////////////////////////////////////////////////////////// 
                    Par_prestamos_plan_cargo::where('idplan','=',$valor->idplan)->delete(); 
                    $perfilcobranza = Par_productos_perfilcuenta::select( 'idperfilcuentadetalle','formula')
                    ->where('iscargo','=','1')
                    ->where('activo','=','1')
                    ->where('idproducto','=',$idproducto)
                    ->get()->toArray();
                    $cargoFinal=0;
                    $cuota=round($aux+$interes,2);
                    foreach ($perfilcobranza as  $perfil){  
                        $formula= $perfil['formula'];
                        $rev=0;
                        if(strpos($formula,'total_cuotas')>0){
                          $formula = str_replace("total_cuotas", "\$plazo_prestamo", $formula);
                          $rev=1;
                        }else{
                          $formula = str_replace("operador", "", $formula);
                          $formula = str_replace("cuota", "\$cuota", $formula);
                        }
                      
                      $formula=eval("return round($formula,2);");

                      $cargosadicionales = new Par_prestamos_plan_cargo();  
                      $cargosadicionales->idplan=$valor->idplan;
                      $cargosadicionales->idperfilcuentadetalle=$perfil['idperfilcuentadetalle'];
                      $cargosadicionales->cargo=$formula;    
                      $cargosadicionales->rev=$rev;
                      $cargosadicionales->save(); 
                      $cargoFinal+=$formula;
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
        $valores=(DB::select("select  pre.idproducto,plan.idplan,pre.idprestamo,
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
        s.numpapeleta,pre.plazo,mo.tipocambio,s.idsocio 
     from socios s,par__prestamos pre,par__prestamos__plans plan ,par__productos pro,par__monedas mo 
     where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pre.idprestamo=plan.idprestamo and plan.idestado=10 and pre.idsocio=s.idsocio
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
