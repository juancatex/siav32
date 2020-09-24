<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Con_Perfilcuentamaestro;
use App\Con_Asientomaestro;
use App\Con_Tipocomprobante;
use App\Par_Modulo;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class ConPerfilcuentamaestroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
       // if (!$request->ajax()) return redirect('/');

        if($request->activo) {
            $perfilcuentamaestros=Con_Perfilcuentamaestro::get();
            return ['perfilcuentamaestros'=>$perfilcuentamaestros];
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $perfilcuentamaestros = Con_Perfilcuentamaestro::join('par__modulos','par__modulos.idmodulo','=','con__perfilcuentamaestros.idmodulo')
                                                            ->leftjoin('con__tipocomprobantes','con__tipocomprobantes.idtipocomprobante','=','con__perfilcuentamaestros.idtipocomprobante')
                                                            ->select('idperfilcuentamaestro',
                                                                        'nomperfil',
                                                                        'con__perfilcuentamaestros.descripcion',
                                                                        'nomtipocomprobante',
                                                                        'nommodulo',
                                                                        'con__perfilcuentamaestros.activo',
                                                                        'completo',
                                                                        'con__perfilcuentamaestros.idtipocomprobante',
                                                                        'con__perfilcuentamaestros.idmodulo',
                                                                        'con__perfilcuentamaestros.siporcentaje')
                                                            ->orderBy('con__perfilcuentamaestros.completo', 'asc')
                                                            ->orderBy('con__perfilcuentamaestros.idmodulo', 'asc')
                                                            ->orderBy('con__perfilcuentamaestros.idtipocomprobante', 'asc') 
                                                            ->paginate(10);
        }
       /* else{
            $perfilcuentamaestros = Con_Perfilcuentamaestro::join('par__modulos','par__modulos.idmodulo','=','con__perfilcuentamaestros.idmodulo')
                                                            ->join('con__tipocomprobantes','con__tipocomprobantes.idtipocomprobante','=','con__perfilcuentamaestros.idtipocomprobante')
                                                            ->select('idperfilcuentamaestro','nomperfil','con__perfilcuentamaestros.descripcion','nomtipocomprobante','nommodulo')
                                                            ->orderBy('con__perfilcuentamaestros.idmodulo', 'asc')
                                                            ->orderBy('con__perfilcuentamaestros.idtipocomprobante', 'asc')
                                                            ->where('con__perfilcuentamaestros.'.$criterio, 'like', '%'. $buscar . '%')
                                                            ->paginate(8);
        }
        */
        return [
            'pagination' => [
                'total'        => $perfilcuentamaestros->total(),
                'current_page' => $perfilcuentamaestros->currentPage(),
                'per_page'     => $perfilcuentamaestros->perPage(),
                'last_page'    => $perfilcuentamaestros->lastPage(),
                'from'         => $perfilcuentamaestros->firstItem(),
                'to'           => $perfilcuentamaestros->lastItem(),
            ],
            'perfilcuentamaestros' => $perfilcuentamaestros
        ];
    }
    
    public function selectPerfilcuentamaestro(Request $request){
       if (!$request->ajax()) return redirect('/');
 
        $idmodulo=$request->idmodulo;
        $perfilcuentamaestros = Con_Perfilcuentamaestro::select('idperfilcuentamaestro','nomperfil','descripcion')
                                                        ->where('idmodulo','=',$idmodulo)
                                                        ->where('activo','=','1')
                                                        ->where('completo','=','1')
                                                        ->orderBy('nomperfil', 'asc')->get();
        
        return ['perfilcuentamaestros' => $perfilcuentamaestros];
    }
    public function selectPerfilcuentamaestro_contable(Request $request){
       // if (!$request->ajax()) return redirect('/');
  
         $idmodulo=$request->idmodulo;
         $perfilcuentamaestros = Con_Perfilcuentamaestro::select('idperfilcuentamaestro','nomperfil','descripcion')
                                                         ->where('idmodulo','=',$idmodulo)
                                                         ->where('activo','=','1')
                                                         ->where('completo','=','1')
                                                         ->orderBy('nomperfil', 'asc')->get();
         foreach($perfilcuentamaestros as  $value){ 
            
            $value['total']=  Con_Asientomaestro::leftjoin('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','=','con__tipocomprobantes.idtipocomprobante')
                                                    ->join('con__perfilcuentamaestros','con__asientomaestros.idperfilcuentamaestro','=','con__perfilcuentamaestros.idperfilcuentamaestro')
                                                    ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                    ->where(function($query) {
                                                        $query->where('con__asientomaestros.estado', 0)
                                                            ->orWhere('con__asientomaestros.estado', 3);
                                                    })
                                                    ->where('con__asientomaestros.idmodulo', '=', $idmodulo) 
                                                    ->where('con__asientomaestros.desembolso', '=','1')
                                                    ->where('con__asientomaestros.idperfilcuentamaestro','=',$value->idperfilcuentamaestro)
                                                    ->where('con__asientomaestros.gestion',0) 
                                                    ->orderBy('fecharegistro', 'desc')
                                                    ->orderBy('cont_comprobante','desc')
                                                    ->groupBy('con__asientomaestros.idasientomaestro')->get()->count();
         }

         return ['perfilcuentamaestros' => $perfilcuentamaestros];
     }
    public function selectPerfilMaestroTesoreria(Request $request){
        //if (!$request->ajax()) return redirect('/');
  
         //$idmodulo=$request->idmodulo;
         $idmodulo=3;//TODO:cambiar dinamicamente, ahora 3 pertenece a contabilidad
         $perfilcuentamaestros = Con_Perfilcuentamaestro::select('idperfilcuentamaestro','nomperfil','descripcion')
                                                         ->where('idmodulo','=',$idmodulo)
                                                         ->where('activo','=','1')
                                                         ->where('completo','=','1')
                                                        ->where('idtipocomprobante','2') // tipo comprobante de egreso
                                                        ->orderBy('nomperfil', 'asc')->get();
         return ['perfilcuentamaestros' => $perfilcuentamaestros];
     }
    public function getnamePerfilcuentamaestro(Request $request){
        if (!$request->ajax()) return redirect('/');
   
         $perfilcuentamaestros = Con_Perfilcuentamaestro::select('nomperfil','descripcion')
                                                         ->where('idmodulo','=',$request->idmodulo)
                                                         ->where('idperfilcuentamaestro','=',$request->idperfil)
                                                         ->where('activo','=','1')
                                                         ->where('completo','=','1')->get();
         return ['perfil' => $perfilcuentamaestros];
     }
    public function selectmaestrofinalizado(Request $request){
        //if (!$request->ajax()) return redirect('/');

        //$tipooperacion=$request->tipooperacion;
       
        //echo $tipooperacion;
        $idmaestro=$request->idmaestro;
        $perfilcuentamaestros = Con_Perfilcuentamaestro::where('idperfilcuentamaestro','=',$idmaestro)
                                                        ->get()->toArray();

        
        return ['perfilcuentamaestros' => $perfilcuentamaestros[0]['completo']];
    }
    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        
       /* $validator = Validator::make($request->all(), [
            'nomdepartamento' => 'unique:par_departamentos'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        */
        $perfilcuentamaestro = new Con_Perfilcuentamaestro();
        
        $perfilcuentamaestro->nomperfil = ucwords(strtolower($request->nomperfilcuentamaestro));
        $perfilcuentamaestro->descripcion = ucwords(strtolower($request->descripcion));
        $perfilcuentamaestro->idmodulo = $request->idmodulo;
        $perfilcuentamaestro->idtipocomprobante = $request->idtipocomprobante;
        $perfilcuentamaestro->siporcentaje=$request->siporcentaje;
        $perfilcuentamaestro->idusuario=Auth::id();  
        $perfilcuentamaestro->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $perfilcuentamaestro = Con_Perfilcuentamaestro::findOrFail($request->idmaestro);

        $perfilcuentamaestro->activo = '0';
        $perfilcuentamaestro->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $perfilcuentamaestro = Con_Perfilcuentamaestro::findOrFail($request->idmaestro);
        $perfilcuentamaestro->activo = '1';
        $perfilcuentamaestro->save();
    }
    public function finalizarcuenta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        
        $perfilcuentamaestro = Con_Perfilcuentamaestro::findOrFail($request->idmaestro);
        $perfilcuentamaestro->completo = '1';
        $perfilcuentamaestro->save();
    }
    public function update(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'nomdepartamento' => 'unique:par_departamentos'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       }
        */
        if (!$request->ajax()) return redirect('/');

        $perfilcuentamaestro = Con_Perfilcuentamaestro::findOrFail($request->idmaestro);
        $perfilcuentamaestro->nomperfil = ucwords(strtolower($request->nomperfilcuentamaestro));
		$perfilcuentamaestro->descripcion = ucwords(strtolower($request->descripcionperfilcuentamaestro));
        $perfilcuentamaestro->idtipocomprobante=$request->idtipocomprobante;
        $perfilcuentamaestro->idmodulo=$request->idmodulo;
        $perfilcuentamaestro->activo = '1';
        $perfilcuentamaestro->save();
    }

    public function reporte(Request $request){
        //$fecha=(DB::select("select getfecha() as total"))[0]->total; 
        $fecha=date("d-m-yy H:i:s");
        $ree = DB::select('SELECT cm.idperfilcuentamaestro,cm.nomperfil ,ct.nomtipocomprobante,m.nommodulo,cd.tipocargo,cu.nomcuenta,cu.codcuenta 
        FROM con__perfilcuentamaestros cm , con__tipocomprobantes ct, par__modulos m, con__perfilcuentadetalles cd,con__cuentas cu 
        WHERE cm.idtipocomprobante=ct.idtipocomprobante and m.idmodulo=cm.idmodulo and cd.idperfilcuentamaestro=cm.idperfilcuentamaestro and cd.idcuenta=cu.idcuenta 
        and cm.activo=1 and cm.completo=1 and cm.idusuario=? ORDER by cm.idperfilcuentamaestro,cd.tipocargo,cm.idmodulo,cm.idtipocomprobante',array(Auth::id()));
        return ['reporte' => $ree,'user'=>Auth::user()->username,'date'=>$fecha];
    }
}
