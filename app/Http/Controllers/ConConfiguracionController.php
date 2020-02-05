<?php

namespace App\Http\Controllers;

use App\Con_Configuracion;
use App\Con_Cuentas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
       
        $relaciones = Con_Configuracion::join('con__cuentas','con__cuentas.idcuenta','=','con__configuracions.valor')
        ->select('idconconfig',
                'codigo',
                'con__configuracions.descripcion',
                'valor',
                'nomcuenta',
                'codcuenta',
                )
        ->where('codigo', $request->criterio)
        ->where('con__configuracions.activo',1)
        ->orderBy('con__configuracions.created_at', 'asc')->paginate(10);

        return [
            'pagination' => [
                'total'        => $relaciones->total(),
                'current_page' => $relaciones->currentPage(),
                'per_page'     => $relaciones->perPage(),
                'last_page'    => $relaciones->lastPage(),
                'from'         => $relaciones->firstItem(),
                'to'           => $relaciones->lastItem(),
            ],
            'relaciones' => $relaciones
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
        if (!$request->ajax()) return redirect('/');
        
       /*  $validator = Validator::make($request->all(), [
            'nomdepartamento' => 'unique:par_departamentos'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       } */
        
        

        $relaciones = new Con_Configuracion();
        
        $relaciones->codigo = $request->codigo;
        $relaciones->descripcion =$request->descripcion;
        $relaciones->valor =$request->valor;
        $relaciones->tipoconfiguracion=4;
        $relaciones->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Con_Configuracion  $con_Configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(Con_Configuracion $con_Configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Con_Configuracion  $con_Configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Con_Configuracion $con_Configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Con_Configuracion  $con_Configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Con_Configuracion $con_Configuracion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Con_Configuracion  $con_Configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Con_Configuracion $con_Configuracion)
    {
        //
    }
    public function cuentas_libros()
    {
        //if (!$request->ajax()) return redirect('/');

        $cuentaslibros = Con_Configuracion::select('codigo','valor')
                                    ->where(function($query){
                                        $query->where('codigo','=','LV')
                                            ->orwhere('codigo','=','LC');
                                    })
                                    ->where('activo',1)
                                    ->get()
                                    ->toArray();
        
        return $cuentaslibros;

    }
    public function cuentas_conciliacion(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $cuentaslibros = Con_Configuracion::select('valor')
                                    ->where('codigo','LB')
                                    ->where('activo',1)
                                    ->get()
                                    ->toArray();
        
        return $cuentaslibros;

    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $relaciones = Con_Configuracion::findOrFail($request->idconconfig);

        $relaciones->activo = '0';
        $relaciones->save();
    }
    public function selectCuentasConciliacion(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $cuentaslibros = Con_Configuracion::join('con__cuentas as a','a.idcuenta','=','con__configuracions.valor')
                                            ->select('valor','codcuenta','nomcuenta')
                                            ->where('codigo','LB')
                                            ->where('con__configuracions.activo',1)
                                            ->get();
        
        return ['cuentaslibros'=>$cuentaslibros];

    }
}
