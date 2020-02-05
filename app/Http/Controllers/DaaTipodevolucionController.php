<?php

namespace App\Http\Controllers;

use App\Daa_Tipodevolucion;
use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DaaTipodevolucionController extends Controller
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
            // $tipodevolucions = Daa_Tipodevolucion::join('configs','daa__tipodevolucions.idconfig','configs.idconfig')
            // ->select('configs.*','daa__tipodevolucions.*')
            // ->orderBy('idtipodevolucion', 'desc')->paginate(10);
            $tipodevolucions = Daa_Tipodevolucion::select('daa__tipodevolucions.*')
            ->orderBy('idtipodevolucion', 'asc')->paginate(10);
        }
        else{
            $tipodevolucions = Daa_Tipodevolucion::where('nomtipodevolucion', 'like', '%'. $buscar . '%')->orderBy('idtipodevolucion', 'asc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $tipodevolucions->total(),
                'current_page' => $tipodevolucions->currentPage(),
                'per_page'     => $tipodevolucions->perPage(),
                'last_page'    => $tipodevolucions->lastPage(),
                'from'         => $tipodevolucions->firstItem(),
                'to'           => $tipodevolucions->lastItem(),
            ],
            'tipodevolucions' => $tipodevolucions
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
        $validator = Validator::make($request->all(), [
            'nomtipodevolucion' => 'unique:daa__tipodevolucions'
        ]);         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        
        if (!$request->ajax()) return redirect('/');      
        $tipodevolucion = new Daa_Tipodevolucion();
        $tipodevolucion->nomtipodevolucion = $request->nomtipodevolucion;
        $tipodevolucion->aporteminimo = $request->cantidadaporte;
        $tipodevolucion->porcentaje = $request->porcentaje;
        $tipodevolucion->valido = $request->valido;
        $tipodevolucion->activo = '1';
        $tipodevolucion->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Daa_Tipodevolucion  $daa_Tipodevolucion
     * @return \Illuminate\Http\Response
     */
    public function show(Daa_Tipodevolucion $daa_Tipodevolucion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Daa_Tipodevolucion  $daa_Tipodevolucion
     * @return \Illuminate\Http\Response
     */
    public function edit(Daa_Tipodevolucion $daa_Tipodevolucion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Daa_Tipodevolucion  $daa_Tipodevolucion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'nomtipodevolucion' => 'unique:daa__tipodevolucions'
        // ]);
         
        // if ($validator->fails()) {
        //     return ($validator->messages()->first());
        // }
        
        if (!$request->ajax()) return redirect('/');

        $tipodevolucion = Daa_Tipodevolucion::findOrFail($request->idtipodevolucion);
        $tipodevolucion->nomtipodevolucion = $request->nomtipodevolucion;
        $tipodevolucion->aporteminimo = $request->cantidadaporte;
        $tipodevolucion->porcentaje = $request->porcentaje;
        $tipodevolucion->valido = $request->valido;
        $tipodevolucion->activo = '1';
        $tipodevolucion->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Daa_Tipodevolucion  $daa_Tipodevolucion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Daa_Tipodevolucion $daa_Tipodevolucion)
    {
        //
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tipodevolucion = Daa_Tipodevolucion::findOrFail($request->idtipodevolucion);
        $tipodevolucion->activo = '0';
        $tipodevolucion->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tipodevolucion = Daa_Tipodevolucion::findOrFail($request->idtipodevolucion);
        $tipodevolucion->activo = '1';
        $tipodevolucion->save();
    }

    public function selectValoraporte(Request $request){
        if (!$request->ajax()) return redirect('/');

        $aportes = Config::orderBy('idconfig', 'asc')
        ->where('codigo','like','%DA%')->get();
        return ['aportes' => $aportes];
    }


}
