<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Estadomodulo;

class Par_EstadomoduloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $estados = Par_Estadomodulo::join('par__modulos','par__modulos.idmodulo','=','par_estadomodulos.idmodulo')
            ->select('par_estadomodulos.idestadomodulo','par_estadomodulos.nomestado','par_estadomodulos.activo','par_estadomodulos.idestado','par__modulos.nommodulo','par__modulos.idmodulo')
            ->orderBy('idestadomodulo', 'desc')->paginate(10);            
        }
        else{
            $estados = Par_Estadomodulo::join('par__modulos','par__modulos.idmodulo','=','par_estadomodulos.idmodulo')
            ->select('par_estadomodulos.idestadomodulo','par_estadomodulos.nomestado','par_estadomodulos.activo','par_estadomodulos.idestado','par__modulos.nommodulo','par__modulos.idmodulo')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('idestado', 'desc')
            ->orderBy('idestadomodulo', 'desc')->paginate(10);                        
        }        

        return [
            'pagination' => [
                'total'        => $estados->total(),
                'current_page' => $estados->currentPage(),
                'per_page'     => $estados->perPage(),
                'last_page'    => $estados->lastPage(),
                'from'         => $estados->firstItem(),
                'to'           => $estados->lastItem(),
            ],
            'estados' => $estados
        ];
    }
    
    public function afiliacion(Request $request){
        if (!$request->ajax()) return redirect('/');

        $estados = Par_Estadomodulo::where('activo','=','1')
        ->where('idmodulo','=','1')
        ->orderBy('nomestado', 'asc')->get();
        return ['estados' => $estados];
    }
    public function selectEstadomodulo(Request $request){
        if (!$request->ajax()) return redirect('/');

        $estados = Par_Estadomodulo::where('activo','=','1')
        ->select('idestadomodulo','nomestado')->orderBy('nomestado', 'asc')->get();
        return ['estados' => $estados];
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
        
        $estado = new Par_Estadomodulo(); 

        $estado->nomestado = $request->nomestado;
        $estado->idmodulo = $request->idmodulo;
        $estado->idestado = $request->idestado;
        $estado->activo = '1';
        $estado->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idestado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'nomestado' => 'unique:par_estadomodulos'
        // ]);

        // if ($validator->fails()) {       
        //     return ($validator->messages()->first());
        // }
                       
        if (!$request->ajax()) return redirect('/');

        $estado = Par_Estadomodulo::findOrFail($request->idestadomodulo);
        $estado->nomestado = $request->nomestado;
        $estado->idmodulo = $request->idmodulo;
        $estado->idestado = $request->idestado;
        $estado->activo = '1';
        $estado->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estado = Par_Estadomodulo::findOrFail($request->idestado);

        $estado->activo = '0';
        $estado->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estado = Par_Estadomodulo::findOrFail($request->idestado);
        $estado->activo = '1';
        $estado->save();
    }

}
