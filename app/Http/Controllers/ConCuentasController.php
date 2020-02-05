<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Adm_User;
use Illuminate\Support\Facades\Validator;
use App\Con_Cuentanivel5;
use App\Con_Cuentas;
use Illuminate\Support\Facades\DB;

class ConCuentasController extends Controller
{
    public function index(Request $request)
    {
        $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
        $user->authorizeRoles('user');
    
        //if (!$request->ajax()) return redirect('/');


        //$cuentanivel2 = Con_Cuentanivel2::orderBy('valorn2', 'asc')
                        
        $codn5=$request->codn5;               
        $cuentas = Con_Cuentas::join('con__cuentanivel5','con__cuentanivel5.codn5','=','con__cuentas.codn5')
                                ->join('par__monedas','con__cuentas.idmoneda','=','par__monedas.idmoneda')
        //->where('con__cuentas.activo','=','1')
        ->select('con__cuentas.codn5','idcuenta','valorcuenta','nomcuenta','con__cuentas.activo','codcuenta','descripcion','codmoneda','con__cuentas.idmoneda')
        ->where('con__cuentas.codn5','=',$codn5)
        ->orderBy('codcuenta', 'asc')
        ->paginate(20);   

        $raw=DB::raw('max(valorcuenta) as maxccuenta');
        $maxccuenta=Con_Cuentas::where('codn5',$codn5)
                                    ->select($raw)
                                    ->get()->toarray();
        
        return [
            'pagination' => [
                'total'        => $cuentas->total(),
                'current_page' => $cuentas->currentPage(),
                'per_page'     => $cuentas->perPage(),
                'last_page'    => $cuentas->lastPage(),
                'from'         => $cuentas->firstItem(),
                'to'           => $cuentas->lastItem(),
            ],
            'cuentass' => $cuentas,
            'maxccuenta'=>$maxccuenta
        ];
    }
    
    public function selectCuentas(Request $request){
        // if (!$request->ajax()) return redirect('/');
        $codn5=$request->codn5;
         $cuentas = Con_Cuentas::join('con__cuentanivel5','con__cuentanivel5.codn5','=','con__cuentas.codn5')
         ->where('con__cuentas.activo','=','1')
         ->where('con__cuentas.codn5','=',$codn5)
         ->orderBy('codcuenta', 'asc')->get();
         return ['cuentass' => $cuentas];
     }
     
