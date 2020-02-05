<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Con_Segbancario;
use Illuminate\Support\Facades\DB;

class ConSegbancarioController extends Controller
{
    public function index(Request $request)
    {
       if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $raw=DB::raw('DATE_FORMAT(fecha_movimiento, "%d-%m-%Y") as fecha_movimiento1') ;
        if (!$buscar){
            $movimientos = Con_Segbancario::join('par_departamentos','par_departamentos.iddepartamento','=','con__segbancarios.iddepartamento')
                                            ->select($raw,'abrvdep','concepto','idasientomaestro','idcuenta','con__segbancarios.iddepartamento','idsegbancario','monto','nomdepartamento','num_operacion','tipomovimiento')
                                            ->where('con__segbancarios.activo',1)
                                            ->where('idcuenta',$request->idcuenta)
                                            ->where('fecha_movimiento','<=',$request->fechacorte)
                                            ->orderBy('fecha_movimiento', 'asc')
                                            ->get();
        }
        else{
            $buscararray = explode(" ",$buscar);
            if (sizeof($buscararray)>0) { 
                $sqls=''; 
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(nomdepartamento like '%".$valor."%' or fechamovimiento like '%".$valor."%' or concepto like '%".$valor."%' or num_operacion like '%".$valor."%' or monto like '%".$valor."%')";
                    }
                    else
                    {
                        $sqls.=" and (nomdepartamento like '%".$valor."%' or fechamovimiento like '%".$valor."%' or concepto like '%".$valor."%' or num_operacion like '%".$valor."%' or monto like '%".$valor."%')";
                    } 
                }   
                
            }
            $movimientos = Con_Segbancario::join('par_departamentos','par_departamentos.iddepartamento','=','con__segbancarios.iddepartamento')
            ->select($raw,'abrvdep','concepto','idasientomaestro','idcuenta','con__segbancarios.iddepartamento','idsegbancario','monto','nomdepartamento','num_operacion','tipomovimiento')
                                            ->where('con__segbancarios.activo',1)
                                            ->where('idcuenta',$request->idcuenta)
                                            ->where('fecha_movimiento','<=',$request->fechacorte)
                                            ->whereraw($sqls)
                                            ->orderBy('fecha_movimiento', 'asc')
                                            ->get();
        }
        
        return ['movimientos' => $movimientos];
    }

    public function selectDepartamento(Request $request){
        if (!$request->ajax()) return redirect('/');
        $movimientos = Con_Segbancario::
        select('iddepartamento','nomdepartamento','abrvdep')
        ->where('activo','=','1')
        ->orderBy('iddepartamento', 'asc')->get();
        return ['movimientos' => $movimientos];
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
        
       /*  $validator = Validator::make($request->all(), [
            'nomdepartamento' => 'unique:par_movimientos'
        ]); */

     /*    if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
         */
        

        $movimiento = new Con_Segbancario();
        
        $movimiento->idcuenta = $request->idcuenta;
        $movimiento->fecha_movimiento =$request->fecha_movimiento;
        $movimiento->concepto = $request->concepto;
        $movimiento->iddepartamento = $request->iddepartamento;
        $movimiento->num_operacion = $request->num_operacion;
        $movimiento->monto = $request->monto;
        $movimiento->tipomovimiento = $request->tipomovimiento;
        $movimiento->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $iddepartamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /*  $validator = Validator::make($request->all(), [
            'nomdepartamento' => 'unique:par_movimientos'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       } */
        
        if (!$request->ajax()) return redirect('/');
        $movimiento = Con_Segbancario::findOrFail($request->idsegbancario);
        $movimiento->activo = '0';
        $movimiento->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $departamento = Con_Segbancario::findOrFail($request->iddepartamento);

        $departamento->activo = '0';
        $departamento->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $departamento = Con_Segbancario::findOrFail($request->iddepartamento);
        $departamento->activo = '1';
        $departamento->save();
    }

    
    //para el nuevo entorno de filiales //para el nuevo entorno de filiales
    //para el nuevo entorno de filiales //para el nuevo entorno de filiales
    //para el nuevo entorno de filiales //para el nuevo entorno de filiales
    public function listamovimientos(Request $request)
    {
        $movimientos=Con_Segbancario::orderBy('iddepartamento','asc')->get();
        return ['movimientos'=>$movimientos];
    }
}
