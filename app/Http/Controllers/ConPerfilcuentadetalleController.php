<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Con_Perfilcuentadetalle;
use Illuminate\Support\Facades\DB;
use App\Con_Perfilcuentamaestro;

class ConPerfilcuentadetalleController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $idmaestro=$request->idmaestro;
        
        //DB::unprepared('CALL actualizar_perfilmaestro('.$idmaestro.')');
        DB::beginTransaction();
        {
            try{   
                    DB::unprepared('CALL actualizar_perfilmaestro("'.$idmaestro.'")');
                    DB::commit();
                    echo "entra index $idmaestro";
            }
            catch(\Exception $e)
            {
                $error = $e->getMessage();
                DB::rollback();
                //return $e;
            }
        }
        //return $idmaestro;
        //return 'correcto';
    }
    
    public function selectPerfilcuentadetalle(Request $request){
        //if (!$request->ajax()) return redirect('/');

        //$tipooperacion=$request->tipooperacion;
       
        //echo $tipooperacion;
        $idmaestro=$request->idmaestro;
        //$tipocargo=$request->tipocargo;
        $perfilcuentadetalles = Con_Perfilcuentadetalle::join('con__cuentas','con__cuentas.idcuenta','=','con__perfilcuentadetalles.idcuenta')
                                                        ->leftjoin('par_fuerzas','par_fuerzas.idfuerza','con__perfilcuentadetalles.relacionfuerza')
                                                        ->select('con__cuentas.nomcuenta','con__cuentas.codcuenta','con__cuentas.idcuenta','tipocargo','porcentaje','relacionfuerza','nomfuerza')
                                                        ->where('idperfilcuentamaestro','=',$idmaestro)
                                                        //->where('tipocargo','=',$tipocargo)
                                                        ->orderBy('porcentaje', 'desc')->get();
        return ['perfilcuentadetalles' => $perfilcuentadetalles];
    }

    public function selectPerfilcuentadetalleProducto(Request $request){
    //  if (!$request->ajax()) return redirect('/');
        $perfilcuentadetalles = Con_Perfilcuentadetalle::join('con__cuentas','con__cuentas.idcuenta','=','con__perfilcuentadetalles.idcuenta')
        ->join('par__monedas','con__cuentas.idmoneda','=','par__monedas.idmoneda')
        ->select('idperfilcuentadetalle','con__cuentas.codcuenta','con__cuentas.idcuenta','con__cuentas.nomcuenta','con__cuentas.idmoneda','tipocargo','idperfilcuentamaestro','par__monedas.codmoneda','par__monedas.tipocambio')
        ->where('idperfilcuentamaestro','=',$request->idmaestro)
        ->where('con__cuentas.activo','=','1')
        ->orderBy('tipocargo', 'asc')
        ->orderBy('idcuenta', 'asc')->get(); 
        return ['perfilcuentadetalles' => $perfilcuentadetalles];
    }

    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        
        /*$validator = Validator::make($request->all(), [
            'nomdepartamento' => 'unique:par_departamentos'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
        }
        */
        $idmaestro=$request->idmaestro;
        $arraydebe=$request->arraydebe;
        $arrayhaber=$request->arrayhaber;
        //dd($arraydebe);
        
        //TODO:agregar try and catch
        foreach ($arraydebe as $key => $valor) {
            $perfildetalle = new Con_Perfilcuentadetalle();
            $perfildetalle->idperfilcuentamaestro=$idmaestro;
            $perfildetalle->tipocargo='d';
            foreach ($valor as $i => $v) {
                if($i=='idcuenta')
                    $perfildetalle->idcuenta=$v;
                if($i=='porcentaje')
                    if($v!=0)
                        $perfildetalle->porcentaje=$v;
            }
            $perfildetalle->save();
            //echo "<<<<<<<<<<<<<<<<<<</br>";
        }
        foreach ($arrayhaber as $key => $valor) {
            $perfildetalle = new Con_Perfilcuentadetalle();
            $perfildetalle->idperfilcuentamaestro=$idmaestro;
            $perfildetalle->tipocargo='h';
            foreach ($valor as $i => $v) {
                if($i=='idcuenta')
                    $perfildetalle->idcuenta=$v;
                if($i=='porcentaje')
                    if($v!=0)
                        $perfildetalle->porcentaje=$v;
                
            }
            $perfildetalle->save();
            //echo "<<<<<<<<<<<<<<<<<<</br>";
        }

        $perfilmaestro = Con_Perfilcuentamaestro::findOrFail($idmaestro);
        $perfilmaestro->completo = '1';
        $perfilmaestro->save();
    }
  
}
