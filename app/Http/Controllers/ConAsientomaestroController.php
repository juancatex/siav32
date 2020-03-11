<?php

namespace App\Http\Controllers;

use App\Con_Asientomaestro;
use App\Con_Asientodetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Apo_Aporte;
use App\Socio;
use App\AsinalssClass\AsientoMaestroClass;
use Illuminate\Support\Facades\Auth;
use App\Con_Cuentas;
use App\con__librocompra;
use App\Con_Configuracion;
use App\Config;
use App\Glo_SolicitudCargoCuenta;
use App\Con_Perfilcuentadetalle;
use App\Con__Movimientobancario;
use App\Apo_Tipoaporte;
use App\Apo_TotalAporte;
use App\Apo_Idsaporte;
use App\Apo_Excedenteaporte;

//agregar mas tablas de subcuentas para la seleccion


class ConAsientomaestroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        /*$buscar = $request->buscar;
        $criterio = $request->criterio;*/
        $idmodulo=$request->idmodulo;
        $buscar=$request->buscar;
        $criterio=$request->criterio;
        $borradorcheck=$request->borradorcheck;
        $perfilcuentadetalles='';
        //dd($request);
        //dd($buscar);
        //DB::statement("SET lc_time_names = 'es_ES'");
        //$rawtime=DB::raw("concat(upper(left(Date_format(fecharegistro,'%M'),1)),substr(left(Date_format(fecharegistro,'%M'),3),2),'-',cont_comprobante) AS numcomprobante");
        //$rawtime=DB::raw("concat(left(Date_format(fecharegistro,'%M'),3),'-',cont_comprobante) AS numcomprobante");
        //CONCAT(UPPER(LEFT(@frase,1)),SUBSTR(@frase,2))
        if(empty($buscar))
        {
            if(!empty($idmodulo))
            {
                if(empty($request->idperfil))
                {
                    //echo "entra empty. $request->idfilial";
                    $asientomaestros = Con_Asientomaestro::leftjoin('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                    ->join('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                    ->where(function($query) {
                                                        $query->where('con__asientomaestros.estado', 0)
                                                            ->orWhere('con__asientomaestros.estado', 3);
                                                    })
                                                    ->where('con__asientomaestros.idmodulo', '=', $idmodulo)
                                                    ->where('con__asientomaestros.gestion',0)
                                                    //->where('','=',0)
                                                    //->orWhere('con__asientomaestros.estado','=',3)
                                                    ->select('con__asientomaestros.idasientomaestro',
                                                                'con__asientomaestros.idtipocomprobante',
                                                                'cont_comprobante',
                                                                'con__asientomaestros.idperfilcuentamaestro',
                                                                'fecharegistro',
                                                                'tipodocumento',
                                                                'numdocumento',
                                                                'glosa',
                                                                'idfilial',
                                                                'con__asientomaestros.idmodulo',
                                                                'estado',
                                                                'nomtipocomprobante',
                                                                'nomperfil',
                                                                'observaciones',
                                                                'fact_modificada',
                                                                'cod_comprobante'
                                                                )
                                                    ->orderBy('fecharegistro', 'asc')
                                                    ->orderBy('cont_comprobante','desc')->paginate(50);
                }
                else
                {
                    $raw=DB::raw('sum(debe) as sdebe,sum(haber) as shaber');
                    $asientomaestros = Con_Asientomaestro::leftjoin('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                    ->join('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                    ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                    ->where(function($query) {
                                                        $query->where('con__asientomaestros.estado', 0)
                                                            ->orWhere('con__asientomaestros.estado', 3);
                                                    })
                                                    ->where('con__asientomaestros.idmodulo', '=', $idmodulo)
                                                    ->where('con__asientomaestros.idperfilcuentamaestro',$request->idperfil)
                                                    ->where('con__asientomaestros.gestion',0)
                                                    ->select('con__asientomaestros.idasientomaestro',
                                                                'con__asientomaestros.idtipocomprobante',
                                                                'cont_comprobante',
                                                                'con__asientomaestros.idperfilcuentamaestro',
                                                                'fecharegistro',
                                                                'tipodocumento',
                                                                'numdocumento',
                                                                'glosa',
                                                                'idfilial',
                                                                'con__asientomaestros.idmodulo',
                                                                'con__asientomaestros.estado',
                                                                'nomtipocomprobante',
                                                                'nomperfil',
                                                                'observaciones',
                                                                'fact_modificada',
                                                                'cod_comprobante',
                                                                'desembolso',
                                                                $raw
                                                                )
                                                    ->orderBy('fecharegistro', 'desc')
                                                    ->orderBy('cont_comprobante','desc')
                                                    ->groupBy('con__asientomaestros.idasientomaestro')->paginate(50);

                    $perfilcuentadetalles = Con_Perfilcuentadetalle::join('con__cuentas','con__cuentas.idcuenta','=','con__perfilcuentadetalles.idcuenta')
                                                    ->select('con__cuentas.nomcuenta','con__cuentas.codcuenta','con__cuentas.idcuenta','tipocargo','idperfilcuentadetalle')
                                                    ->where('idperfilcuentamaestro','=',$request->idperfil)
                                                    ->orderBy('tipocargo', 'asc')->get();
                }
            }
            else 
            {
                if($borradorcheck=='validado')
                {
                    //echo "entra buscar vacio";
                    $raw=DB::raw('round(sum(con__asientodetalles.monto),2) as total');
                    $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                        ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')  
                                                        ->leftjoin('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                        ->leftjoin('par__modulos','con__asientomaestros.idmodulo','=','par__modulos.idmodulo')
                                                        ->leftjoin('glo__solicitud_cargo_cuentas','glo__solicitud_cargo_cuentas.idasientomaestro','=','con__asientomaestros.idasientomaestro')
                                                        ->where(function($query) {
                                                            $query->where('con__asientomaestros.estado',1)
                                                                ->orWhere('con__asientomaestros.estado',4);
                                                        })
                                                        ->where('con__asientomaestros.idagrupacion',null)
                                                        ->where('con__asientodetalles.monto','>',0)
                                                        ->where('con__asientomaestros.idtipocomprobante','=',$criterio)
                                                        ->where('con__asientomaestros.gestion',0)
                                                        ->select('con__asientomaestros.idasientomaestro',
                                                                    'con__asientomaestros.idtipocomprobante',
                                                                    'cont_comprobante',
                                                                    'con__asientomaestros.idperfilcuentamaestro',
                                                                    'con__perfilcuentamaestros.nomperfil',
                                                                    'fecharegistro',
                                                                    'tipodocumento',
                                                                    'numdocumento',
                                                                    'con__asientomaestros.glosa',
                                                                    'idfilial',
                                                                    'con__asientomaestros.idmodulo',
                                                                    'par__modulos.nommodulo',
                                                                    'estado',
                                                                    'nomtipocomprobante',
                                                                    'nomperfil',
                                                                    'idrevertido',
                                                                    'fact_modificada',
                                                                    'cod_comprobante',
                                                                    $raw,
                                                                    'glo__solicitud_cargo_cuentas.idsolccuenta'
                                                                    )
                                                        ->orderBy('fecharegistro', 'desc')
                                                        ->orderBy('cont_comprobante','desc')
                                                        ->groupBy('con__asientomaestros.idasientomaestro')
                                                        ->limit(50)->paginate(15);
                }
                elseif ($borradorcheck=='borrador')
                {
                    $borrador=5;
                    $raw=DB::raw('round(sum(con__asientodetalles.monto),2) as total');
                    $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                            ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                        ->leftjoin('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                        ->leftjoin('par__modulos','con__asientomaestros.idmodulo','=','par__modulos.idmodulo')
                                                        ->where('con__asientomaestros.estado', $borrador)
                                                        ->where('con__asientomaestros.idtipocomprobante','=',$criterio)
                                                        ->where('con__asientodetalles.monto','>',0)
                                                        ->where('con__asientomaestros.gestion',0)
                                                        ->select('con__asientomaestros.idasientomaestro',
                                                                    'con__asientomaestros.idtipocomprobante',
                                                                    'cont_comprobante',
                                                                    'con__asientomaestros.idperfilcuentamaestro',
                                                                    'con__perfilcuentamaestros.nomperfil',
                                                                    'fecharegistro',
                                                                    'tipodocumento',
                                                                    'numdocumento',
                                                                    'glosa',
                                                                    'idfilial',
                                                                    'con__asientomaestros.idmodulo',
                                                                    'par__modulos.nommodulo',
                                                                    'estado',
                                                                    'nomtipocomprobante',
                                                                    'nomperfil',
                                                                    'fact_modificada',
                                                                    $raw,
                                                                    'cod_comprobante'
                                                                    )
                                                                    ->groupBy('con__asientomaestros.idasientomaestro')
                                                        ->orderBy('fecharegistro', 'desc')
                                                        ->orderBy('cont_comprobante','desc')->limit(50)->paginate(15);
                    
                }
            }
        }
        else 
        {
            //echo "entra buscar else";
            if($borradorcheck=='validado')
            {
                $raw=DB::raw('round(sum(con__asientodetalles.monto),2) as total');
                $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                    ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                    ->leftjoin('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                    ->leftjoin('par__modulos','con__asientomaestros.idmodulo','=','par__modulos.idmodulo')                                                    
                                                    ->leftjoin('glo__solicitud_cargo_cuentas','glo__solicitud_cargo_cuentas.idasientomaestro','=','con__asientomaestros.idasientomaestro')
                                                    ->where(function($query) {
                                                        $query->where('con__asientomaestros.estado',1)
                                                            ->orWhere('con__asientomaestros.estado',4);
                                                    })
                                                    ->where('con__asientomaestros.idagrupacion',null)
                                                    ->where('con__asientomaestros.idtipocomprobante','=',$criterio)
                                                    ->where('con__asientodetalles.monto','>',0)
                                                    ->where('con__asientomaestros.gestion',0)
                                                    ->where(function($query) use ($buscar){
                                                        $query->where('con__asientomaestros.cont_comprobante','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.tipodocumento','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.numdocumento','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.glosa','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.cod_comprobante','like','%'.$buscar.'%');
                                                    })
                                                    ->select('con__asientomaestros.idasientomaestro',
                                                                'con__asientomaestros.idtipocomprobante',
                                                                'cont_comprobante',
                                                                'con__asientomaestros.idperfilcuentamaestro',
                                                                'con__perfilcuentamaestros.nomperfil',
                                                                'fecharegistro',
                                                                'tipodocumento',
                                                                'numdocumento',
                                                                'con__asientomaestros.glosa',
                                                                'idfilial',
                                                                'con__asientomaestros.idmodulo',
                                                                'par__modulos.nommodulo',
                                                                'estado',
                                                                'nomtipocomprobante',
                                                                'nomperfil',
                                                                'idrevertido',
                                                                'fact_modificada',
                                                                'cod_comprobante',
                                                                $raw,
                                                                'glo__solicitud_cargo_cuentas.idsolccuenta'
                                                                )
                                                    ->groupBy('con__asientomaestros.idasientomaestro')
                                                    ->orderBy('fecharegistro', 'desc')
                                                    ->orderBy('cont_comprobante','desc')->limit(50)->paginate(15);
            }
            elseif ($borradorcheck=='borrador')
            {
                $borrador=5;
                $raw=DB::raw('round(sum(con__asientodetalles.monto),2) as total');
                $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                        ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                    ->leftjoin('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                    ->leftjoin('par__modulos','con__asientomaestros.idmodulo','=','par__modulos.idmodulo')                                                    
                                                    ->where('con__asientomaestros.estado', $borrador)
                                                    ->where('con__asientomaestros.idtipocomprobante','=',$criterio)
                                                    ->where('con__asientodetalles.monto','>',0)
                                                    ->where('con__asientomaestros.gestion',0)
                                                    ->where(function($query) use ($buscar){
                                                        $query->where('con__asientomaestros.cont_comprobante','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.tipodocumento','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.numdocumento','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.glosa','like','%'.$buscar.'%')
                                                        ->orwhere('con__asientomaestros.cod_comprobante','like','%'.$buscar.'%');
                                                    })
                                                    ->select('con__asientomaestros.idasientomaestro',
                                                                'con__asientomaestros.idtipocomprobante',
                                                                'cont_comprobante',
                                                                'con__asientomaestros.idperfilcuentamaestro',
                                                                'con__perfilcuentamaestros.nomperfil',
                                                                'fecharegistro',
                                                                'tipodocumento',
                                                                'numdocumento',
                                                                'glosa',
                                                                'idfilial',
                                                                'con__asientomaestros.idmodulo',
                                                                'par__modulos.nommodulo',
                                                                'estado',
                                                                'nomtipocomprobante',
                                                                'nomperfil',
                                                                'fact_modificada',
                                                                $raw,
                                                                'cod_comprobante'
                                                                )
                                                                ->groupBy('con__asientomaestros.idasientomaestro')
                                                    ->orderBy('fecharegistro', 'desc')
                                                    ->orderBy('cont_comprobante','desc')->limit(50)->paginate(15);
                
            }
        }
        
        return [
            'pagination' => [
                'total'        => $asientomaestros->total(),
                'current_page' => $asientomaestros->currentPage(),
                'per_page'     => $asientomaestros->perPage(),
                'last_page'    => $asientomaestros->lastPage(),
                'from'         => $asientomaestros->firstItem(),
                'to'           => $asientomaestros->lastItem(),
            ],
            'asientomaestros' => $asientomaestros,
            'perfildetalle'=>$perfilcuentadetalles
        ];
    }


    public function getasientosmaestros_automatico(Request $request)
    {
      if (!$request->ajax()) return redirect('/');
 
        $idmodulo=$request->idmodulo;  
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        $sqlss=''; 
        if (sizeof($buscararray)>0){
           
            
            if(sizeof($buscararray)==1){
                $valor2=$request->buscar; 
                $sqlss="(".(is_numeric($valor2)?"con__asientomaestros.loteprestamos =".$valor2." or con__asientomaestros.cont_comprobante =".$valor2." or ":"")."con__asientomaestros.glosa like '%".$valor2."%'  or con__asientomaestros.fecharegistro like '".$valor2."%' or con__asientomaestros.tipodocumento = '".$valor2."' or con__asientomaestros.numdocumento = '".$valor2."' or con__asientomaestros.cod_comprobante = '".$valor2."')";
           
            }else{
                foreach($buscararray as $valor){
                    if(empty($sqlss)){
                        $sqlss="(con__asientomaestros.fecharegistro like '%".$valor."%' or con__asientomaestros.loteprestamos like '%".$valor."%' or con__asientomaestros.cont_comprobante like '%".$valor."%' or con__asientomaestros.tipodocumento like '%".$valor."%' or con__asientomaestros.numdocumento like '%".$valor."%' or con__asientomaestros.glosa like '%".$valor."%'  or con__asientomaestros.cod_comprobante like '%".$valor."%')";
                    }else{
                        $sqlss.=" and (con__asientomaestros.fecharegistro like '%".$valor."%' or con__asientomaestros.loteprestamos like '%".$valor."%' or con__asientomaestros.cont_comprobante like '%".$valor."%' or con__asientomaestros.tipodocumento like '%".$valor."%' or con__asientomaestros.numdocumento like '%".$valor."%' or con__asientomaestros.glosa like '%".$valor."%'  or con__asientomaestros.cod_comprobante like '%".$valor."%')";
                    } 
     
                } 
            }
        }
     
        $raw=DB::raw('sum(debe) as sdebe,sum(haber) as shaber');
                    $asientomaestros = Con_Asientomaestro::leftjoin('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                    ->join('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                    ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                    ->where(function($query) {
                                                        $query->where('con__asientomaestros.estado', 0)
                                                            ->orWhere('con__asientomaestros.estado', 3);
                                                    }) 
                                                    ->where('con__asientomaestros.idmodulo', '=', $idmodulo)
                                                    ->where('con__asientomaestros.idperfilcuentamaestro',$request->idperfil)
                                                    ->where('con__asientomaestros.gestion',0)
                                                    //->where('con__asientomaestros.desembolso', '=','1')
                                                    ->select('con__asientomaestros.idasientomaestro',
                                                                'con__asientomaestros.loteprestamos',
                                                                'con__asientomaestros.idtipocomprobante',
                                                                'cont_comprobante',
                                                                'con__asientomaestros.idperfilcuentamaestro',
                                                                'fecharegistro',
                                                                'tipodocumento',
                                                                'numdocumento',
                                                                'glosa',
                                                                'idfilial',
                                                                'con__asientomaestros.idmodulo',
                                                                'con__asientomaestros.estado',
                                                                'nomtipocomprobante',
                                                                'nomperfil',
                                                                'observaciones',
                                                                'fact_modificada',
                                                                'cod_comprobante',
                                                                'desembolso',
                                                                $raw
                                                            );
                                                if(!empty($sqlss)){
                                                    $asientomaestros= $asientomaestros->whereraw($sqlss);
                                                }
                                                      
                                                    $asientomaestros=$asientomaestros->orderBy('fecharegistro', 'desc')
                                                    ->orderBy('cont_comprobante','desc')
                                                    ->groupBy('con__asientomaestros.idasientomaestro')->paginate(50);
 
        return [
            'pagination' => [
                'total'        => $asientomaestros->total(),
                'current_page' => $asientomaestros->currentPage(),
                'per_page'     => $asientomaestros->perPage(),
                'last_page'    => $asientomaestros->lastPage(),
                'from'         => $asientomaestros->firstItem(),
                'to'           => $asientomaestros->lastItem(),
            ],
            'asientomaestros' => $asientomaestros 
        ];
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
        //$o = new AsientoMaestroClass('sheep'); 
        //$o = new AsientoMaestroClass('sheep','cat'); 
        //$o = new AsientoMaestroClass('sheep','cat','dog'); 
        //dd($request);
        //echo "entra store";
        $arrayDetalle = array();
        $idmodulo=$request->idmodulo;
        $rowregistros=$request->rowregistros;
        //dd($rowregistros);
        $fechatransaccion=$request->fechatransaccion;
        $idtipocomprobante=$request->idtipocomprobante;
        $tipodocumento=$request->tipodocumento;
        $numdocumento=$request->numdocumento;
        $glosa=$request->glosa;
        $idperfilcuentamaestro='';
        $borrador=$request->borrador;
        $librocomprasok=$request->librocomprasok;
        $idfacturas=$request->idfacturas;
        $validarfacturas=$request->validarfacturas;
        if($request->sidirectorio)
            $tiposubcuenta=1;
        else
            $tiposubcuenta=2;
        //dd($rowregistros);
        
        //dd($borrador);
        //echo "idmodulo". $idmodulo;
       // dd(count($rowregistros));
       // dd($rowregistros[0]['idcuenta'][0]);
        $cont=0;
        
        foreach ($rowregistros as $indice=>$valor)
        {
            $swd=0;
            $swh=0;
            //echo"{$valor['idcuenta']}";
            $arrayDetalle[$cont]['idcuenta']=$valor['idcuenta'];
            $arrayDetalle[$cont]['subcuenta']=$valor['idsubcuenta'];
            $arrayDetalle[$cont]['documento']=$valor['documento'];
            $arrayDetalle[$cont]['moneda']=$valor['moneda'];
            $arrayDetalle[$cont]['tiposubcuenta']=$tiposubcuenta;
            if($valor['debe']!=0)
                $arrayDetalle[$cont]['monto']=$valor['debe'];
            else
                $arrayDetalle[$cont]['monto']=$valor['haber']*-1;
            $cont++;
        }
        //dd($arrayDetalle);
        if($request->idasientomaestro)
        {
            //echo "entra idasientomaestro";
            
            DB::table('con__asientodetalles')->where('idasientomaestro', '=', $request->idasientomaestro)->delete();
            DB::table('con__asientomaestros')->where('idasientomaestro', '=', $request->idasientomaestro)->delete();

            $asientomaestro= new AsientoMaestroClass();
            $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador);
            
            if($idfacturas)
            {
                DB::table('con__librocompras')->where('lote', $request->reslote)->update(['idasientomaestro' => null,
                                                                                            'lote'=>null,
                                                                                            'validadoconta'=>0]);    
                foreach ($idfacturas as $indice => $valor) {
                    DB::table('con__librocompras')->where('idlibrocompra', $valor)->update(['idasientomaestro' => $request->idasientomaestro,
                                                                                                                    'lote'=>$request->reslote]);    
                }

                $librocompra = con__librocompra::select(DB::raw('max(lote) as maximo'))
                                                    ->where('idasientomaestro','<>',0)
                                                    ->get()->toArray();
                $lote=$librocompra[0]['maximo']+1;
                DB::table('con__librocompras')->where('idasientomaestro', $request->idasientomaestro)->update(['idasientomaestro' => $respuesta,
                                                                                                                 'lote'=>$lote,
                                                                                                                 'validadoconta'=>1]);    
            }
            else
            {
                if($request->reslote)
                {
                    DB::table('con__librocompras')->where('lote', $request->reslote)->update(['idasientomaestro' => null,
                                                                                                                 'lote'=>null,
                                                                                                                 'validadoconta'=>0]);   
                }
            }
        }
        else {
            if($idfacturas)
            {
                //echo "else idasientomaestro entra idfacturas2";
                $asientomaestro= new AsientoMaestroClass();
                $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador);
                
                
               /*  foreach ($idfacturas as $indice => $valor) {
                    DB::table('con__librocompras')->where('idlibrocompra', $valor)->update(['idasientomaestro' => $request->idasientomaestro,
                                                                                            'lote'=>$request->reslote]);    
                } */
                
                $librocompra = con__librocompra::select(DB::raw('max(lote) as maximo'))
                                                ->where('idasientomaestro','<>',0)
                                                ->get()->toArray();
                $lote=$librocompra[0]['maximo']+1;
                
                
                foreach ($idfacturas as $indice => $valor) {
                    DB::table('con__librocompras')->where('idlibrocompra', $valor)->update(['idasientomaestro' => $respuesta,
                                                                                            'lote'=>$lote,
                                                                                            'validadoconta'=>$validarfacturas]);    
                }
            }
            else
            {
                //echo "entra idfacturas else";
                $asientomaestro= new AsientoMaestroClass();
                $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador); 
                //echo "respueta".$respuesta;
            }
            if($request->idmovimiento!=0)
            {
                DB::table('con___movimientobancarios')->where('idmovimiento', $request->idmovimiento)->update(['idasientomaestro' => $respuesta]); 
            }
            
        }
        return $respuesta;
    }
    public function selectSocio2(Request $request){
        //if (!$request->ajax()) return redirect('/');
        $raw2=DB::raw('concat(numpapeleta," ",apaterno," ",amaterno," ",nombre) as mostrar');
        $busqueda=$request->busqueda;
        $socios = Socio::where('activo','=','1')
                        ->where('numpapeleta','like','%'.$busqueda.'%')->get();
        return response()->json($socios);
    }


    //TODO:ampliar la busqueda de subcuentas a empleados y empresas proveedoras.
    public function selectSubcuenta(Request $request)  
    {
        //if (!$request->ajax()) return redirect('/');
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $raw2=DB::raw('concat(numpapeleta," ",apaterno," ",amaterno," ",nombre) as mostrar');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(numpapeleta like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%')";
                }else{
                    $sqls.=" and (numpapeleta like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%')";
                } 
            }   
            $subcuentas = Socio::select('numpapeleta',$raw,$raw2)->orderBy('numpapeleta', 'desc')->whereraw($sqls)->get();
        }
        else {
            if ($request->id)
                $subcuentas = Socio::select('numpapeleta',$raw,$raw2)->where('idsocio','=',$request->id)->get();
            else
                $subcuentas = Socio::select('numpapeleta',$raw,$raw2)->orderby('apaterno','desc')
                                                                        ->orderBy('amaterno', 'desc')
                                                                        ->orderBy('nombre', 'desc')
                                                                        ->get();
        }
        
        
        return ['subcuentas' => $subcuentas];
        
        /* $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $raw2=DB::raw('concat(numpapeleta," ",apaterno," ",amaterno," ",nombre) as mostrar');
        $subcuentas = Socio::select('numpapeleta',$raw,$raw2)
        ->where('activo','=','1')
        ->orderBy('numpapeleta', 'asc')->get();

        return response()->json($subcuentas);
        //return ['subcuentas' => $subcuentas]; */
    }
    public function selectdesembolso(Request $request)  
    {
       if (!$request->ajax()) return redirect('/');
       $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        
        $idperfil = $request->idperfil;  
        $estadodesembolso=$request->estadodesembolso;

         
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nomsocio');
        $desembolsados = Con_Asientomaestro::join('par__prestamos','par__prestamos.idasiento','=','con__asientomaestros.idasientomaestro')
                                            ->join('con__cuentasocios','con__cuentasocios.idcuentasocio','=','par__prestamos.idcuentasocio')
                                            ->join('socios','socios.idsocio','=','par__prestamos.idsocio')
                                            ->select('con__asientomaestros.idasientomaestro',
                                                        'socios.numpapeleta',
                                                        'con__asientomaestros.fecharegistro',
                                                        'con__asientomaestros.numdocumento',
                                                        'con__asientomaestros.glosa',
                                                        $raw,
                                                        'par__prestamos.monto',
                                                        'numcuentasocio',
                                                        'idagrupacion',
                                                        'desembolso',
                                                        'fechahora_desembolso',
                                                        'con__asientomaestros.estado',
                                                        'par__prestamos.lote',
                                                        'con__asientomaestros.observaciones')
                                            ->where('idperfilcuentamaestro',$idperfil)
                                            ->where('con__asientomaestros.gestion',0)
                                            ->where('con__asientomaestros.desembolso',$estadodesembolso);
                                            
                                               
                                            if (sizeof($buscararray)>0){
                                                $sqls=''; 
                                                if(sizeof($buscararray)==1){
                                                    $valor = $request->buscar;
                                                    $sqls="(".(is_numeric($valor)?"par__prestamos.lote = ".$valor." or ":"")."socios.numpapeleta like '%".$valor."%' or socios.nombre ='".$valor."' or socios.apaterno ='".$valor."' or socios.amaterno ='".$valor."'  or con__asientomaestros.fecharegistro like '".$valor."%' )";
                                                    
                                                    
                                                }else{
                                                    foreach($buscararray as $valor){
                                                        if(empty($sqls)){
                                                            $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%'  or par__prestamos.lote = '".$valor."' or con__asientomaestros.fecharegistro like '".$valor."%' )";
                                                        }else{
                                                            $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%'  or par__prestamos.lote = '".$valor."' or con__asientomaestros.fecharegistro like '".$valor."%')";
                                                        } 
                                                    }
                                                }
                                                 

                                                if($estadodesembolso==0){
                                                    $desembolsados=$desembolsados->orderBy('con__asientomaestros.fecharegistro','desc')
                                                    ->whereraw($sqls)
                                                    ->orderBy('par__prestamos.lote','desc')
                                                    ->where(function($query) {
                                                        $query->where('con__asientomaestros.estado', 0)
                                                            ->orWhere('con__asientomaestros.estado', 3);
                                                    });
                                                } 
                                                else {
                                                    $desembolsados=$desembolsados->orderBy('con__asientomaestros.fechahora_desembolso','desc')
                                                    ->whereraw($sqls)
                                                    ->orderBy('par__prestamos.lote','desc')
                                                    ->limit(30);
                                                } 
                                            }else{
                                                if($estadodesembolso==0){
                                                    $desembolsados=$desembolsados->orderBy('con__asientomaestros.fecharegistro','desc')
                                                    ->orderBy('par__prestamos.lote','desc')
                                                    ->where(function($query) {
                                                        $query->where('con__asientomaestros.estado', 0)
                                                            ->orWhere('con__asientomaestros.estado', 3);
                                                    });
                                                } 
                                                else {
                                                    $desembolsados=$desembolsados->orderBy('con__asientomaestros.fechahora_desembolso','desc')
                                                    ->orderBy('par__prestamos.lote','desc')
                                                    ->limit(30);
                                                }
                                            }

                                            
        
        $cuentashaber=Con_Perfilcuentadetalle::select('idcuenta')
                                                ->where('idperfilcuentamaestro',$idperfil)
                                                ->where('tipocargo','h')
                                                ->get();

        
        
        return ['desembolsados' => $desembolsados->get(),
                'cuentashaber'=>$cuentashaber];
        
      
    }
    public function show(Con_Asientomaestro $con_Asientomaestro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Con_Asientomaestro  $con_Asientomaestro
     * @return \Illuminate\Http\Response
     */
    public function edit(Con_Asientomaestro $con_Asientomaestro)
    {
        //
    }
    public function editarcabecera (Request $request)
    {
        $asientomaestroclass= new AsientoMaestroClass();
        
        $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
        $asientomaestro->tipodocumento=$request->documento;
        $asientomaestro->numdocumento=$request->numdocumento;
        $asientomaestro->glosa= $request->glosa;
        $asientomaestro->u_modifica=Auth::id();

        $asientomaestro->save(); 

    }
    public function update(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        //$users = DB::table('users') ->select(DB::raw('count(*) as user_count, status')) ->where('status', '<>', 1) ->groupBy('status') ->get(); 
        /* $asientomaestros = Con_Asientomaestro::select(DB::raw('max(cont_comprobante) as maximo'))
                                                 ->where('idtipocomprobante','=',$request->idtipocomprobante)->get()->toArray();
        $cont_tipocomprobante=$asientomaestros[0]['maximo']+1;
        */



         $fechavalidacion = date("Y-m-d H:i:s");
        $fecharegistro=$request->fecharegistro;
        $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
        $idtipocomprobante=$asientomaestro->idtipocomprobante;
        $idmodulo=$asientomaestro->idmodulo;
        
        $asientomaestroclass= new AsientoMaestroClass();
        $respuesta=$asientomaestroclass->verificarfecha($fecharegistro,$idtipocomprobante);
        $asientomaestro->cont_comprobante=$respuesta[0];
        $asientomaestro->cod_comprobante=$respuesta[1];


        $asientomaestro->fecharegistro = $fecharegistro;
        $asientomaestro->fechavalidado=$fechavalidacion;
        $asientomaestro->tipodocumento = ucwords(strtolower($request->tipodocumento));
        $asientomaestro->numdocumento = ucwords(strtolower($request->numdocumento));
        $asientomaestro->glosa = ucwords(strtolower($request->glosa));
        $asientomaestro->estado = '1';
        $asientomaestro->u_obs_val=Auth::id();

        $asientomaestro->save(); 

        if($idmodulo==2) //TODO: es para verificar y validar los aportes para actualizar el total aportes
        {
            $this->validaraportes($request->idasientomaestro);
        }
        //DB::unprepared('CALL validadoconta_aportes('.$request->idasientomaestro.')');
    }
    public function recuperarSubcuenta(Request $request)
    {
        $idasientomaestro=$request->idasientomaestro;
        $sw=0;
        $cuentaslibros = Con_Configuracion::select('codigo','valor')
                                    ->where(function($query){
                                        $query->where('codigo','=','LV')
                                            ->orwhere('codigo','=','LC');
                                    })
                                    ->where('activo',1)
                                    ->get()
                                    ->toArray();
        
        //dd($cuentaslibros);
        foreach ($cuentaslibros as $valor) {
            if($valor['codigo']=='LV')
                $lv=$valor['valor'];
            else
            {
                if($valor['codigo']=='LC')
                    $lc=$valor['valor'];
            }
        }

        $asientodetalles=Con_Asientodetalle::select('idasientodetalle',
                                                    'idcuenta',
                                                    'monto',
                                                    'moneda',
                                                    'documento',
                                                    'subcuenta')
                                            ->where('idasientomaestro','=',$idasientomaestro)
                                            ->orderBy('monto','desc')->get()->toArray();
        $rowregistros=array();                                            
        $cont=0;
        foreach ($asientodetalles as $indice=>$valor)
        {   
            foreach ($valor as $i =>$v) 
            {
                if($i=='monto')
                {
                    //TODO: para mandar  valores debe haber en 0 comentar este if
                    if($request->tipo=='editar')                    
                    {
                        if($v>0)
                        {   
                            $rowregistros[$cont]['debe']=$v;
                            $rowregistros[$cont]['haber']="";
                        }
                        else
                        {   
                            $rowregistros[$cont]['haber']=($v*(-1));    
                            $rowregistros[$cont]['debe']="";
                        }   
                    }
                    elseif ($request->tipo=='copiar') 
                    {
                        $rowregistros[$cont]['debe']="";
                        $rowregistros[$cont]['haber']="";
                    }
                }
                if($i=='documento')
                    $rowregistros[$cont]['documento']=$v;   
                if($i=='moneda')
                    $rowregistros[$cont]['moneda']=$v; 
                if($i=='idcuenta')
                {
                    if($v==$lc && $request->tipo=='editar')
                        $sw=1;

                    $raw=DB::raw('concat(codcuenta, " ", nomcuenta) as cuentas');
                    $valorcuenta = Con_Cuentas::select('idcuenta',$raw,'codcuenta','nomcuenta')
                                                ->where('idcuenta','=',$v)
                    ->get()->toArray();
                    $arrayidcuenta=array();
                    foreach($valorcuenta as $indi=>$val)
                    {   foreach($val as $ic=>$vc)
                        {    
                            $arrayidcuenta[]=$vc;
                            //echo "indicecuenta=$ic, valorcuenta=$vc</br>";

                        }
                        //echo"------------------</br>";
                    }
                    $rowregistros[$cont]['idcuenta']=$arrayidcuenta; 
                }
                if($i='subcuenta')
                {
                    
                    $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
                    $raw2=DB::raw('concat(numpapeleta," ",apaterno," ",amaterno," ",nombre) as mostrar');
                    $subcuentas = Socio::select('numpapeleta',$raw,$raw2)
                                        ->where('activo','=','1')
                                        ->where('numpapeleta','=',$v)
                                        ->get()->toArray();
                    
                    $arraysubcuenta=array();
                    foreach($subcuentas as $indi=>$val)
                        foreach($val as $vs)
                            $arraysubcuenta[]=$vs;

                    $rowregistros[$cont]['idsubcuenta']=$arraysubcuenta; 
                }
            } 
            $cont++;
        } 
        if($sw==1)
        {
            //echo "entra sw==1";
            $raw1=DB::raw('sum(credfiscal) as suma13');
            $raw2=DB::raw('sum(restoimporte) as suma87');
            //$raw3=DB::raw('max(lote) as maxlote');
            $acumulados=con__librocompra::select($raw1,$raw2)
                                            ->where('idasientomaestro',$idasientomaestro)
                                            ->get()->toArray();
            
            
            $raw1=DB::raw('distinct(lote) as lote');
            $lote=con__librocompra::select($raw1)
                                        ->where('idasientomaestro',$idasientomaestro)
                                        ->get()->toArray();
            //dd($lote);
            
            return ['rowregistros'=>$rowregistros,
            'sw'=>$sw,
            'acumulados'=>$acumulados,
            'reslote'=>$lote];
            //dd($acumulados);
        }
        else {
            return ['rowregistros'=>$rowregistros,
                    'sw'=>$sw];
        }
       
       
    }
    public function revertir(Request $request)
    {
        $hora=date("H:i:s");
        $fecharegistro=$request->fechareversion." ".$hora;
        $glosareversion=$request->glosareversion;
        $idtipocomprobante=3;

        
        //dd($request);
        //if (!$request->ajax()) return redirect('/');
         ///tipo comprobante de ajuste para reversiones;
        
        $asientomaestro = Con_Asientomaestro::select('cont_comprobante','loteprestamos','nomtipocomprobante')
                                                ->join('con__tipocomprobantes','con__tipocomprobantes.idtipocomprobante','=','con__asientomaestros.idtipocomprobante')
                                                ->where('con__asientomaestros.idasientomaestro','=',$request->idasientomaestro)
                                                ->get()->toArray();            //findOrFail($request->idasientomaestro);
        
        $asientomaestros = Con_Asientomaestro::select(DB::raw('max(cont_comprobante) as maximo'))
                                                ->where('idtipocomprobante','=',$idtipocomprobante)
                                                ->where('con__asientomaestros.gestion',0)->get()->toArray();
        
        $asientodetalle=Con_Asientodetalle::select('idcuenta','monto','documento','subcuenta')
                                            ->join('con__asientomaestros','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                            ->where('con__asientodetalles.idasientomaestro','=',$request->idasientomaestro)
                                            ->get()
                                            ->toArray();

        //return $asientodetalle;
        //var_dump($asientodetalle);
        //dd($asientodetalle);

        //$fecharegistro = date("Y-m-d H:i:s");
        //$cont_tipocomprobante=$asientomaestros[0]['maximo']+1;
        
        if($glosareversion=='')
            $glosa="Reversión de Comprobante de ".$asientomaestro[0]['nomtipocomprobante']. " ".$asientomaestro[0]['cont_comprobante'];
        else
            $glosa=$glosareversion;

        $loteprestamos=$asientomaestro[0]['loteprestamos'];

        $asientomaestro=new Con_Asientomaestro();
        $asientomaestro->idtipocomprobante=$idtipocomprobante;
        $asientomaestro->fecharegistro=$fecharegistro;          

        $asientomaestroclass= new AsientoMaestroClass();
        $respuesta=$asientomaestroclass->verificarfecha($fecharegistro,$idtipocomprobante);
        $asientomaestro->cont_comprobante=$respuesta[0];
        $asientomaestro->cod_comprobante=$respuesta[1];

        $asientomaestro->tipodocumento='';
        $asientomaestro->numdocumento='';
        $asientomaestro->glosa=$glosa;
        $asientomaestro->idfilial='1';//modificar con ide de filial
        $asientomaestro->idmodulo=$request->idmodulo;
        $asientomaestro->estado=1;
        $asientomaestro->idrevertido=$request->idasientomaestro;
        $asientomaestro->loteprestamos=$loteprestamos;
        $asientomaestro->u_obs_val=Auth::id();
        $asientomaestro->save();
        $idasientomaestro=$asientomaestro->idasientomaestro;
        //dd($asientodetalle);
        foreach($asientodetalle as $valor)
        {
            
            $asientodetalle=new Con_Asientodetalle();
            $asientodetalle->idasientomaestro=$idasientomaestro;
                
            foreach($valor as $i=>$v)
            {          
                if($i=='monto')
                {
                    if($v>0)
                        $asientodetalle->haber=$v;
                    else
                        $asientodetalle->debe=$v*-1;
                    
                    $v=$v*(-1);
                }
                
                $asientodetalle->$i=$v;
                $asientodetalle->moneda='bs';//cambiar por moneda
                $asientodetalle->usuarioregistro=Auth::id();
                $asientodetalle->usuariomodifica=Auth::id();
                $asientodetalle->save();
                
            }
            $asientodetalle->usuarioregistro=Auth::id();
            $asientodetalle->usuariomodifica=Auth::id();
            $asientodetalle->save();
        }
        $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
        $asientomaestro->estado = '4';
        $asientomaestro->save();
        
        //$asientomaestro->save();join('con__asientodetalles','con__asientodetalles.idasientomaestro','=','con__asientomaestros.idasientomaestro')
        //return $asientomaestro;
    }
    public function observar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
        $asientomaestro->observaciones = $request->observado;
        $asientomaestro->estado = 3;
        $asientomaestro->u_obs_val=Auth::id();
        $asientomaestro->save();
    }
    public function eliminarBorrador(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $repuestafactura=$request->respuestafactura;
        if($repuestafactura==1)
        {
            DB::table('con__librocompras')->where('idasientomaestro', '=', $request->idasientomaestro)->update(['activo' => 0]);
        }

        $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
        $asientomaestro->estado = 6;
        $asientomaestro->save();
    }
    public function validarBorrador(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $checkvalidacion=$request->checkvalidacion;
        $idtipocomprobante=$request->idtipocomprobante;

        sort($checkvalidacion);
        //dd($checkvalidacion);

       /*  $maestroclass= new AsientoMaestroClass();
        $respuesta=$maestroclass->verificarfecha($)
         */
        foreach($checkvalidacion as $valor)
        {
            
            //echo $valor;
            $fechavalidacion = date("Y-m-d H:i:s");

            $asientomaestro = Con_Asientomaestro::findOrFail($valor);
            $fecharegistro=$asientomaestro->fecharegistro;
            
            DB::table('con__librocompras')->where('idasientomaestro', $valor)->update(['validadoconta' => 1]);
            
            

            $asientomaestroclass= new AsientoMaestroClass();
            $respuesta=$asientomaestroclass->verificarfecha($fecharegistro,$idtipocomprobante);

            $asientomaestro->cont_comprobante=$respuesta[0];
            $asientomaestro->cod_comprobante=$respuesta[1];
            
            $asientomaestro->fechavalidado=$fechavalidacion;
            
            $asientomaestro->estado = 1;
            $asientomaestro->u_obs_val=Auth::id();
            $asientomaestro->save();
        }
    }
    public function storeccuenta(Request $request)// guardar o validar cargo de cuenta desde contabilidad.
    {
        /* $arrayDetalle = array(array('idcuenta'='',
							'subcuenta'='',
							'documento'='',
							'moneda'='',
							'monto'= 0
							)
		); */
        $fechatransaccion=$request->fecharegistro;
        $subcuenta=$request->subcuenta;
        $monto=$request->monto;
        $glosa=$request->glosa;
        $idcuentahaber=$request->idcuentahaber;
        $tipodocumento=$request->documento;
        $numdocumento=$request->numdocumento;
        $idmodulo=$request->idmodulo;
        $idtipocomprobante=2;//TODO: tipo de comprobante manual se debe cambiar a dinamico
        $idsolccuenta=$request->idsolccuenta;
        $borrador=false;
        $directivo=$request->sidirectorio;
        //dd($directivo);

        
        
        if($directivo==0)
        {
            $tiposubcuenta=2;
            $solccuenta=Glo_SolicitudCargoCuenta::join('rrh__empleados as a','a.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->select('glo__solicitud_cargo_cuentas.activo','idfilial')
                                                    ->where('idsolccuenta',$idsolccuenta)
                                                    ->get()->toArray();
        }
        else
        {
            $tiposubcuenta=1;
            $solccuenta=Glo_SolicitudCargoCuenta::join('socios as a','a.numpapeleta','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->join('fil__directivos','fil__directivos.idsocio','a.idsocio')
                                                    ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                                    ->select('glo__solicitud_cargo_cuentas.activo','fil__filials.idfilial')
                                                    ->where('idsolccuenta',$idsolccuenta)
                                                    ->get()->toArray();
        }

        //dd($solccuenta);
        if($solccuenta[0]['activo']==0)
        {
            return 'incorrecto';
        }
        else {
            //TODO:verificar que tipo de comprobante es para que sea dinamico o estatico
            $idfilial=$solccuenta[0]['idfilial'];

            $ccuenta = Con_Configuracion::select('valor')
                                ->where('codigo','=','CC')
                                ->where('activo',1)
                                ->get()
                                ->toArray();
            $idccuentadebe=$ccuenta[0]['valor'];

            $arrayDetalle[0]['idcuenta']=$idccuentadebe;
            $arrayDetalle[0]['subcuenta']=$subcuenta;
            $arrayDetalle[0]['documento']='';
            $arrayDetalle[0]['moneda']='Bs.';
            $arrayDetalle[0]['monto']=$monto;
            $arrayDetalle[0]['tiposubcuenta']=$tiposubcuenta;

            $arrayDetalle[1]['idcuenta']=$idcuentahaber;
            $arrayDetalle[1]['subcuenta']=$subcuenta;
            $arrayDetalle[1]['documento']='';
            $arrayDetalle[1]['moneda']='Bs.';
            $arrayDetalle[1]['monto']=$monto * (-1);
            $arrayDetalle[0]['tiposubcuenta']=$tiposubcuenta;

            $asientomaestro= new AsientoMaestroClass();
            $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador,$idfilial);

            $docobligacion=Glo_SolicitudCargoCuenta::select(DB::raw('max(numdocobligacion) as numobligacion'))
                                                    ->get()->toArray();
            
            $numdocobligacion=$docobligacion[0]['numobligacion']+1;
            
            
            Glo_SolicitudCargoCuenta::where('idsolccuenta', $idsolccuenta)->update(['estado_aprobado' => 1,
                                                                                                        'idasientomaestro'=>$respuesta,
                                                                                                        'numdocobligacion'=>$numdocobligacion,
                                                                                                        'glosa'=>$glosa]);    
            if($request->idmovimiento)
            {
                DB::table('con___movimientobancarios')->where('idmovimiento', $request->idmovimiento)->update(['idasientomaestro' => $respuesta]); 
            }

        }
        return $respuesta;
    }
    public function desembolsar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $hora = time();
        $fecha= date('Y-m-d H:i:s',$hora);
        //echo $fecha;
        //echo date("d-m-Y (H:i:s)", $time);
        foreach ($request->arrayids as $indice => $valor) {
            $idmovimientobancario='';
            $asientomaestro=Con_Asientomaestro::findOrFail($valor['idasientomaestro']);
            $asientomaestro->desembolso = 1;
            $asientomaestro->fechahora_desembolso=$fecha;
            $asientomaestro->u_registro_tesoreria=Auth::id();
            //$asientomaestro->idcuentadesembolso=$request->idcuentadesembolso;
            if($valor['num_cheque'])
            {
                $mov_bancario= new Con__Movimientobancario();
                $mov_bancario->idasientomaestro=$valor['idasientomaestro'];
                $mov_bancario->nomportador=$valor['portador'];
                $mov_bancario->idcuenta=$request->idcuentadesembolso;
                $mov_bancario->numdocumento=$valor['num_cheque'];
                $mov_bancario->importe=$valor['importe'];
                $mov_bancario->tipocargo='h';
                $mov_bancario->save();
                $idmovimientobancario=$mov_bancario->idmovimiento;                
            }
            $asientomaestro->save();
        }
    }
    public function agruparcomprobante(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        
        $lista=$request->listaagrupados;
        $arraylista=explode(",",$lista);
        $raw=DB::raw('sum(debe) as sdebe, sum(haber) as shaber');
        $arrayMaestros=Con_Asientodetalle::join('con__cuentas','con__asientodetalles.idcuenta','=','con__cuentas.idcuenta')
                                        ->select('con__cuentas.idcuenta',
                                                'codcuenta',
                                                'nomcuenta',
                                                $raw)
                                        ->whereIn('idasientomaestro',$arraylista)
                                        ->groupBy('con__cuentas.idcuenta')
                                        ->orderBy('sdebe','desc')->get();
        
        $rawconcat=DB::raw("concat(nomgrado,' ',apaterno,' ',amaterno,' ',nombre) as nombres");
        $rawsumas=DB::raw('sum(debe) as sdebe');
        $detalles=Con_Asientodetalle::join('socios','con__asientodetalles.subcuenta','=','socios.numpapeleta')
                                        ->join('par_grados','socios.idgrado','=','par_grados.idgrado')
                                        ->select('numpapeleta',
                                                    $rawconcat,
                                                    $rawsumas,
                                                    'documento')
                                        ->whereIn('idasientomaestro',$arraylista)
                                        ->groupBy('numpapeleta')
                                        ->orderBy('sdebe','desc')->get();
        
        $fechadistinta=Con_Asientomaestro::select(DB::raw('DISTINCT(date(fecharegistro)) as fecha'))
                                            ->wherein('idasientomaestro',$arraylista)
                                            ->get();

        
        return ['arrayMaestros'=>$arrayMaestros,
                'arrayDetalles'=>$detalles,
                'fechacomprobante'=>$fechadistinta];
        

           
        
    }
    
    public function registraragrupacion(Request $request)// guardar comprobante contable de asientos agrupados
    {
        //dd($request);
        $idperfilcuentamaestro=$request->idperfilcuentamaestro;
        $tipocomprobante=$request->tipocomprobante;
        $tipodocumento=$request->tipodocumento;
        $numdocumento=$request->numdocumento;
        $glosa=$request->glosa;
        $arrayMaestros=$request->arrayDetalle;
        $idmodulo=$request->idmodulo;
        $fecharegistro=$request->fecharegistro;
        $listaids=$request->listaids;
        $cont=0;
        foreach ($arrayMaestros as $indice => $valor) {
            //echo"{$valor['idcuenta']}";
            $arrayDetalle[$cont]['idcuenta']=$valor['idcuenta'];
            if(!empty($valor['sdebe']))
            {
                $arrayDetalle[$cont]['monto']=$valor['sdebe'];
                $arrayDetalle[$cont]['debe']=$valor['sdebe'];   
            }
            if(!empty($valor['shaber']))
            {
                $arrayDetalle[$cont]['monto']=$valor['shaber']*(-1);
                $arrayDetalle[$cont]['haber']=$valor['shaber'];   
            }
            $cont++;
        }

        $asientomaestro= new AsientoMaestroClass();
        $respuesta=$asientomaestro->AsientosMaestroAgrupado($idperfilcuentamaestro,$tipocomprobante,$tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fecharegistro,$listaids);
        //dd($arrayDetalle);

        if($idmodulo==2) //TODO: es para verificar y validar los aportes para actualizar el total aportes
        {
            foreach ($listaids as $key => $value) {
                $this->validaraportes($value);
            }
            
        }
    }
    public function validaraportes($idasientomaestro){
        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);
            
        $maxaportes = Config::select('codigo','valor')
                                        ->where(function($query){
                                            $query->where('codigo','AO')
                                                ->orwhere('codigo','AJ');
                                        })
                                        ->get()
                                        ->toArray();
        //dd($maxaportes);
        foreach ($maxaportes as $indice => $valor) {
            if($valor['codigo']=='AO')
                $obligatorios=$valor['valor'];
            
            if($valor['codigo']=='AJ')
                $jubilacion=$valor['valor'];
        }
        
        $tipoaporte=Apo_Tipoaporte::where('descripcion','Reintegro')->get()->toArray();
        $aportes=Apo_Aporte::where('idasientomaestro',$idasientomaestro)->get()->toArray();
        foreach ($aportes as $indice => $valor) {
            $totalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->get()->toArray();
            //dd($totalaportes);
            if(!$totalaportes)
            {
                echo "entra verdad";
                $insertartotal =  new Apo_TotalAporte();
                $insertartotal->numpapeleta=$valor['numpapeleta'];
                $insertartotal->cantobligados=1;
                $insertartotal->totalobligados=$valor['aporte'];
                $insertartotal->fecha_primeraporte_obligados=$valor['fechaaporte'];
                $insertartotal->fecha_ultimoaporte_obligados=$valor['fechaaporte'];
                $insertartotal->save();
            }   
            else
            {
               
                if($totalaportes[0]['obligatorios']==0)
                {
                    //echo "entra obligatorios</br>";
                    if($valor['idtipoaporte']==$tipoaporte[0]['idtipoaporte'])
                    {
                        $totalobligados=$totalaportes[0]['totalobligados']+$valor['aporte'];
                        $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['totalobligados'=>$totalobligados]);
                    }
                    else
                    {
                        $cantobligados=$totalaportes[0]['cantobligados']+1;
                        $totalobligados=$totalaportes[0]['totalobligados']+$valor['aporte'];
                        
                        //echo"{$valor['fechaaporte']} -- {$totalaportes[0]['fecha_primeraporte_obligados']}</br>";
                        if($valor['fechaaporte'] < $totalaportes[0]['fecha_primeraporte_obligados'])
                        {   
                            //echo "entra if</br>"; 
                            $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['cantobligados'=>$cantobligados,
                                                                                                                                'totalobligados'=>$totalobligados,
                                                                                                                                'fecha_primeraporte_obligados'=>$valor['fechaaporte']]);
                              
                        }
                        else if($valor['fechaaporte'] >= $totalaportes[0]['fecha_ultimoaporte_obligados'])
                        {
                            //echo "entra else if</br>";
                            $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['cantobligados'=>$cantobligados,
                                                                                                    'totalobligados'=>$totalobligados,
                                                                                                    'fecha_ultimoaporte_obligados'=>$valor['fechaaporte']]);
                            
                        }
                        else {
                            $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['cantobligados'=>$cantobligados,
                                                                                                    'totalobligados'=>$totalobligados]);
                            
                        }

                        if($cantobligados==$obligatorios)
                        {
                            $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['obligatorios'=>1]);
                            $actualizacionaportes=Apo_Idsaporte::where('numpapeleta',$valor['numpapeleta'])
                                                                ->where('tipodevolucion',0)->update(['tipodevolucion'=>1,]);
                        }
                    }
                }
                else if($totalaportes[0]['jubilados']==0)
                {
                    if($valor['idtipoaporte']==$tipoaporte[0]['idtipoaporte'])
                    {
                        $totaljuilacion=$totalaportes[0]['totaljuilacion']+$valor['aporte'];
                        $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['totaljuilacion'=>$totaljuilacion]);
                    }
                    else
                    {
                        $cantjubilacion=$totalaportes[0]['cantjubilacion']+1;
                        $totaljubilacion=$totalaportes[0]['totaljubilacion']+$valor['aporte'];
                        

                        if(($valor['fechaaporte']<$totalaportes[0]['fecha_primeraporte_jubilacion'])|| $cantjubilacion==1)
                        {
                            if($cantjubilacion==1)
                            {
                                $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['cantjubilacion'=>$cantjubilacion,
                                                                                                                                'totaljubilacion'=>$totaljubilacion,
                                                                                                                                'fecha_primeraporte_jubilacion'=>$valor['fechaaporte'],
                                                                                                                                'fecha_ultimoaporte_jubilacion'=>$valor['fechaaporte']]);
                            }
                            else
                            {
                                $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['cantjubilacion'=>$cantjubilacion,
                                                                                                                                'totaljubilacion'=>$totaljubilacion,
                                                                                                                                'fecha_primeraporte_jubilacion'=>$valor['fechaaporte']]);
                            }
                        }
                            
                        else if($valor['fechaaporte']>=$totalaportes[0]['fecha_ultimoaporte_jubilacion'])
                        {
                            $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['cantjubilacion'=>$cantjubilacion,
                                                                                                    'totaljubilacion'=>$totaljubilacion,
                                                                                                    'fecha_ultimoaporte_jubilacion'=>$valor['fechaaporte']]);
                        }
                        else{
                            $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['cantjubilacion'=>$cantjubilacion,
                                                                                                    'totaljubilacion'=>$totaljubilacion]);
                        }
                        
                        if($cantjubilacion==$jubilacion)
                        {
                            $actualizaciontotalaportes=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['jubilados'=>1]);
                            $actualizacionaportes=Apo_Idsaporte::where('numpapeleta',$valor['numpapeleta'])
                                                                ->where('tipodevolucion',0)->update(['tipodevolucion'=>2,]);
                        }

                    }
                }
                else
                {
                    $excedente= new Apo_Excedenteaporte();
                    $excedente->idaporte=$valor['idaporte'];
                    $excedente->numpapeleta=$valor['numpapeleta'];
                    $excedente->fechaaporte=$valor['fechaaporte'];
                    $excedente->save();
                    $actualizacionidsaportes=Apo_Idsaporte::where('idaporte',$valor['idaporte'])->update(['tipodevolucion'=>3]);
                }
            }

        }

    }
    
}
/* $arrayDetalle = array(array('idcuenta'='',
							'monto',
							'debe'=0,
							'haber'=0
							)
				); */