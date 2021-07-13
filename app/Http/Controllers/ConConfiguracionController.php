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
        $dias=$request->dias;
        $diasoc='';
        $diasor='';
       
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

        if($dias)
        {
            $respuesta=$this->recuperarccdias();
            
            $diasor=$respuesta['diasor'];
            $diasoc=$respuesta['diasoc'];
            
            
            //echo $diasor.'->'.$diasoc;

        }

        return [
            'pagination' => [
                'total'        => $relaciones->total(),
                'current_page' => $relaciones->currentPage(),
                'per_page'     => $relaciones->perPage(),
                'last_page'    => $relaciones->lastPage(),
                'from'         => $relaciones->firstItem(),
                'to'           => $relaciones->lastItem(),
            ],
            'relaciones' => $relaciones,
            'diasor'=>$diasor,
            'diasoc'=>$diasoc
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
        $dias=$request->dias;
        $relaciones = Con_Configuracion::findOrFail($request->idconconfig);
        if($dias)
        DB::table('con__configuracions')->where('codigo','ccdiasor')
                                            ->orwhere('codigo','ccdiasoc')
                                            ->update(['activo' => 0]);

        $relaciones->activo = '0';
        $relaciones->save();
    }

    public function desactivarccdias(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        DB::table('con__configuracions')->where('codigo','ccdiasor')
                                            ->orwhere('codigo','ccdiasoc')
                                            ->update(['activo' => 0]);
        $ccdiasor=$request->ccdiasor;
        $ccdiasoc=$request->ccdiasoc;
        $relaciones = new Con_Configuracion();
        
        $relaciones->codigo = 'ccdiasor';
        $relaciones->descripcion ='limite dias oficina regional';
        $relaciones->valor =$ccdiasor;
        $relaciones->tipoconfiguracion=4;
        $relaciones->save();

        $relaciones = new Con_Configuracion();
        
        $relaciones->codigo = 'ccdiasoc';
        $relaciones->descripcion ='limite dias oficina central';
        $relaciones->valor =$ccdiasoc;
        $relaciones->tipoconfiguracion=4;
        $relaciones->save();


        
    }
    public function recuperarccdias(){
        $respuestadias=Con_Configuracion::where('activo',1)
                                                ->where(function($query) {
                                                    $query->where('codigo', 'ccdiasoc')
                                                          ->orWhere('codigo', 'ccdiasor');
                                                })->get()->toarray();
                                                
        foreach ($respuestadias as $key => $value) {
            if($value['codigo']=='ccdiasor')
                $diasor=$value['valor'];
            elseif ($value['codigo']=='ccdiasoc') {
                $diasoc=$value['valor'];
            }
        }
        return ['diasor'=>$diasor,
                'diasoc'=>$diasoc];

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
    public function selectSubCuentaAscinalss()
    {
        //if (!$request->ajax()) return redirect('/');

        $subasc = Con_Configuracion::where('codigo','SubASC')
                                     ->where('con__configuracions.activo',1)
                                     ->get();
        
        return ['subasc'=>$subasc];

    }
}
