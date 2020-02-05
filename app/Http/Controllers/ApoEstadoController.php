<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apo_Estado;
use Illuminate\Support\Facades\Validator;

class ApoEstadoController extends Controller
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
            $estados = Apo_Estado::orderBy('idestado', 'asc')->paginate(10);
        }
        else{
            $estados = Apo_Estado::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idestado', 'asc')->paginate(10);
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

    public function selectEstado(Request $request){
        if (!$request->ajax()) return redirect('/');
        $estados = Apo_Estado::
        select('idestado','nomestado')
        ->where('activo','=','1')
        ->orderBy('idestado', 'asc')->get();
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
        
        $validator = Validator::make($request->all(), [
            'nomestado' => 'unique:apo__estados'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        

        $estado = new Apo_Estado();
        
        $estado->nomestado = ucwords(strtolower($request->nomestado));
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
        $validator = Validator::make($request->all(), [
            'nomestado' => 'unique:apo__estados'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        if (!$request->ajax()) return redirect('/');

        $estado = Apo_Estado::findOrFail($request->idestado);
        $estado->nomestado = $request->nomestado;
        $estado->activo = '1';
        $estado->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estado = Apo_Estado::findOrFail($request->idestado);

        $estado->activo = '0';
        $estado->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estado = Apo_Estado::findOrFail($request->idestado);
        $estado->activo = '1';
        $estado->save();
    }
}