     public function selectValornn(Request $request){
        // if (!$request->ajax()) return redirect('/');
         $valornn = Con_Cuentas::select('codcuenta')
                            ->where('idcuenta','=',$request->idcuenta)
                            ->get();
         return ['valornn' => $valornn];
     }
     public function selectBuscarcuenta(Request $request){
        //if (!$request->ajax()) return redirect('/');
        
        $buscararray = array();  
        
        $buscararray = explode(" ",$request->buscar);

        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(nomcuenta like '%".$valor."%' or codcuenta like '%".$valor."%')";
                }
                else
                {
                    $sqls.=" and (nomcuenta like '%".$valor."%' or codcuenta like '%".$valor."%')";
                } 
            }   
            $cuentas = Con_Cuentas::select('idcuenta','codcuenta','nomcuenta','activo')->orderBy('codcuenta', 'asc')->whereraw($sqls)->get();
        }
        return ['cuentas' => $cuentas];
     }
     public function selectBuscarcuenta2(Request $request){
        //if (!$request->ajax()) return redirect('/');
        
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        $raw=DB::raw('concat(codcuenta, " ", nomcuenta) as cuentas');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(nomcuenta like '%".$valor."%' or codcuenta like '%".$valor."%')";
                }else{
                    $sqls.=" and (nomcuenta like '%".$valor."%' or codcuenta like '%".$valor."%')";
                } 
            }   
            $cuentas = Con_Cuentas::select('idcuenta',$raw,'codcuenta','nomcuenta')->orderBy('codcuenta', 'asc')->whereraw($sqls)->where('activo',1)->get();
        }
        else {
            if ($request->id)
                $cuentas = Con_Cuentas::select('idcuenta',$raw,'codcuenta','nomcuenta')->orderBy('idcuenta', 'asc')->where('idcuenta','=',$request->id)->get();
            else
                $cuentas = Con_Cuentas::select('idcuenta',$raw,'codcuenta','nomcuenta')->orderBy('idcuenta', 'asc')->where('activo',1)->get();
        }
        
        
        return ['cuentas' => $cuentas];
        
        /* $raw=DB::raw('concat(codcuenta, " ", nomcuenta) as cuentas');
        // if (!$request->ajax()) return redirect('/');
        $valorcuenta = Con_Cuentas::select('idcuenta',$raw,'codcuenta','nomcuenta')
         ->orderBy('codcuenta')
        ->get();
         

        //dd($valorcuenta); 

       // foreach
        return response()->json($valorcuenta); */



     }
     public function store(Request $request)
     {
         if (!$request->ajax()) return redirect('/');

      
             $validator = Validator::make($request->all(), [
                
                'con__cuentas_codn5_valorcuenta_unique'=>'unique:con__cuentas' 
                //'valorn4'     => 'unique:con__cuentanivel4'
            ]);
        
         
         $codn5=$request->codn5;
         $valorcuenta=strtoupper($request->valorcuenta);
         $nombrecuenta=ucwords(strtolower($request->nombrecuenta));
         $codcuenta=strtoupper($request->codcuenta);
         $descripcion=ucwords(strtolower($request->descripcion));
         $idmoneda=$request->idmoneda;
 
         $cuenta = new Con_Cuentas();
         $cuenta->codn5=$codn5;
         $cuenta->nomcuenta = $nombrecuenta;
         $cuenta->valorcuenta = $valorcuenta;
         $cuenta->codcuenta=$codcuenta;
         $cuenta->descripcion=$descripcion;
         $cuenta->idmoneda=$idmoneda;
         $cuenta->save();
     }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cuenta = Con_Cuentas::findOrFail($request->idcuenta);

        $cuenta->activo = '0';
        $cuenta->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cuenta = Con_Cuentas::findOrFail($request->idcuenta);
        $cuenta->activo = '1';
        $cuenta->save();
    }
    public function update(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'nomcuentan1' => 'unique:con__cuentanivel1'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       }
       */
        //$nuevocodcuenta=$request->codcuenta.$request->valorcuenta;
        if (!$request->ajax()) return redirect('/');

        $cuenta = Con_Cuentas::findOrFail($request->idcuenta);
        
        $cuenta->nomcuenta = ucwords(strtolower($request->nomcuenta));
        //$cuenta->valorcuenta = $request->valorcuenta;
        //$cuenta->codcuenta=$nuevocodcuenta;
        $cuenta->descripcion=$request->descripcion;
        $cuenta->idmoneda=$request->idmoneda;
        $cuenta->activo = '1';
        $cuenta->save();
    }

    public function selectCuentaspto(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(nomcuenta like '%".$valor."%' or descripcion like '%".$valor."%' or codcuenta like '%".$valor."%')";
                }else{
                    $sqls.=" and (nomcuenta like '%".$valor."%' or descripcion like '%".$valor."%' or codcuenta like '%".$valor."%')";
                } 
            }   
            $cuentas = Con_Cuentas::select('idcuenta','codcuenta','nomcuenta','descripcion')->orderBy('idcuenta', 'desc')->whereraw($sqls)->get();
        }
        else {
            if ($request->id)
                $cuentas = Con_Cuentas::select('idcuenta','codcuenta','nomcuenta','descripcion')->orderBy('idcuenta', 'desc')->where('idcuenta','=',$request->id)->get();
            else
                $cuentas = Con_Cuentas::select('idcuenta','codcuenta','nomcuenta','descripcion')->orderBy('idcuenta', 'desc')->get();
        }
        
        return ['cuentas' => $cuentas];
        
    }

}
