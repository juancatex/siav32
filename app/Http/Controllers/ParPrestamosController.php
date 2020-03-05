<?php

namespace App\Http\Controllers;

use App\Socio;
use App\Par_Prestamos;
use App\Par_prestamos_plan;
use App\Con_Asientomaestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;   
use App\AsinalssClass\AsientoMaestroClass; 
use App\Par_productos_perfilcuenta;
use App\Par_prestamos_plan_cargo;
use App\Par_Producto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class ParPrestamosController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
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
    { if (!$request->ajax()) return redirect('/');
        $fecha=(DB::select("select getfecha() as total"))[0]->total;
        $prestamo = new Par_Prestamos();
        $prestamo->idproducto=$request->idproducto;
        $prestamo->idsocio=$request->idsocio; 
        $prestamo->idoperario=Auth::id();
        $prestamo->idsupervisor=Auth::id();
        $prestamo->monto=$request->monto;  
        $prestamo->plazo=$request->plazo; 
        $prestamo->factor=$request->factor; 
        $prestamo->idcuentasocio=$request->cuenta; 
        $prestamo->obs=$request->obs; 
        $prestamo->fecharegistro=$fecha;
        
        $prestamo->liquidopagable=$request->lip; 
        $prestamo->liquidocomputable=$request->lipcom; 
        $prestamo->cuotasvigentes=$request->pvig; 
        $prestamo->b_frontera=$request->fron; 
        $prestamo->b_prolibro=$request->libro; 
        $prestamo->b_familiar=$request->fami; 
        $prestamo->b_riesgo=$request->riesgo; 
        $prestamo->cuota_aprox=$request->cuo_aprox; 
        $prestamo->planPagosMap=$request->planPagosMap; 
 

        $prestamo->save(); 
        $prestamo->idref =$prestamo->idprestamo;
        $prestamo->idrefaux =$prestamo->idprestamo;
        /*$prestamo->no_prestamo =$prestamo->idproducto.'-'.$prestamo->idejecucion.'-'.str_pad($prestamo->idprestamo, 10, "0", STR_PAD_LEFT);*/

        $total=DB::select("select codproducto as total from  par__productos where idproducto=?", array($prestamo->idproducto));
 
        $prestamo->no_prestamo ='P-'.strtoupper($total[0]->total).'-'.str_pad($prestamo->idprestamo, 10, "0", STR_PAD_LEFT);
        $prestamo->save();

        return response()->json(array('id' => $prestamo->idprestamo), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_Prestamos  $par_Prestamos
     * @return \Illuminate\Http\Response
     */
    public function show(Par_Prestamos $par_Prestamos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Par_Prestamos  $par_Prestamos
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_Prestamos $par_Prestamos)
    {
        //
    }

  
    public function grabar_estado(Request $request) 
    {
        if (!$request->ajax()) return redirect('/'); 
        $prestamo = Par_Prestamos::findOrFail($request->idp);  
        $prestamo->cuota = $request->cuota;  
        $prestamo->idusuario=Auth::id();   
        $prestamo->save();
 
    }
    public function grabar_desembolsoNormal(Request $request){
        if (!$request->ajax()) return redirect('/'); 

        DB::beginTransaction();  
        try{ 
         /////////////////////////////////////////////////////// desembolso ///////////////////////////////////////////////////////////////////////////////////////////////////////
         $product_perfil=(DB::select("SELECT pp.idproducto,pp.desembolso_perfil,pre.plazo,pre.no_prestamo,pre.monto*mo.tipocambio as montotal, s.numpapeleta 
         from par__prestamos pre,par__productos pp,par__monedas mo,socios s 
          where pre.idsocio=s.idsocio and pp.moneda=mo.idmoneda and pre.idproducto=pp.idproducto and pre.idprestamo=?",array($request->idprestamoin)))[0]; 
          ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                     for($i=65; $i<=90; $i++) {  
                         $abc="$".chr($i); 
                         eval($abc."=0;");
                     }
          ////////////////////////////////////////////////////////////////////////////////////
          $debe=0;
          $haber=0;
          $total_cuotas = $product_perfil->plazo;     
          $monto = $product_perfil->montotal; 
          $total_refinanciar=0;
          $prestamos = $total_refinanciar; 
          $arrayDesembolso = array(); 
          $perfildesembolso =$this->getperfil($product_perfil->idproducto,$product_perfil->desembolso_perfil); 
                             foreach($perfildesembolso as $perfil){ 
                                 $abc="$".$perfil['valor_abc'];
                                 eval($abc."=".$perfil['formulaphp'].";");
                                 $value=eval("return round(".$abc.",2);"); 
                                 if($value>0){ 
                                     if($perfil['tipocargo']=='h'){
                                         $haber+=$value;
                                         $value*= -1;
                                     }else{
                                         $debe+=$value;
                                     }
                                         ///////////////////////// registrar el perfil con su valor en un array
                                         array_push($arrayDesembolso,array('idcuenta'=>$perfil['idcuenta'],
                                         'subcuenta'=>$product_perfil->numpapeleta, 
                                         'documento'=>$product_perfil->no_prestamo, 
                                         'moneda'=>'bs',	 
                                         'monto'=>$value
                                         ));     
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
                                             array_push($arrayDesembolso,array('idcuenta'=>$perfil['idcuenta'],
                                             'subcuenta'=>$product_perfil->numpapeleta, 
                                             'documento'=>$product_perfil->no_prestamo, 
                                             'moneda'=>'bs',	 
                                             'monto'=>$value
                                             ));
                                     }

                                 }
                             }
                   
                             if($debe!=$haber){ 
                                 throw new ModelNotFoundException("Existe una variacion de valores en el asiento contable al momento de realizar el desembolso del prestamo, comuniquese con el administrador del sistema."); 
                                } 
                             $fechaasiento=(DB::select("select getfecha() as total"))[0]->total;  
                                     $asientomaestro= new AsientoMaestroClass();
                                     $respuesta_perfil=$asientomaestro->AsientosMaestroArray($product_perfil->desembolso_perfil,
                                     'lote', //lote
                                     $product_perfil->no_prestamo,  
                                     'Desembolso del prestamo - automatico', 
                                     $arrayDesembolso,
                                     $request->idmodulo,$fechaasiento,$request->lote,$request->idprestamoin); 
                            
                             $prestamo_desembolso = Par_Prestamos::findOrFail($request->idprestamoin); 
                             $prestamo_desembolso->idestado =2;  
                             $total=DB::select("select codproducto as total from  par__productos where idproducto=?", array($prestamo_desembolso->idproducto)); 
                             $prestamo_desembolso->idtransaccionD = 'D-'.strtoupper($total[0]->total).'-'.str_pad($prestamo_desembolso->idprestamo, 10, "0", STR_PAD_LEFT);
                             $prestamo_desembolso->detalle_desembolso = $request->detalle;
                             $prestamo_desembolso->numDocD = $request->numdoc; 
                             $prestamo_desembolso->lote = $request->lote; 
                             $prestamo_desembolso->fechardesembolso = $fechaasiento; 
                             $prestamo_desembolso->idasiento = $respuesta_perfil; 
                             $prestamo_desembolso->idusuario=Auth::id();   
                             $prestamo_desembolso->save();
                DB::commit(); 
                return response()->json(array('data' =>'ok'), 200);   
            }
            catch(\Exception $e)
            {
                DB::rollback();  
                return response()->json(array('data' =>'error','mensaje'=>$e->getMessage()), 500);  
            }
    }
    public function update_estado_observado(Request $request) 
    {
        if (!$request->ajax()) return redirect('/');
        $fecha=(DB::select("select getfecha() as total"))[0]->total; 
        $aux=0;
        $prestamo = Par_Prestamos::findOrFail($request->idp); 
        $prestamo->idestado = '7'; 
        $prestamo->obs=$prestamo->obs.' OBS: Eliminacion por observación en Fecha : "'.date("d-m-y H:i:s").'" Fecha sistema : "'.$fecha.'" por Iduser:'.Auth::id().'.';  
        $aux=$prestamo->idasiento;
        $prestamo->save();

        Par_prestamos_plan::where('idprestamo',$request->idp)->delete();


        $asientomaestro = Con_Asientomaestro::findOrFail($aux); 
        $asientomaestro->estado = 2;
        $asientomaestro->save();
 
    }
    public function update_estado_revertir(Request $request) 
    {
        if (!$request->ajax()) return redirect('/');
        $fecha=(DB::select("select getfecha() as total"))[0]->total; 
        
        $prestamo = Par_Prestamos::findOrFail($request->idp); 
        $prestamo->idestado = '8'; 
        $prestamo->obs=$prestamo->obs.' OBS: Eliminacion por reversion en Fecha : "'.date("d-m-y H:i:s").'" Fecha sistema : "'.$fecha.'" por Iduser:'.Auth::id().'.';  
        $prestamo->save();

        Par_prestamos_plan::where('idprestamo',$request->idp)->delete();
 
 
    }
    public function delete_estado(Request $request) 
    {
        if (!$request->ajax()) return redirect('/');
        
        $prestamo = Par_Prestamos::findOrFail($request->idprestamo); 
        $prestamo->idestado = '6';  
        $prestamo->save();
 
    }
    public function getprestamosperiodo(Request $request)
    { if (!$request->ajax()) return redirect('/');
        $sum = DB::raw("sum(cuota) as total");
        $where = DB::raw("fecharegistro BETWEEN '".$request->fecha."' AND getfecha()");

        $total=Par_Prestamos::select($sum)
        ->whereraw($where)
        ->where('idsocio','=',$request->idsocio)
        ->where('idestado','=','3')->get();  

        //$total=(DB::select("select sum(cuota) as total FROM par__prestamos WHERE  (fecharegistro BETWEEN getfecha() AND ? )and idsocio=? and idestado=3", array($request->fechapapeleta,$request->idsocio)))[0]->total;
        
        return ['cuotas'=>$total[0]->total];
    }
    public function getprestamostotal(Request $request)
    { if (!$request->ajax()) return redirect('/');
          
        $total=DB::select("select IFNULL(ROUND(SUM(getcuota(pre.idprestamo,mo.tipocambio)),2),0)as total 
        from par__prestamos pre,par__productos pro,par__monedas mo 
        where pre.idproducto=pro.idproducto  
        and pro.moneda=mo.idmoneda and pre.idsocio=? 
        and pre.apro_conta not between 2 and 4 and pre.idestado between 2 and 3", array($request->idsocio)); 

        return ['cuotas'=>($total[0]->total + 0)];
    }

    
    public function prestamosDesembolso(Request $request)
    {  
      if (!$request->ajax()) return redirect('/');

        
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        $tipo = DB::raw("par__prestamos__tipo__ejecucion.nombre as nombretipo");
     
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(par__prestamos.no_prestamo like '%".$valor."%' or  par__productos.nomproducto like '%".$valor."%' or socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (par__prestamos.no_prestamo like '%".$valor."%' or par__productos.nomproducto like '%".$valor."%' or socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 
            } 
            
            $socios=Par_Prestamos::select('con__asientomaestros.observaciones','par__prestamos.fecharde_apro_conta','par__prestamos.idejecucion',
            'par__prestamos.idasiento','socios.numpapeleta','par__prestamos.lote','par__prestamos.fechardesembolso','par__prestamos.apro_conta',
            'par__prestamos.idtransaccionD','par__prestamos.idestado','par__prestamos__estados.nombreestado','par__prestamos.no_prestamo',
            'par__monedas.tipocambio','par__productos.idproducto','socios.idsocio','par__productos.tasa','par__prestamos.detalle_desembolso',
            'par__monedas.nommoneda','par__prestamos.idprestamo',$tipo,'par__productos.nomproducto',
            'par_grados.nomgrado','socios.nombre','socios.apaterno','socios.amaterno', 'par__prestamos.monto','par__prestamos.plazo'
            ,'par__prestamos.fecharegistro','par__prestamos.idoperario','par__prestamos.idusuario','par__productos.garantes'
           
            ,'par__productos.cancelarprestamos'
            ,'par__productos.activar_garante'
            ,'par__productos.desembolso_perfil' 
            ,'par__productos.desembolso_perfil_refi'
            ,'par__productos.desembolso_perfil_garante'
            ,'par__productos.cobranza_perfil_refi'
            ,'par__productos.cobranza_perfil_garante')
            ->join ('par__productos','par__prestamos.idproducto','=','par__productos.idproducto')
            ->join ('par__prestamos__estados','par__prestamos.idestado','=','par__prestamos__estados.idestado')
            ->join ('socios','par__prestamos.idsocio','=','socios.idsocio')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('par__prestamos__tipo__ejecucion','par__prestamos.idejecucion','=','par__prestamos__tipo__ejecucion.idejecucion')
            ->join ('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->leftJoin ('con__asientomaestros','par__prestamos.idasiento','=','con__asientomaestros.idasientomaestro')
            //  ->where('par__prestamos.apro_conta','!=','1') 
            //  ->where('par__prestamos.apro_conta','!=','2') 
            ->whereBetween('par__prestamos.idestado',[1,6])
            ->where('par__prestamos.idestado','!=','3')
            ->whereraw($sqls)
            ->orderBy('par__prestamos.idestado', 'asc')
            ->orderBy('par__prestamos.lote', 'DESC')
            ->orderBy('par__prestamos.apro_conta', 'DESC') 
            ->orderBy('socios.nombre', 'asc')->paginate(10);
        }
        else{	 
            $socios=Par_Prestamos::select('con__asientomaestros.observaciones','par__prestamos.fecharde_apro_conta','par__prestamos.idejecucion',
            'par__prestamos.idasiento','socios.numpapeleta','par__prestamos.lote','par__prestamos.fechardesembolso','par__prestamos.apro_conta',
            'par__prestamos.idtransaccionD','par__prestamos.idestado','par__prestamos__estados.nombreestado','par__prestamos.no_prestamo',
            'par__monedas.tipocambio','par__productos.idproducto','socios.idsocio','par__productos.tasa','par__prestamos.detalle_desembolso',
            'par__monedas.nommoneda','par__prestamos.idprestamo',$tipo,'par__productos.nomproducto',
            'par_grados.nomgrado','socios.nombre','socios.apaterno','socios.amaterno', 'par__prestamos.monto','par__prestamos.plazo'
            ,'par__prestamos.fecharegistro','par__prestamos.idoperario','par__prestamos.idusuario','par__productos.garantes'
          
            ,'par__productos.cancelarprestamos'
            ,'par__productos.activar_garante'
            ,'par__productos.desembolso_perfil' 
            ,'par__productos.desembolso_perfil_refi'
            ,'par__productos.desembolso_perfil_garante'
            ,'par__productos.cobranza_perfil_refi'
            ,'par__productos.cobranza_perfil_garante')
            ->join ('par__productos','par__prestamos.idproducto','=','par__productos.idproducto')
            ->join ('par__prestamos__estados','par__prestamos.idestado','=','par__prestamos__estados.idestado')
            ->join ('socios','par__prestamos.idsocio','=','socios.idsocio')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('par__prestamos__tipo__ejecucion','par__prestamos.idejecucion','=','par__prestamos__tipo__ejecucion.idejecucion')
            ->join ('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->leftJoin ('con__asientomaestros','par__prestamos.idasiento','=','con__asientomaestros.idasientomaestro')
           
            ->where('par__prestamos.apro_conta','!=','1') 
            ->where('par__prestamos.apro_conta','!=','2') 

            ->where('par__prestamos.idoperario','=',Auth::id()) 
            ->whereBetween('par__prestamos.idestado',[1,2])
            ->orderBy('par__prestamos.idestado', 'asc')
            ->orderBy('par__prestamos.lote', 'DESC')
            ->orderBy('par__prestamos.apro_conta', 'DESC')
            
            ->orderBy('socios.nombre', 'asc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $socios->total(),
                'current_page' => $socios->currentPage(),
                'per_page'     => $socios->perPage(),
                'last_page'    => $socios->lastPage(),
                'from'         => $socios->firstItem(),
                'to'           => $socios->lastItem(),
            ],
            'prestamos' => $socios
        ];
    }
    public function prestamosMoras(Request $request)
    {  
      if (!$request->ajax()) return redirect('/');
 
        $tipo = DB::raw("par__prestamos__tipo__ejecucion.nombre as nombretipo");
            $socios=Par_Prestamos::select('con__asientomaestros.observaciones','par__prestamos.fecharde_apro_conta','par__prestamos.idasiento','socios.numpapeleta',
            'par__productos.cancelarprestamos','par__prestamos.lote','par__prestamos.fechardesembolso',
            'par__prestamos.idejecucion','par__prestamos.apro_conta','par__prestamos.idtransaccionD','par__prestamos.idestado','par__prestamos__estados.nombreestado',
            'par__prestamos.no_prestamo','par__monedas.tipocambio','par__productos.idproducto','socios.idsocio','par__productos.tasa',
            'par__prestamos.detalle_desembolso','par__monedas.nommoneda','par__prestamos.idprestamo',$tipo,'par__productos.nomproducto',
            'par_grados.nomgrado','socios.nombre','socios.apaterno','socios.amaterno', 'par__prestamos.monto','par__prestamos.plazo',
            'par__prestamos.fecharegistro','par__prestamos.idoperario','par__prestamos.idusuario','par__productos.garantes')
            ->join ('par__productos','par__prestamos.idproducto','=','par__productos.idproducto')
            ->join ('par__prestamos__estados','par__prestamos.idestado','=','par__prestamos__estados.idestado')
            ->join ('socios','par__prestamos.idsocio','=','socios.idsocio')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('par__prestamos__tipo__ejecucion','par__prestamos.idejecucion','=','par__prestamos__tipo__ejecucion.idejecucion')
            ->join ('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->leftJoin ('con__asientomaestros','par__prestamos.idasiento','=','con__asientomaestros.idasientomaestro')
             
            ->where('par__prestamos.idestado','=','3')  
            ->orderBy('socios.nombre', 'asc')->get();
        
        return ['moras' => $socios];
    }
    public function prestamosEstatus(Request $request)
    {  
      if (!$request->ajax()) return redirect('/'); 
        $tipo = DB::raw("par__prestamos__tipo__ejecucion.nombre as nombretipo");
        $ascii = DB::raw("(SELECT count(*) FROM par__prestamos__plans pplan where pplan.idprestamo=par__prestamos.idprestamo and pplan.idestado=10) as acsii");
        $socios=Par_Prestamos::select('par__productos.tasa',
        'par__prestamos.detalle_desembolso','par__monedas.nommoneda','par__prestamos.idprestamo',$tipo,$ascii,
        'par__productos.nomproducto','par_grados.nomgrado','socios.nombre','socios.apaterno','socios.amaterno',
        'par__prestamos.monto','par__prestamos.plazo','par__prestamos.fecharegistro','par__prestamos.idestado','par__prestamos.planPagosMap',
        'par__prestamos__estados.nombreestado','par__prestamos.cuota','par__prestamos.apro_conta')
            ->join ('par__productos','par__prestamos.idproducto','=','par__productos.idproducto')
            ->join ('socios','par__prestamos.idsocio','=','socios.idsocio')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('par__prestamos__estados','par__prestamos.idestado','=','par__prestamos__estados.idestado')
            ->join ('par__prestamos__tipo__ejecucion','par__prestamos.idejecucion','=','par__prestamos__tipo__ejecucion.idejecucion')
            ->join ('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
            ->whereBetween('par__prestamos.idestado',[1,6])
            ->where('par__prestamos.idsocio','=',$request->idsocio)
            ->orderBy('par__prestamos.idestado', 'asc')->paginate(5);
        
        return [
            'pagination' => [
                'total'        => $socios->total(),
                'current_page' => $socios->currentPage(),
                'per_page'     => $socios->perPage(),
                'last_page'    => $socios->lastPage(),
                'from'         => $socios->firstItem(),
                'to'           => $socios->lastItem(),
            ],
            'prestamos' => $socios
        ];
    }

    public function desembolso(Request $request)
    { 
       if (!$request->ajax()) return redirect('/'); 
       $productos = Par_Producto::select( 'par__productos.linea','par__productos.max_prestamos','par__productos.cancelarprestamos')
       ->where('par__productos.idproducto','=',$request->idpro)  
       ->get();
        
     
     $validadorcuota=0;
     
     if($productos[0]->max_prestamos==1){ 
        if($productos[0]->linea==0&&$productos[0]->cancelarprestamos==0){
             $validadorcuota=0;
        }elseif($productos[0]->linea==1&&$productos[0]->cancelarprestamos==0){
            $validadorcuota=1;
        }elseif($productos[0]->linea==0&&$productos[0]->cancelarprestamos==1){
            $validadorcuota=2;
        }else{
           $validadorcuota=10;// indica error de configuracion del producto
        }
     }else{
       
       if($productos[0]->linea==0&&$productos[0]->cancelarprestamos==0){
           $validadorcuota=3;
       }else{
           $validadorcuota=10;// indica error de configuracion del producto
       }
     }
     if($validadorcuota<5){
        
        $total=DB::select("select  refinanciar(?,?,?,?,?) as total", array($request->idsocio,$request->idpro,Auth::id(),$request->prestamo,$validadorcuota));

        $json=json_decode($total[0]->total,true);
        if(floatval($json['total'])>=0){
            return $json;
        }else{
            return response()->json([ 'mensaje' => '¡ Existe una variación con el saldo capital y el monto solicitado. !','mensaje_into' => 'Comunique al administrador del sistema parta verificación de datos.'], 500);
        }
        

     }else{
        return response()->json([ 'mensaje' => '¡ Error en la configuracion del producto !','mensaje_into' => 'Comunique al administrador del sistema'], 500);
     }
 }
 public function getprestamoRefi(Request $request)
    { 
      if (!$request->ajax()) return redirect('/');
        $salida=Par_Prestamos::select('par__prestamos.idprestamo')
           ->whereraw('par__prestamos.idprestamo != par__prestamos.idref')
           ->where('par__prestamos.idref','=',$request->id)
           ->get();  

      return ['refi'=>$salida];
    }
 public function reversionPrestamos(Request $request)
    { 
    if (!$request->ajax()) return redirect('/');
    
        Par_prestamos_plan::where('idprestamo',$request->idprestamo)
        ->where('idestado',13)
        ->where('pe','=',500)
        ->delete();

        Par_prestamos_plan::where('idprestamo',$request->idprestamo)
        ->where('idestado',13)
        ->where('pe','!=',500)
        ->update(['idestado'=>2]); 
        $prestamo = Par_Prestamos::findOrFail($request->idprestamo); 
        $prestamo->idejecucion = '1';   
        $prestamo->idestado = '2'; 
        $buscararray = explode("|",$prestamo->idrefaux);
        array_pop($buscararray);
        $prestamo->idref =end($buscararray); 
        $prestamo->idrefaux =implode("|", $buscararray); 
        $prestamo->save(); 

    }

    public function alta_garantes(Request $request)
    {  
        $total=DB::select("select * from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($request->id));
        $tasain=DB::select("select p.tasa from par__productos p, par__prestamos pp where p.idproducto=pp.idproducto and pp.idprestamo=?", array($request->id));
        $tasa=$tasain[0]->tasa;
        $tem =($tasa/12)/100; 
        $punterofechas=0;
        $sumatotal=0;
        $totaldias=0;
        $fp_base='';
        $pruebaarray=array();
        $mainMora=(DB::select("SELECT valor FROM configs WHERE codigo='MORA'"))[0]->valor;
        foreach($total as $valor)
               {  $punterofechas++;
                    if($valor->idestado==10){
                        $fp_base=$valor->fp;  
                        $diasnow=DB::select("SELECT day(getfecha()) as diasnow")[0]->diasnow;
                        if(($valor->di+$diasnow)>$mainMora){ 
                      
                            $sumatotal=DB::select("select sum(am) as suma from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($request->id));
                            $totaldias=($valor->di+$diasnow);
                            $interes=round(($sumatotal[0]->suma*$tem*$totaldias)/30,2);
                            $cargo=(($valor->am+$interes)*0.35)/100;
                            array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('di'=>$totaldias,'in'=>$interes,'cut'=>($valor->am+$interes+$cargo+$valor->indi),'car'=>$cargo,'cuota'=>($valor->am+$interes),'tipo'=>2,'idestado'=>2)));
                            $sumatotal=$valor->ca; 

                            $prestamo = Par_Prestamos::findOrFail($request->id);  
                            $prestamo->idestado = '3';  
                            $prestamo->save(); 

                            $idsocio=(DB::select("SELECT idsocio from par__prestamos where idprestamo=?", array($request->id)))[0]->idsocio;
                            $Socio = Socio::findOrFail($idsocio);  
                            $Socio->idestprestamos = '1';  
                            $Socio->save(); 

                        }else{
                            $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                            $sumatotal=DB::select("select sum(am) as suma from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($request->id));
                            $totaldias=($valor->di+$dias[0]->dias);
                            $interes=round(($sumatotal[0]->suma*$tem*$totaldias)/30,2);
                            $cargo=(($valor->am+$interes)*0.35)/100;
                            array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('di'=>$totaldias,'in'=>$interes,'cut'=>($valor->am+$interes+$cargo+$valor->indi),'car'=>$cargo,'cuota'=>($valor->am+$interes),'tipo'=>2,'idestado'=>2)));
                            $sumatotal=$valor->ca; 
                        }
                       
 
                    }else{
                       $fechain=DB::select("SELECT LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH) as fecha", array($fp_base,$punterofechas));
                       $dias=DB::select("SELECT DAY(LAST_DAY((STR_TO_DATE(?,'%Y-%m-%d')) + INTERVAL ? MONTH)) as dias", array($fp_base,$punterofechas));
                       $interes=round(($sumatotal*$tem*$dias[0]->dias)/30,2);
                       $cargo=(($valor->am+$interes)*0.35)/100;
                       array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('fp'=>$fechain[0]->fecha,'di'=>$dias[0]->dias,'in'=>$interes,'cut'=>($valor->am+$interes+$cargo+$valor->indi),'car'=>$cargo,'cuota'=>($valor->am+$interes),'tipo'=>2)));
                       $sumatotal=$valor->ca;
                    } 
               } 
              
               foreach($pruebaarray as $valor){  
                    $planpago = Par_prestamos_plan::findOrFail($valor["key"]); 
                    foreach($valor["value"] as $akey=>$avalue) { $planpago->$akey=$avalue; }
                    $planpago->save(); 
               } 
                
    } 

    public function start_refinanciamiento(Request $request){
       if (!$request->ajax()) return redirect('/');
        DB::beginTransaction();  
        try{     
                // http://localhost:8000/start_refinanciamiento?socio=534&idmodulo=2&idprestamoin=2&detalle=asdfasdfa&numdoc=00122015   
                //http://localhost:8000/start_refinanciamiento?socio=3463&idmodulo=3&idprestamoin=4&detalle=asdfasdfa&numdoc=P-PR-0000000004
                 $prestamos_refinanciar=DB::select("select pre.idprestamo,mo.tipocambio ,pro.cobranza_perfil_refi,pro.idproducto,pre.plazo,so.numpapeleta 
                from par__prestamos pre,par__productos pro,par__monedas mo,socios so 
                where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pro.cobranza_perfil_refi!=0
                and pre.idsocio=? and pre.idsocio=so.idsocio and pre.idestado between 2 and 3", array($request->socio));
                    $total_refinanciar=0;
                    $fecha=(DB::select("select fechaSistema as total  from par__fecha__sistemas where activo=1"))[0]->total; 
                foreach($prestamos_refinanciar as $prestamo){
                    $valores=json_decode(DB::select("select getmontoRefi2(?) as total", array($prestamo->idprestamo))[0]->total);
                        if($valores->datas->cuota<0){ 
                                throw new ModelNotFoundException("Tiene uno o mas prestamos no validados por contabilidad."); 
                        } elseif($valores->datas->cuota==0){
                        break;
                        }
                    ////////////////////////////////////----- obtiene el perfil de cobranza segun el producto del presamo------///////////////////////////////////////////////////
                  $perfilcobranza =$this->getperfil($prestamo->idproducto,$prestamo->cobranza_perfil_refi); 
                    ////////////////////////////////////////////////////////////////////////////////////////

                            $total_cuotas = $prestamo->plazo;   
                            $cuota =round($valores->datas->am+$valores->datas->in,2);
                            $cuotaf = $valores->datas->cuota;  
                            $capital = $valores->datas->am;
                            $intereses = $valores->datas->in;
                            $interes_diferido = $valores->values->indi;
 
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
                                        $sumacargos+=$cargoin;  
                                        array_push($cargoscobranza,['idperfilcuentadetalle'=>$perfil['idperfilcuentadetalle'],'cargo'=>$cargoin,'rev'=>(strpos($perfil['formulaphp'],'$total_cuotas')>0)?1:0]);
                                    }
                                }
                            /////////////////////////////////////////////////////////////////////////////////////////
                             $cuotaf = $valores->datas->cuota+$sumacargos;
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
                                        $value=eval("return round(".$abc.",2);");
                                        $value=round($value*$prestamo->tipocambio,2);  
                                        if($value>0){ 
                                            if($perfil['tipocargo']=='h'){
                                                $haber+=$value;
                                                $value*= -1;
                                            }else{
                                                $debe+=$value;
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
                                                    $haber+=$value;
                                                    $value*= -1;
                                                }else{
                                                    $debe+=$value;
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
                                    
                       if($debe!=$haber){ 
                                throw new ModelNotFoundException("Existe una variacion de valores en el asiento contable al momento de realizar la cobranza de prestamos, comuniquese con el administrador del sistema."); 
                        }          
                       // dd($arrayDetalle);
                    ///////////////////////////////////////////////////////////////////////////////////////////
                    $cuotafinal=round($cuotaf*$prestamo->tipocambio,2); 
                    $total_refinanciar+=$cuotafinal; 
                                        $prestamo_plan = new Par_prestamos_plan(); 
                                            $prestamo_plan->idprestamo=$prestamo->idprestamo;
                                            $prestamo_plan->pe=500; 
                                            $prestamo_plan->fp=$fecha; 
                                            $prestamo_plan->di=$valores->values->dias; 
                                            $prestamo_plan->am=$valores->datas->am; 
                                            $prestamo_plan->in=$valores->datas->in; 
                                            $prestamo_plan->indi=$valores->values->indi; 
                                            $prestamo_plan->car=$sumacargos; 
                                            $prestamo_plan->cut=$cuotaf; 
                                            $prestamo_plan->ca=0;
                                            $prestamo_plan->ca_an=$valores->datas->am; 
                                            $prestamo_plan->cuota=$cuota; 
                                            $prestamo_plan->fecharegistro=(DB::select("select getfecha() as total"))[0]->total;   
                                            $prestamo_plan->tipo=3;
                                            $prestamo_plan->idestado=13;
                                            $prestamo_plan->idtransaccionC="R-".$request->idprestamoin."-".$prestamo->idprestamo;
                                            $prestamo_plan->numDocC=(DB::select("SELECT no_prestamo as total FROM par__prestamos WHERE idprestamo=?",array($request->idprestamoin)))[0]->total;
                                            $prestamo_plan->glosa='Refinanciamiento del prestamo - automatico'; 
                                            $prestamo_plan->fechaCobranza=(DB::select("select getfecha() as total"))[0]->total;  
                                            $prestamo_plan->tipocambio=$prestamo->tipocambio;
                                            $prestamo_plan->importe=$cuotafinal;
                                            $prestamo_plan->idusuario=Auth::id();
                                            $prestamo_plan->save();
                                            $planid=$prestamo_plan->idplan; 
                                                    foreach ($cargoscobranza as $val){  
                                                        $cargosadicionales = new Par_prestamos_plan_cargo();   
                                                        $cargosadicionales->idplan=$planid;
                                                        $cargosadicionales->idperfilcuentadetalle=$val['idperfilcuentadetalle'];
                                                        $cargosadicionales->cargo=$val['cargo'];    
                                                        $cargosadicionales->rev=$val['rev'];
                                                        $cargosadicionales->save(); 
                                                    }  
                    ///////////////////////////////////////////////////////////////////////////////////////////
                                  $fechaasiento=(DB::select("select getfecha() as total"))[0]->total; 
                                    $asientomaestro= new AsientoMaestroClass();
                                    $respuesta_perfil=$asientomaestro->AsientosMaestroArray($prestamo->cobranza_perfil_refi,
                                    '', 
                                    '',  
                                    'Cobranza del prestamo - Refinanciamiento - automatico', 
                                    $arrayDetalle,
                                    $request->idmodulo,$fechaasiento,'',$request->idprestamoin);
                             
                                    $prestamo_update_plan = Par_prestamos_plan::findOrFail($planid); 
                                    $prestamo_update_plan->idasiento =$respuesta_perfil;   
                                    $prestamo_update_plan->save(); 
                    ///////////////////////////////////////////////////////////////////////////////////////////
                     //actualiza el estado del prestamo a refinanciado
                  $prestamo_update = Par_Prestamos::findOrFail($prestamo->idprestamo);  
                     $prestamo_update->idejecucion =6;  
                     $prestamo_update->idestado=4;  
                     $prestamo_update->idrefaux= $prestamo_update->idrefaux."|".$request->idprestamoin;  
                     $prestamo_update->idref=$request->idprestamoin;  
                     $prestamo_update->save();

                     
             
                     DB::table('par__prestamos__plans')
                     ->where('idprestamo',$prestamo->idprestamo)
                     ->where('idestado',2)
                     ->orwhere('idestado',10)
                     ->update(['tipo' => 10,'idestado' => 13]); 
                    
                }
           
               
                


                /////////////////////////////////////////////////////// desembolso ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $product_perfil=(DB::select("SELECT pp.idproducto,pp.desembolso_perfil_refi,pre.plazo,pre.no_prestamo,pre.monto*mo.tipocambio as montotal, s.numpapeleta 
                from par__prestamos pre,par__productos pp,par__monedas mo,socios s 
                 where pre.idsocio=s.idsocio and pp.moneda=mo.idmoneda and pre.idproducto=pp.idproducto and pre.idprestamo=?",array($request->idprestamoin)))[0]; 
                 ///////////////////////////// clear ram ///////////////////////////////////////////////////////
                            for($i=65; $i<=90; $i++) {  
                                $abc="$".chr($i); 
                                eval($abc."=0;");
                            }
                 ////////////////////////////////////////////////////////////////////////////////////
                 $debe=0;
                 $haber=0;
                 $total_cuotas = $product_perfil->plazo;     
                 $monto = $product_perfil->montotal; 
                 $prestamos = $total_refinanciar; 
                 $arrayDesembolso = array(); 
                 $perfildesembolso =$this->getperfil($product_perfil->idproducto,$product_perfil->desembolso_perfil_refi); 
                                    foreach($perfildesembolso as $perfil){ 
                                        $abc="$".$perfil['valor_abc'];
                                        eval($abc."=".$perfil['formulaphp'].";");
                                        $value=eval("return round(".$abc.",2);"); 
                                        if($value>0){ 
                                            if($perfil['tipocargo']=='h'){
                                                $haber+=$value;
                                                $value*= -1;
                                            }else{
                                                $debe+=$value;
                                            }
                                                ///////////////////////// registrar el perfil con su valor en un array
                                                array_push($arrayDesembolso,array('idcuenta'=>$perfil['idcuenta'],
                                                'subcuenta'=>$product_perfil->numpapeleta, 
                                                'documento'=>$product_perfil->no_prestamo, 
                                                'moneda'=>'bs',	 
                                                'monto'=>$value
                                                ));     
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
                                                    array_push($arrayDesembolso,array('idcuenta'=>$perfil['idcuenta'],
                                                    'subcuenta'=>$product_perfil->numpapeleta, 
                                                    'documento'=>$product_perfil->no_prestamo, 
                                                    'moneda'=>'bs',	 
                                                    'monto'=>$value
                                                    ));
                                            }

                                        }
                                    }
                          
                                    if($debe!=$haber){ 
                                        throw new ModelNotFoundException("Existe una variacion de valores en el asiento contable al momento de realizar el desembolso del prestamo, comuniquese con el administrador del sistema."); 
                                       } 
                                    $fechaasiento=(DB::select("select getfecha() as total"))[0]->total;  
                                            $asientomaestro= new AsientoMaestroClass();
                                            $respuesta_perfil=$asientomaestro->AsientosMaestroArray($product_perfil->desembolso_perfil_refi,
                                            'lote', //lote
                                            $product_perfil->no_prestamo,  
                                            'Desembolso del prestamo'.($total_refinanciar>0?' - Refinanciamiento ':' ').'- automatico', 
                                            $arrayDesembolso,
                                            $request->idmodulo,$fechaasiento,$request->lote,$request->idprestamoin); 
                                    $prestamo_desembolso = Par_Prestamos::findOrFail($request->idprestamoin); 
                                    $prestamo_desembolso->idestado =2; 
                                    $total=DB::select("select codproducto as total from  par__productos where idproducto=?", array($prestamo_desembolso->idproducto)); 
                                    $prestamo_desembolso->idtransaccionD = 'D-'.strtoupper($total[0]->total).'-'.str_pad($prestamo_desembolso->idprestamo, 10, "0", STR_PAD_LEFT);
                                    $prestamo_desembolso->detalle_desembolso = $request->detalle;
                                    $prestamo_desembolso->numDocD = $request->numdoc; 
                                    $prestamo_desembolso->lote = 0; 
                                    $prestamo_desembolso->fechardesembolso = $fechaasiento; 
                                    $prestamo_desembolso->idasiento = $respuesta_perfil; 
                                    $prestamo_desembolso->idusuario=Auth::id();   
                                    $prestamo_desembolso->save();
                             

                ////////////////////////////////////////////////////////// fin desembolso//////////////////////////////////////////////////////////////////////// 
 

                $tipocambioout=(DB::select("select mo.tipocambio as tipocambioout from par__productos pro,par__monedas mo ,par__prestamos pres  where 
                 pro.moneda=mo.idmoneda   and pro.idproducto=pres.idproducto and  pres.idprestamo=?",array($request->idprestamoin)))[0]->tipocambioout;  
             DB::commit();
            return ['status'=>1,'total'=>round($total_refinanciar/$tipocambioout,2)];
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
    'con__perfilcuentadetalles.tipocargo',
    'con__perfilcuentadetalles.idcuenta',
    'par__productos__perfilcuentas.iscargo')
    ->where('par__productos__perfilcuentas.activo','=','1')  
    ->where('par__productos__perfilcuentas.idproducto','=',$producto)
    ->where('par__productos__perfilcuentas.idperfilcuentamaestro','=',$idperfil)
    ->orderBy('par__productos__perfilcuentas.valor_abc', 'asc')
    ->get()->toArray();
}
    public function prueba(Request $request)
    { 
       
        // $productos_array = array();
        // $productos=DB::select('select idproducto from par__productos where activo=1'); 
        // foreach($productos as $valor){
        //  $formulascobranza = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil','=','par__productos__perfilcuentas.idperfilcuentamaestro')
        // ->join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
        // ->select( 'par__productos.linea','par__productos.cobranza_perfil','par__productos__perfilcuentas.idperfilcuentadetalle',
        // 'par__productos__perfilcuentas.valor_abc',
        // 'par__productos__perfilcuentas.formula',
        // 'con__perfilcuentadetalles.tipocargo',
        // 'con__perfilcuentadetalles.idcuenta',
        // 'par__productos__perfilcuentas.iscargo')
        // ->where('par__productos__perfilcuentas.activo','=','1')
        // ->where('par__productos.idproducto','=',$valor->idproducto)
        // ->get()->toArray();
        // $productos_array[$valor->idproducto] =$formulascobranza;
        // }
        // return  $productos_array;

        // $tipo = DB::raw("par__prestamos__tipo__ejecucion.nombre as nombretipo");
        // $socios=Par_Prestamos::select('con__asientomaestros.observaciones','par__prestamos.fecharde_apro_conta','par__prestamos.idasiento',
        // 'socios.numpapeleta','par__productos.cancelarprestamos','par__prestamos.lote','par__prestamos.fechardesembolso',
        // 'par__prestamos.idejecucion','par__prestamos.apro_conta','par__prestamos.idtransaccionD','par__prestamos.idestado',
        // 'par__prestamos__estados.nombreestado',
        // 'par__prestamos.no_prestamo','par__monedas.tipocambio','par__productos.idproducto','socios.idsocio','par__productos.tasa',
        // 'par__prestamos.detalle_desembolso','par__monedas.nommoneda','par__prestamos.idprestamo',$tipo,'par__productos.nomproducto',
        // 'par_grados.nomgrado','socios.nombre','socios.apaterno','socios.amaterno', 'par__prestamos.monto','par__prestamos.plazo',
        // 'par__prestamos.fecharegistro','par__prestamos.idoperario','par__prestamos.idusuario','par__productos.garantes')
        // ->join ('par__productos','par__prestamos.idproducto','=','par__productos.idproducto')
        // ->join ('par__prestamos__estados','par__prestamos.idestado','=','par__prestamos__estados.idestado')
        // ->join ('socios','par__prestamos.idsocio','=','socios.idsocio')
        // ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
        // ->join ('par__prestamos__tipo__ejecucion','par__prestamos.idejecucion','=','par__prestamos__tipo__ejecucion.idejecucion')
        // ->join ('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
        // ->leftJoin ('con__asientomaestros','par__prestamos.idasiento','=','con__asientomaestros.idasientomaestro')
       
        // ->where('par__prestamos.apro_conta','!=','1') 
        // ->where('par__prestamos.apro_conta','!=','2') 

        // ->where('par__prestamos.idoperario','=',Auth::id()) 
        // ->whereBetween('par__prestamos.idestado',[1,2])
        // ->orderBy('par__prestamos.apro_conta', 'DESC')
        // ->orderBy('par__prestamos.idestado', 'asc')
        // ->orderBy('socios.nombre', 'asc')->toSql();

        //         return  $socios;

        // $formula=eval("return round($formula,2);");
                        // $formulascobranza = Par_productos_perfilcuenta::join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
                        //  ->where('par__productos__perfilcuentas.activo','=','1')
                        // ->where('par__productos__perfilcuentas.idperfilcuentamaestro','=','6')
                        // ->orderBy('par__productos__perfilcuentas.valor_abc', 'asc')
                        // ->get()->toArray();

                        // $cuotaf=1000;
                        // $capital=800;
                        // $intereses=200;

                        // $cuota=250;
                        // $total_cuotas=10;

                        // foreach($formulascobranza as $valor){ 
                        //      $abc="$".$valor['valor_abc'];
                        //      eval($abc."=".$valor['formulaphp'].";");
                        // }
                
                        // foreach($formulascobranza as $valor){  
                        //     echo $valor['valor_abc']."=".eval("return $".$valor['valor_abc'].";")."<br>";
                        // }
        // $suma_total=5;
        // $variable='$A';
        //  eval($variable."=$suma_total;");
        //  eval("\$Ar2=$A+3;");
        //  eval("\$B=($Ar2*2)/100+$A;");
        for($i=65; $i<=90; $i++) {  
            $abc="$".chr($i); 
            eval($abc."=0;");
        }

        for($i=65; $i<=90; $i++) {  
            $abc="$".chr($i);  
            echo $abc.'='.eval("return ".$abc.";").'    ';
        }
    }
     
}
