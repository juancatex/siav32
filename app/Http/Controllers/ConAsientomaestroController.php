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
use App\Con_Asientosubcuenta;
use App\Con_Firmaautorizada;
use App\Rrh_Empleado;
use App\Con_Perfilcuentamaestro;
use App\Fil_Filial;

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
                                                                'con__perfilcuentamaestros.nomperfil',
                                                                'observaciones',
                                                                'fact_modificada',
                                                                'cod_comprobante',
                                                                'idunidad'
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
                                                                'con__perfilcuentamaestros.nomperfil',
                                                                'observaciones',
                                                                'fact_modificada',
                                                                'cod_comprobante',
                                                                'desembolso',
                                                                $raw,
                                                                'idunidad'
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
                                                                    // 'nomperfil',
                                                                    'idrevertido',
                                                                    'fact_modificada',
                                                                    'cod_comprobante',
                                                                    $raw,
                                                                    'glo__solicitud_cargo_cuentas.idsolccuenta',
                                                                    'seg_descargoccuenta',
                                                                    'idunidad'
                                                                    )
                                                        ->orderBy('fecharegistro', 'desc')
                                                        ->orderBy('cont_comprobante','desc')
                                                        ->groupBy('con__asientomaestros.idasientomaestro')
                                                        ->limit(50)->paginate(50);
                }
                elseif ($borradorcheck=='borrador')
                {
                    $borrador=5;
                    $raw=DB::raw('round(sum(con__asientodetalles.monto),2) as total');
                    $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                            ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                        ->leftjoin('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                        ->leftjoin('par__modulos','con__asientomaestros.idmodulo','=','par__modulos.idmodulo')
                                                        ->leftjoin('glo__solicitud_cargo_cuentas','con__asientomaestros.idasientomaestro','glo__solicitud_cargo_cuentas.idasientomaestro')
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
                                                                    'con__asientomaestros.glosa',
                                                                    'idfilial',
                                                                    'con__asientomaestros.idmodulo',
                                                                    'par__modulos.nommodulo',
                                                                    'estado',
                                                                    'nomtipocomprobante',
                                                                    // 'nomperfil',
                                                                    'fact_modificada',
                                                                    $raw,
                                                                    'cod_comprobante',
                                                                    'estado_aprobado',
                                                                    'idunidad'
                                                                    )
                                                                    ->groupBy('con__asientomaestros.idasientomaestro')
                                                        ->orderBy('fecharegistro', 'desc')
                                                        ->orderBy('cont_comprobante','desc')->limit(50)->paginate(50);
                    
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
                                                                // 'nomperfil',
                                                                'idrevertido',
                                                                'fact_modificada',
                                                                'cod_comprobante',
                                                                $raw,
                                                                'glo__solicitud_cargo_cuentas.idsolccuenta',
                                                                'seg_descargoccuenta',
                                                                'idunidad'
                                                                )
                                                    ->groupBy('con__asientomaestros.idasientomaestro')
                                                    ->orderBy('fecharegistro', 'desc')
                                                    ->orderBy('cont_comprobante','desc')->limit(50)->paginate(50);
            }
            elseif ($borradorcheck=='borrador')
            {
                $borrador=5;
                $raw=DB::raw('round(sum(con__asientodetalles.monto),2) as total');
                $asientomaestros = Con_Asientomaestro::join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                        ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                    ->leftjoin('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                    ->leftjoin('par__modulos','con__asientomaestros.idmodulo','=','par__modulos.idmodulo')  
                                                    ->leftjoin('glo__solicitud_cargo_cuentas','con__asientomaestros.idasientomaestro','glo__solicitud_cargo_cuentas.idasientomaestro')                                                  
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
                                                                'con__asientomaestros.glosa',
                                                                'idfilial',
                                                                'con__asientomaestros.idmodulo',
                                                                'par__modulos.nommodulo',
                                                                'estado',
                                                                'nomtipocomprobante',
                                                                // 'nomperfil',
                                                                'fact_modificada',
                                                                $raw,
                                                                'cod_comprobante',
                                                                'estado_aprobado',
                                                                'idunidad'
                                                                )
                                                                ->groupBy('con__asientomaestros.idasientomaestro')
                                                    ->orderBy('fecharegistro', 'desc')
                                                    ->orderBy('cont_comprobante','desc')->limit(50)->paginate(50);
                
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
                $sqlss="(".(is_numeric($valor2)?"con__asientomaestros.loteprestamos =".$valor2." or con__asientomaestros.cont_comprobante =".$valor2." or ":"")."con__asientomaestros.glosa like '%".$valor2."%'  or con__asientomaestros.fecharegistro like '".$valor2."%' or con__asientomaestros.tipodocumento = '".$valor2."' or con__asientomaestros.numdocumento like '%".$valor2."%' or con__asientomaestros.cod_comprobante = '".$valor2."')";
           
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
                                                                $raw,
                                                                'idunidad'
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
        
        $arrayDetalle = array();
        $idmodulo=$request->idmodulo;
        $rowregistros=$request->rowregistros;
        $sidebito=$request->sidebito;
        $fechatransaccion=$request->fechatransaccion;
        $idtipocomprobante=$request->idtipocomprobante;
        $tipodocumento=$request->tipodocumento;
        $numdocumento=$request->numdocumento;
        $glosa=$request->glosa;
        $idperfilcuentamaestro='';
        $borrador=$request->borrador;
        $idfacturas=$request->idfacturas;
        $validarfacturas=$request->validarfacturas;
        $idfilial=$request->idfilial;
        $idunidad=$request->idunidad;

        if($request->sidirectorio)
            $tiposubcuenta=1;
        else
            $tiposubcuenta=2;
        $cont=0;
        $estado_aprobado=$request->estado_aprobado;
        
        foreach ($rowregistros as $indice=>$valor)
        {
            $swd=0;
            $swh=0;
            //echo"{$valor['idcuenta']}";
            $arrayDetalle[$cont]['idcuenta']=$valor['idcuenta'];
            if(count($valor['idsubcuenta'])>0)
            {
                foreach ($valor['idsubcuenta'] as $i => $v) {
                    $arrayDetalle[$cont]['subcuenta'][$i]=$v;
                }
            }

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

            $buscarsolccuenta = Con_Asientomaestro::select('idsolccuenta')->where('idasientomaestro',$request->idasientomaestro)->get()->toArray();

            if($buscarsolccuenta[0]['idsolccuenta']!=null);
            {
                $sisolccuenta=true;
                $idsolccuenta = $buscarsolccuenta[0]['idsolccuenta'];
            }

            
            DB::table('con__asientodetalles')->where('idasientomaestro', '=', $request->idasientomaestro)->delete();
            DB::table('con__asientomaestros')->where('idasientomaestro', '=', $request->idasientomaestro)->delete();
            DB::table('con__asientosubcuentas')->where('idasientomaestro',$request->idasientomaestro)->delete();
            //dd($arrayDetalle);
            $asientomaestro= new AsientoMaestroClass();
            $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador,$idfilial,$idunidad);
            
            if($sisolccuenta){
                //echo "entra";
                DB::table('con__asientomaestros')->where('idasientomaestro', $respuesta)->update(['idsolccuenta' => $idsolccuenta]);    
            }
            

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
                if($sidebito==false)
                {
                    //echo "else idasientomaestro entra idfacturas2";
                    $asientomaestro= new AsientoMaestroClass();
                    $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador,$idfilial,$idunidad);
                    
                    
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
                     //echo "else idasientomaestro entra idfacturas2";
                    $asientomaestro= new AsientoMaestroClass();
                    $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador,$idfilial,$idunidad);
                    
                    
                
                    /////// copiado de arriba no tiene funcion en debito
                    /* $librocompra = con__librocompra::select(DB::raw('max(lote) as maximo'))
                                                    ->where('idasientomaestro','<>',0)
                                                    ->get()->toArray();
                    $lote=$librocompra[0]['maximo']+1; */
                    
                    
                    foreach ($idfacturas as $indice => $valor) {
                        DB::table('con__facturas')->where('idfactura', $valor)->update(['idasientomaestro' => $respuesta,
                                                                                    'validadoconta'=>2]);    
                    }
                }
            }
            else
            {
                //echo "entra idfacturas else";
                $asientomaestro= new AsientoMaestroClass();
                $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador,$idfilial,$idunidad); 
                //echo "respueta".$respuesta;
            }
            if($request->idmovimiento!=0)
            {
                DB::table('con___movimientobancarios')->where('idmovimiento', $request->idmovimiento)->update(['idasientomaestro' => $respuesta]); 
            }
            
        }
        if($estado_aprobado==4)
            DB::table('glo__solicitud_cargo_cuentas')->where('idasientomaestro', $request->idasientomaestro)->update(['idasientomaestro' => $respuesta]); 
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
                                                    $valor = trim($request->buscar);
                                                    $sqls="(".(is_numeric($valor)?"par__prestamos.lote = ".$valor." or ":"")."socios.numpapeleta like '%".$valor."' or socios.nombre ='".$valor."' or socios.apaterno ='".$valor."' or socios.amaterno ='".$valor."' or con__asientomaestros.numdocumento like '%".$valor."%'  or con__asientomaestros.fecharegistro like '".$valor."%' )";
                                                     
                                                }else{
                                                    foreach($buscararray as $valor){
                                                        if(empty($sqls)){
                                                            $sqls="(con__asientomaestros.numdocumento like '%".$valor."%' or socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%'  or par__prestamos.lote = '".$valor."' or con__asientomaestros.fecharegistro like '".$valor."%' )";
                                                        }else{
                                                            $sqls.=" and (con__asientomaestros.numdocumento like '%".$valor."%' or socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%'  or par__prestamos.lote = '".$valor."' or con__asientomaestros.fecharegistro like '".$valor."%')";
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
        
        $asientosubcuentas=Con_Asientosubcuenta::select('tipo_subcuenta','idsubcuenta','subcuenta','idcuenta','subdebe','subhaber')
                                                ->where('idasientomaestro',$request->idasientomaestro)
                                                ->get()->toArray();

        //dd($asientosubcuentas);
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

        DB::beginTransaction();
        {
            try
            {
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
                        /* $asientodetalle->usuarioregistro=Auth::id();
                        $asientodetalle->usuariomodifica=Auth::id();
                        $asientodetalle->save(); */
                        
                    }
                    $asientodetalle->usuarioregistro=Auth::id();
                    $asientodetalle->usuariomodifica=Auth::id();
                    $asientodetalle->save();
                }
                foreach($asientosubcuentas as $valor)
                {
                    
                    $asientosubcuenta=new Con_Asientosubcuenta();
                    $asientosubcuenta->idasientomaestro=$idasientomaestro;
                    $asientosubcuenta->subdetalle="Revertido";
                    foreach ($valor as $i =>$v) {
                        
                        if($i=='subdebe')
                            $asientosubcuenta->subhaber=$v;
                        else
                        {
                            if($i=='subhaber')
                                $asientosubcuenta->subdebe=$v;
                            else
                                $asientosubcuenta->$i=$v;
                        }
                        
                        
                        
                        
                    }
                    //echo $asientosubcuenta;
                    $asientosubcuenta->save();
                }
                $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
                $asientomaestro->estado = '4';
                $asientomaestro->save();

                if($request->seg_descargoccuenta)
                {
                    DB::table('glo__solicitud_cargo_cuentas')->where('idsolccuenta', $request->idsolccuenta)->update(['estado_aprobado' => 3,'idasientomaestro'=>null]);
                }
            }
            catch(\Exeption $e)
            {
                $error=$e->getMessage();
                DB::rollback();
                return $error;
            }
        }
        

        
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

        $respuestafactura=$request->respuestafactura;
        if($respuestafactura==1)
        {
            DB::table('con__librocompras')->where('idasientomaestro',$request->idasientomaestro)->update(['activo' => 0]);
        }
        if($respuestafactura==2)
            DB::table('con__facturas')->where('idasientomaestro',  $request->idasientomaestro)->update(['validadoconta' => 0,
                                                                                                        'idasientomaestro'=>null]);


        $buscarsolccuenta = Con_Asientomaestro::select('idsolccuenta')->where('idasientomaestro',$request->idasientomaestro)->get();
        //dd($buscarsolccuenta[0]->idsolccuenta);

       
        if($buscarsolccuenta[0]->idsolccuenta!=null)
        {
            echo "entra !=nulla";
            $monto=Glo_SolicitudCargoCuenta::select('monto')->where('idsolccuenta',$buscarsolccuenta[0]['idsolccuenta'])->get()->toArray();
            $solccuenta = Glo_SolicitudCargoCuenta::findOrFail($buscarsolccuenta[0]['idsolccuenta']);
            $solccuenta->seg_descargoccuenta = 0;
            $solccuenta->saldo_descargo=$monto[0]['monto'];
            $solccuenta->save();
            
            //DB::table('glo__solicitud_cargo_cuentas')->where('idasientomaestro', $respuesta)->update(['idsolccuenta' => $idsolccuenta]);    
        }
       
        
        



        DB::table('glo__solicitud_cargo_cuentas')->where('idasientomaestro', $request->idasientomaestro)->update(['estado_aprobado' => 3]);

        


        $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
        $asientomaestro->estado = 6;
        $asientomaestro->idsolccuenta=null;
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
            
            $reccuentas=Con_Asientomaestro::leftjoin('glo__solicitud_cargo_cuentas','glo__solicitud_cargo_cuentas.idasientomaestro','con__asientomaestros.idasientomaestro')
                                                    ->select('glo__solicitud_cargo_cuentas.idsolccuenta','estado_aprobado')
                                                    ->where('con__asientomaestros.idasientomaestro',$valor)
                                                    ->get()->toArray();

            if($reccuentas[0]['estado_aprobado']==4)
            {
                DB::table('glo__solicitud_cargo_cuentas')->where('idsolccuenta', $reccuentas[0]['idsolccuenta'])->update(['estado_aprobado' => 1]);
            }

            //dd($reccuentas[0]['estado_aprobado']);
            //echo $valor;
            $fechavalidacion = date("Y-m-d H:i:s");

            $asientomaestro = Con_Asientomaestro::findOrFail($valor);
            
            $fecharegistro=$asientomaestro->fecharegistro;
            
            DB::table('con__librocompras')->where('idasientomaestro', $valor)->update(['validadoconta' => 1]);
            DB::table('con__facturas')->where('idasientomaestro', $valor)->update(['validadoconta' => 1]);
            
            

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
        $borrador=true; // se modifico a pedido de contabilidad, el cargo de cuenta sera en estado borrador
        $directivo=$request->sidirectorio;
        //dd($directivo);

        
        
        if($directivo==0)
        {
            $tiposubcuenta=2;
            $solccuenta=Glo_SolicitudCargoCuenta::join('rrh__empleados as a','a.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->select('glo__solicitud_cargo_cuentas.activo','idfilial')
                                                    ->where('idsolccuenta',$idsolccuenta)
                                                    ->get()->toArray();
            if($solccuenta[0]['idfilial']==1)
            {
                $res=Rrh_Empleado::join('fil__oficinas','fil__oficinas.idoficina','rrh__empleados.idoficina')
                                        ->select('fil__oficinas.idunidad')
                                        ->where('rrh__empleados.idempleado',$subcuenta)
                                        ->where('rrh__empleados.activo',1)
                                        ->get()->toArray();
                $idunidad=$res[0]['idunidad'];
                    
            }
                
            else
                $idunidad=0;
            
            //dd($idunidad);
        }
        else
        {
            $tiposubcuenta=1;
            $solccuenta=Glo_SolicitudCargoCuenta::join('socios as a','a.numpapeleta','=','glo__solicitud_cargo_cuentas.subcuenta')
                                                    ->join('fil__directivos','fil__directivos.idsocio','a.idsocio')
                                                    ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                                    ->select('glo__solicitud_cargo_cuentas.activo','fil__filials.idfilial','fil__directivos.idunidad')
                                                    ->where('idsolccuenta',$idsolccuenta)
                                                    ->get()->toArray();

            if($solccuenta[0]['idfilial']==1)
                $idunidad=$solccuenta[0]['idunidad'];
            else
                $idunidad=0;

           // dd($idunidad);
            
        }

        //dd($solccuenta);
        if($solccuenta[0]['activo']==0)
        {
            return 'incorrecto';//TODO:verificar que valida este if
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
            $arrayDetalle[1]['tiposubcuenta']=$tiposubcuenta;

            //dd($arrayDetalle);
            $asientomaestro= new AsientoMaestroClass();
            $respuesta=$asientomaestro->AsientosManualMaestroArray($idtipocomprobante, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fechatransaccion,$borrador,$idfilial,$idunidad);

            $docobligacion=Glo_SolicitudCargoCuenta::select(DB::raw('max(numdocobligacion) as numobligacion'))
                                                    ->get()->toArray();
            
            $numdocobligacion=$docobligacion[0]['numobligacion']+1;
            
            
            Glo_SolicitudCargoCuenta::where('idsolccuenta', $idsolccuenta)->update(['estado_aprobado' => 4,
                                                                                                        'idasientomaestro'=>$respuesta,
                                                                                                        'fecha_apertura_cuenta'=>$fechatransaccion,
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
      //  $fecha= date('Y-m-d H:i:s',$hora);
        $fecha=(DB::select("select getfecha() as total"))[0]->total;
        //echo $fecha;
        //echo date("d-m-Y (H:i:s)", $time);
        foreach ($request->arrayids as $indice => $valor) {
            
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
                $asientomaestro=Con_Asientomaestro::findOrFail($valor['idasientomaestro']);
                $asientomaestro->desembolso = 1;
                $asientomaestro->id_movimiento = $idmovimientobancario;
                $asientomaestro->fechahora_desembolso=$fecha;
                $asientomaestro->u_registro_tesoreria=Auth::id();
                $asientomaestro->save();              
            }else{
                $idmovimientobancario='';
                $asientomaestro=Con_Asientomaestro::findOrFail($valor['idasientomaestro']);
                $asientomaestro->desembolso = 1;
                $asientomaestro->fechahora_desembolso=$fecha;
                $asientomaestro->u_registro_tesoreria=Auth::id();
                $asientomaestro->save();
            }
            
        }
    }
    public function cobrar(Request $request)
    {
        //dd($request);
        if (!$request->ajax()) return redirect('/');
        $hora = time();
      //  $fecha= date('Y-m-d H:i:s',$hora);
        $fecha=(DB::select("select getfecha() as total"))[0]->total;
        //echo $fecha;
        //echo date("d-m-Y (H:i:s)", $time);
        foreach ($request->arrayids as $valor) {
                $asientomaestro=Con_Asientomaestro::findOrFail($valor);
                $asientomaestro->desembolso = 1;
                $asientomaestro->fechahora_desembolso=$fecha;
                $asientomaestro->u_registro_tesoreria=Auth::id();
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
    public function listarcobranza(Request $request){
        $valor=$request->valor;
        $idmodulo=3;//TODO:cambiar dinamicamente, ahora 3 pertenece a contabilidad
        $perfilcuentamaestros = Con_Perfilcuentamaestro::select('idperfilcuentamaestro')
                                                        ->where('idmodulo','=',$idmodulo)
                                                        ->where('activo','=','1')
                                                        ->where('completo','=','1')
                                                        ->where('idtipocomprobante','1') // tipo comprobante de egreso
                                                        ->orderBy('nomperfil', 'asc')
                                                        ->get()->toArray();
        
        
        $cobranza=Con_Asientomaestro::join('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','con__perfilcuentamaestros.idperfilcuentamaestro')
                                        //->join('con__cuentasocios','con__cuentasocios.idcuentasocio','=','par__prestamos.idcuentasocio')
                                        //->join('socios','socios.idsocio','=','par__prestamos.idsocio')
                                        ->select('con__asientomaestros.idasientomaestro',
                                                    'nomperfil',
                                                    'descripcion',
                                                    //'socios.numpapeleta',
                                                    'con__asientomaestros.fecharegistro',
                                                    'con__asientomaestros.numdocumento',
                                                    'con__asientomaestros.glosa',
                                                    //$raw,
                                                    //'par__prestamos.monto',
                                                    //'numcuentasocio',
                                                    //'idagrupacion',
                                                    'desembolso',
                                                    'fechahora_desembolso',
                                                    'con__asientomaestros.estado',
                                                    //'par__prestamos.lote',
                                                    'con__asientomaestros.observaciones',
                                                    'numdocumento')
                                        ->whereIn('con__asientomaestros.idperfilcuentamaestro',$perfilcuentamaestros)
                                        ->where('desembolso',$valor)
                                        ->orderby('fecharegistro','desc')
                                        ->get();
        return $cobranza;
    }
    
    public function recuperarCuenta(Request $request){
        //echo "hola";
        $reccuentas=Con_Asientomaestro::join('con__asientodetalles','con__asientodetalles.idasientomaestro','con__asientomaestros.idasientomaestro')
                                                    ->leftjoin('glo__solicitud_cargo_cuentas','glo__solicitud_cargo_cuentas.idasientomaestro','con__asientomaestros.idasientomaestro')
                                                    ->leftjoin('con__cuentas','con__cuentas.idcuenta','con__asientodetalles.idcuenta')
                                                    ->select('debe','haber','nomcuenta','codcuenta','con__cuentas.idcuenta')
                                                    ->where('con__asientomaestros.idasientomaestro',$request->idasientomaestro)
                                                    ->orderby('debe','desc')
                                                    ->get();
    
         return ['reccuentas'=>$reccuentas];                                               

    }

    public function datospdf(Request $request){
        $idasientomaestro=$request->idasientomaestro;

        
        $maestros=Con_Asientomaestro::select('idasientomaestro',
                                            'cod_comprobante',
                                            'fecharegistro',
                                            'fechavalidado',
                                            'tipodocumento',
                                            'numdocumento',
                                            'nomtipocomprobante',
                                            'glosa',
                                            'cont_comprobante',
                                            )
                                        ->join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','con__tipocomprobantes.idtipocomprobante')
                                        ->where('idasientomaestro',$idasientomaestro)
                                        //->where('estado',1)
                                        ->get();

        //dd($maestros);

        $asientodetalles = Con_Asientodetalle::join('con__cuentas','con__asientodetalles.idcuenta','con__cuentas.idcuenta')
                                                ->select(
                                                            'nomcuenta',
                                                            'codcuenta',
                                                            'monto',
                                                            'debe',
                                                            'haber',
                                                            'con__asientodetalles.idcuenta',
                                                            'con__asientodetalles.documento',
                                                            'tiposubcuenta',
                                                            'subcuenta'
                                                            )
                                                ->where('idasientomaestro',$idasientomaestro)
                                                ->orderby('debe','desc')
                                                ->orderby('haber','asc')
                                                ->get();
        //dd($asientodetalles);
        
        //dd($asientodetalles);
        //$asientosubdetalles=[];
        

        $siasientomaestro=Con_Asientosubcuenta::where('idasientomaestro',$idasientomaestro)
                                                    ->get();
        
        //dd($siasientomaestro);
        if(count($siasientomaestro)>0)
        {
            foreach ($asientodetalles as $value) {
               
                $filtro=$value->monto;
                $socios =Con_Asientosubcuenta::select('subdetalle as detalle',
                                                            'subdebe',
                                                            'subhaber',
                                                            'idcuenta',
                                                            DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                                            'idsocio as idsubcuenta',
                                                            'numpapeleta as subcuenta',
                                                            'tipo_subcuenta as tiposubcuenta')
                                                    ->join('socios','socios.idsocio','con__asientosubcuentas.idsubcuenta')
                                                    ->where('idasientomaestro',$idasientomaestro)
                                                    ->where('tipo_subcuenta',1)//tipo subcuenta  socios
                                                    ->where('idcuenta',$value->idcuenta)
                                                    ->where(function($query) use ($filtro)
                                                            {
                                                                if ($filtro>0)
                                                                    $query->where('subdebe','>',0);
                                                                else
                                                                    $query->where('subhaber','>',0); 
                                                            });
                                                    
                                                    
                $personal=Con_Asientosubcuenta::select('subdetalle as detalle',
                                                        'subdebe',
                                                        'subhaber',
                                                        'idcuenta',
                                                        DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                                        'idempleado as idsubcuenta',
                                                        'ci as subcuenta',
                                                        'tipo_subcuenta as tiposubcuenta')                                    
                                                    ->join('rrh__empleados','rrh__empleados.idempleado','con__asientosubcuentas.idsubcuenta')
                                                    ->where('idasientomaestro',$idasientomaestro)
                                                    ->where('idcuenta',$value->idcuenta)
                                                    ->where('tipo_subcuenta',2)//tipo subcuenta personal
                                                    ->where(function($query) use ($filtro)
                                                            {
                                                                if ($filtro>0)
                                                                    $query->where('subdebe','>',0);
                                                                else
                                                                    $query->where('subhaber','>',0); 
                                                            })
                                                    ->union($socios);
                
                $otros=Con_Asientosubcuenta::select('subdetalle as detalle',
                                                    'subdebe',
                                                    'subhaber',
                                                    'idcuenta',
                                                    'nomproveedor as nombre',
                                                    'idproveedor as idsubcuenta',
                                                    'nit as subcuenta',
                                                    'tipo_subcuenta as tiposubcuenta')
                                                    ->join('alm__proveedors','alm__proveedors.idproveedor','con__asientosubcuentas.idsubcuenta')
                                                    ->where('idasientomaestro',$idasientomaestro)
                                                    ->where('idcuenta',$value->idcuenta)
                                                    ->where('tipo_subcuenta',3)// tipo subcuenta otros
                                                    ->where(function($query) use ($filtro)
                                                            {
                                                                if ($filtro>0)
                                                                    $query->where('subdebe','>',0);
                                                                else
                                                                    $query->where('subhaber','>',0); 
                                                            })
                                                    ->union($personal);
                
                $subascinalss=Con_Asientosubcuenta::select('subdetalle as detalle',
                                                            'subdebe',
                                                            'subhaber',
                                                            'idcuenta',
                                                            'descripcion as nombre',
                                                            'idconconfig as idsubcuenta',
                                                            'valor as subcuenta',
                                                            'tipo_subcuenta as tiposubcuenta')
                                                            ->join('con__configuracions','con__configuracions.idconconfig','con__asientosubcuentas.idsubcuenta')
                                                            ->where('idasientomaestro',$idasientomaestro)
                                                            ->where('idcuenta',$value->idcuenta)
                                                            ->where('tipo_subcuenta',4)// tipo subcuenta subascinalss
                                                            ->union($otros)
                                                            ->where(function($query) use ($filtro)
                                                            {
                                                                if ($filtro>0)
                                                                    $query->where('subdebe','>',0);
                                                                else
                                                                    $query->where('subhaber','>',0); 
                                                            })
                                                            ->get();
                
                                                        
                $value->subdetalles=$subascinalss;
                
                    //array_push($asientosubdetalles,$subascinalss);
                    
            }
           // dd($asientodetalles);
        }
        else
        {
            //dd($asientodetalles);

            foreach ($asientodetalles as  $value) {
                //echo "->".$value->subcuenta; 
                if($value->subcuenta!="")
                {
                    //echo "entras subcuenta";
                    if($value->tiposubcuenta==2)
                    {
                        $subcuentas=Rrh_Empleado::select(
                                                        
                                                            DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                                            'idempleado as idsubcuenta',
                                                            'ci as subcuenta')                                    
                                                        ->where('idempleado',$value->subcuenta)
                                                        ->get();
                    }
                    else
                    {
                        $subcuentas =Socio::select(DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                                                'idsocio as idsubcuenta',
                                                                'numpapeleta as subcuenta')
                                                        ->where('numpapeleta',$value->subcuenta)//tipo subcuenta  socios
                                                        ->get();
                    }
                    
                    //dd($subcuentas);
                    $subcuentas[0]['detalle']='';
                    $subcuentas[0]['subdebe']=$value->debe;
                    $subcuentas[0]['subhaber']=$value->haber;
                    $value->subdetalles=$subcuentas;
                }
            }
            //dd($asientodetalles);
        }
        

        $firm=Con_Asientomaestro::join('rrh__empleados','rrh__empleados.idempleado','con__asientomaestros.u_registro') 
                                    ->leftjoin('rrh__cargos','rrh__cargos.idcargo','rrh__empleados.idcargo') 
                                    ->select(DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                                'nomcargo',
                                                DB::raw('1 as orden'))
                                    ->where('idasientomaestro',$request->idasientomaestro);

                                    
        $firmas1=Con_Firmaautorizada::join('socios','socios.idsocio','con__firmaautorizadas.idpersona')
                                    ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    ->join('fil__unidads','fil__directivos.idunidad','fil__unidads.idunidad')
                                    ->select(DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                            'nomcargo',
                                            'orden')
                                    ->where('tipo_persona',1)//1 es el valor de los directivos
                                    ->where('con__firmaautorizadas.activo',1)
                                    ->union($firm);
        $firma2=Con_Firmaautorizada::join('rrh__empleados','rrh__empleados.idempleado','con__firmaautorizadas.idpersona') 
                                    ->join('rrh__cargos','rrh__cargos.idcargo','rrh__empleados.idcargo')                   
                                    ->select(DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                    'nomcargo',
                                    'orden')                       
                                    ->where('tipo_persona',2)
                                    ->where('con__firmaautorizadas.activo',1)
                                    ->union($firmas1)
                                    ->orderby('orden', 'asc')
                                    ->get();

                                            

        

        $totalh=0;
        $totald=0;
        foreach ($asientodetalles as $key => $value) {
            $totald=$totald+$value->debe;
            $totalh=$totalh+$value->haber;
            
        }
        //dd($asientodetalles);
        //$maestro=DB::select('select * from con__asientomaestros where estado = 1 and idasientomaestro=2186');

        /* $detalle=Con_Asientodetalle::join('')
        where('idasientomaestro') */
        
        //return($firma2);
        return view('pdf')->with(['maestros'=>$maestros,
                                    'detalles'=>$asientodetalles,
                                    'firmas'=>$firma2,
                                    'totalh'=>$totalh,
                                    'totald'=>$totald]);

                              /*   return ['maestros'=>$maestros,
                                'detalles'=>$asientodetalles,
                                'firmas'=>$firma2,
                            'totalh'=>$totalh,
                            'totald'=>$totald]; */
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