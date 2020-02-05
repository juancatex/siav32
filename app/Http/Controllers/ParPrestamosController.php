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
use App\Par_Producto;
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
        $fecha=(DB::select("select getfecha() as total"))[0]->total; 
        $cargosin=json_decode($request->cargos,true);
        $arrayDetalle=[];
        foreach ($cargosin as $clave => $val){  
         array_push($arrayDetalle,array('idcuenta'=>$val['id'],
                                  'subcuenta'=>$val['num'], 
                                  'documento'=>$request->numdoc, 
                                  'moneda'=>'bs',	 
                                  'monto'=>$val['value']
                                  )); 
        }


        $asientomaestro= new AsientoMaestroClass();
        $respuesta_perfil=$asientomaestro->AsientosMaestroArray(
        $request->perfilmaestro,
        'lote', //lote
        $request->numdoc,  //numero de lote lote
        $request->detalle, 
        $arrayDetalle,
        $request->idmodulo,
        $fecha,
        $request->lote,
        $request->idp);
 
        

        $prestamo = Par_Prestamos::findOrFail($request->idp); 
        $prestamo->idestado = '2'; 
        $total=DB::select("select codproducto as total from  par__productos where idproducto=?", array($prestamo->idproducto));
        // $prestamo->idtransaccionD = strtoupper($total[0]->total).'-'.$prestamo->idprestamo.'-'.date("ymd-His");
        $prestamo->idtransaccionD = 'D-'.strtoupper($total[0]->total).'-'.str_pad($prestamo->idprestamo, 10, "0", STR_PAD_LEFT);
        $prestamo->cuota = $request->cuota;
        $prestamo->detalle_desembolso = $request->detalle;
        $prestamo->numDocD = $request->numdoc; 
        $prestamo->lote = $request->lote; 
        $prestamo->fechardesembolso = $fecha; 
        $prestamo->idasiento = $respuesta_perfil; 
        $prestamo->idusuario=Auth::id();   
        $prestamo->save();
 
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
        // $sum = DB::raw("sum(cuota) as total"); 
        // $total=Par_Prestamos::select($sum) 
        // ->where('idsocio','=',$request->idsocio)
        // ->whereBetween('idestado',[2,3])->get();  

        $total=DB::select("select getPrestamos(?,?,?) as total", array($request->idsocio,$request->pro,$request->valor));
        //$total=(DB::select("select sum(cuota) as total FROM par__prestamos WHERE  (fecharegistro BETWEEN getfecha() AND ? )and idsocio=? and idestado=3", array($request->fechapapeleta,$request->idsocio)))[0]->total;
        
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
            'par__monedas.nommoneda','par__prestamos.idprestamo',$tipo,'par__productos.nomproducto','par__productos.cancelarprestamos',
            'par_grados.nomgrado','socios.nombre','socios.apaterno','socios.amaterno', 'par__prestamos.monto','par__prestamos.plazo'
            ,'par__prestamos.fecharegistro','par__prestamos.idoperario','par__prestamos.idusuario','par__productos.garantes')
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
            ->orderBy('par__prestamos.apro_conta', 'DESC')
            ->orderBy('par__prestamos.idestado', 'asc')
            ->orderBy('socios.nombre', 'asc')->paginate(10);
        }
        else{	 
            $socios=Par_Prestamos::select('con__asientomaestros.observaciones','par__prestamos.fecharde_apro_conta','par__prestamos.idasiento',
            'socios.numpapeleta','par__productos.cancelarprestamos','par__prestamos.lote','par__prestamos.fechardesembolso',
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
           
            ->where('par__prestamos.apro_conta','!=','1') 
            ->where('par__prestamos.apro_conta','!=','2') 

            ->where('par__prestamos.idoperario','=',Auth::id()) 
            ->whereBetween('par__prestamos.idestado',[1,2])
            ->orderBy('par__prestamos.apro_conta', 'DESC')
            ->orderBy('par__prestamos.idestado', 'asc')
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
        'par__prestamos.monto','par__prestamos.plazo','par__prestamos.fecharegistro','par__prestamos.idestado',
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
    public function prueba(Request $request)
    { 
       
        $productos_array = array();
        $productos=DB::select('select idproducto from par__productos where activo=1'); 
        foreach($productos as $valor){
         $formulascobranza = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil','=','par__productos__perfilcuentas.idperfilcuentamaestro')
        ->join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
        ->select( 'par__productos.linea','par__productos.cobranza_perfil','par__productos__perfilcuentas.idperfilcuentadetalle',
        'par__productos__perfilcuentas.valor_abc',
        'par__productos__perfilcuentas.formula',
        'con__perfilcuentadetalles.tipocargo',
        'con__perfilcuentadetalles.idcuenta',
        'par__productos__perfilcuentas.iscargo')
        ->where('par__productos__perfilcuentas.activo','=','1')
        ->where('par__productos.idproducto','=',$valor->idproducto)
        ->get()->toArray();
        $productos_array[$valor->idproducto] =$formulascobranza;
        }
        return  $productos_array;
    }
     
}
