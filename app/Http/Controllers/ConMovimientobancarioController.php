<?php

namespace App\Http\Controllers;

use App\Con__Movimientobancario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConMovimientobancarioController extends Controller
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
        
        /*  $validator = Validator::make($request->all(), [
             'nomdepartamento' => 'unique:par_departamentos'
         ]);
 
         if ($validator->fails()) {
             //echo $validator->messages()->first(); exit();
             return ($validator->messages()->first());
        } */
         $conciliacion = new Con__Movimientobancario();
         
         $conciliacion->idcuenta = $request->idcuenta;
         $conciliacion->numdocumento =$request->numdocumento;
         $conciliacion->importe =$request->importe;
         $conciliacion->tipocargo=$request->tipocargo;
         $conciliacion->nomportador=$request->nomportador;
         
         $conciliacion->save();
         $idconciliacion=$conciliacion->idmovimiento;
         //dd($idconciliacion);
         return $idconciliacion;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Con__Movimientobancario  $con__Movimientobancario
     * @return \Illuminate\Http\Response
     */
    public function show(Con__Movimientobancario $con__Movimientobancario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Con__Movimientobancario  $con__Movimientobancario
     * @return \Illuminate\Http\Response
     */
    public function edit(Con__Movimientobancario $con__Movimientobancario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Con__Movimientobancario  $con__Movimientobancario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Con__Movimientobancario $con__Movimientobancario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Con__Movimientobancario  $con__Movimientobancario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Con__Movimientobancario $con__Movimientobancario)
    {
        //
    }
    public function selectConciliacion(Request $request){
        // if (!$request->ajax()) return redirect('/');
        //////////////// de asignacion de cheques a empleados
        $nomempleado=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nomempleado');
        $fecha=DB::raw('date(con___movimientobancarios.created_at) as fecha');
        $conciliacion = Con__Movimientobancario::join('glo__solicitud_cargo_cuentas','glo__solicitud_cargo_cuentas.idasientomaestro','=','con___movimientobancarios.idasientomaestro')
        ->join('rrh__empleados','rrh__empleados.idempleado','=','glo__solicitud_cargo_cuentas.subcuenta')
        ->select('idmovimiento',
                    'con___movimientobancarios.idasientomaestro',
                    'con___movimientobancarios.numdocumento',
                    'con___movimientobancarios.importe',
                    $nomempleado,
                    $fecha,
                    )
        ->where('con___movimientobancarios.tipocargo','h')
        // ->where('glo__solicitud_cargo_cuentas.tipocargo','interno') //TODO: interno debe ser dinamico y buscar en la tabla de externos
        ->where('estado',0)
        ->where('con___movimientobancarios.idcuenta',$request->idcuenta)
        ->limit(10)
        ->orderBy('con___movimientobancarios.numdocumento', 'desc')->get();
     
        //////////////// asignacion de cheques a externos
        $raw=DB::raw('if(idasientomaestro,1,0) as externo');
        $conciliacionexterna = Con__Movimientobancario::select('idmovimiento',
                                                        'idasientomaestro',
                                                        'numdocumento',
                                                        'importe',
                                                        'nomportador as nomempleado',
                                                        $fecha,
                                                        $raw)
                                            ->where('tipocargo','h')
                                            ->where('estado',0)
                                            ->where('idcuenta',$request->idcuenta)
                                            ->where('tipocargo','h')
                                            ->where('idasientomaestro','<>','null')
                                            ->where('nomportador','<>','null')
                                            ->limit(10)
                                            ->orderBy('numdocumento', 'desc')->get();
    
     
     
     
         return ['conciliacion' => $conciliacion,
                 'conciliacionexterna'=>$conciliacionexterna];
     }
     public function segCheques(Request $request)
     {
        $fechainicio = date("Y-m-d", strtotime($request->finicio));
        $fechafin=date("Y-m-d",strtotime($request->ffin));
         $segcheques=Con__Movimientobancario::join('con__asientomaestros','con__asientomaestros.idasientomaestro','=','con___movimientobancarios.idasientomaestro')
                                                ->select('idmovimiento',
                                                            'con__asientomaestros.idasientomaestro',
                                                            'cod_comprobante',
                                                            'nomportador',
                                                            'con___movimientobancarios.numdocumento',
                                                            'importe',
                                                            DB::raw('date(fecharegistro) as fecharegistro1'),
                                                            'con___movimientobancarios.estado')
                                                ->where('con___movimientobancarios.estado','<>',1)
                                                ->where('con__asientomaestros.estado',1)
                                                ->where('con__asientomaestros.gestion',0)
                                                ->where('idcuenta',$request->idcuenta)
                                                ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                                ->orderby('fecharegistro','asc')
                                                ->get();
        return ['segcheques'=>$segcheques];
     }
     
     public function registrarCambio(Request $request)
     {
        //dd($request);
        $fecha=date("Y-m-d H:i:s");
        
        $movimientobancaario = Con__Movimientobancario::findOrFail($request->idmovimiento);
        $movimientobancaario->estado = $request->valor;
        if($request->valor==2)
            $movimientobancaario->fecha_entrega=$fecha;
        $movimientobancaario->save();
     }
     
}
