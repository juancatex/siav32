<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Par_Tiposocio;

class ParTiposocioController extends Controller
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
            $tiposocios = Par_Tiposocio::orderBy('idtiposocio', 'desc')->paginate(10);
        }
        else{
            $tiposocios = Par_Tiposocio::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idtiposocio', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $tiposocios->total(),
                'current_page' => $tiposocios->currentPage(),
                'per_page'     => $tiposocios->perPage(),
                'last_page'    => $tiposocios->lastPage(),
                'from'         => $tiposocios->firstItem(),
                'to'           => $tiposocios->lastItem(),
            ],
            'tiposocios' => $tiposocios
        ];
    }

    public function selectTiposocio(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $tiposocios = Par_Tiposocio::where('activo','=','1')
        ->select('idtiposocio','nomtiposocio')->orderBy('nomtiposocio', 'asc')->get();
        return ['tiposocios' => $tiposocios];
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
            'nomtiposocio' => 'unique:par__tiposocios'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        

        $tiposocio = new Par_Tiposocio();
        
        $tiposocio->nomtiposocio = $request->nomtiposocio;
        $tiposocio->activo = '1';
        $tiposocio->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idtiposocio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomtiposocio' => 'unique:par__tiposocios'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        
        
        if (!$request->ajax()) return redirect('/');

        $tiposocio = Par_Tiposocio::findOrFail($request->idtiposocio);
        $tiposocio->nomtiposocio = $request->nomtiposocio;
        $tiposocio->activo = '1';
        $tiposocio->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $tiposocio = Par_Tiposocio::findOrFail($request->idtiposocio);

        $tiposocio->activo = '0';
        $tiposocio->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $tiposocio = Par_Tiposocio::findOrFail($request->idtiposocio);
        $tiposocio->activo = '1';
        $tiposocio->save();
    }
}
