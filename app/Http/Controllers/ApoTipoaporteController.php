<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Apo_Tipoaporte;
use Illuminate\Support\Facades\Validator;

class ApoTipoaporteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //$tipoaportes = Apo_Tipoaporte::orderBy('idtipoaporte', 'desc')->paginate(10);
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $tipoaportes = Apo_Tipoaporte::where('activo',1)->orderBy('descripcion', 'desc')->paginate(10);
        }
        else{
            $tipoaportes = Apo_Tipoaporte::where('descripcion','like', '%'.$buscar.'%')
                                            ->where('activo',1)
                                            ->orderBy('descripcion', 'desc')->paginate(10);
        }




        return [
            'pagination' => [
                'total'        => $tipoaportes->total(),
                'current_page' => $tipoaportes->currentPage(),
                'per_page'     => $tipoaportes->perPage(),
                'last_page'    => $tipoaportes->lastPage(),
                'from'         => $tipoaportes->firstItem(),
                'to'           => $tipoaportes->lastItem(),
            ],
            'tipoaportes' => $tipoaportes
        ];
    }

    public function selectTipoaporte(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $tipoaportes = Apo_Tipoaporte::where('activo','=','1')
        ->select('idtipoaporte','descripcion')->orderBy('descripcion', 'asc')->get();
        return ['tipoaportes' => $tipoaportes];
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
            'descripcion' => 'unique:apo__tipoaportes'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        

        $tipoaporte = new Apo_Tipoaporte();
        
        $tipoaporte->descripcion = $request->descripcion;
       // $tipoaporte->idcuentacontable = $request->idcuenta;
        $tipoaporte->activo = '1';
        $tipoaporte->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idtipoaporte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'unique:apo__tipoaportes'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        
        
        if (!$request->ajax()) return redirect('/');

        $tipoaporte = Apo_Tipoaporte::findOrFail($request->idtipoaporte);
        $tipoaporte->descripcion = $request->descripcion;
       // $tipoaporte->idcuentacontable= $request->idcuenta;
        $tipoaporte->activo = '1';
        $tipoaporte->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $tipoaporte = Apo_Tipoaporte::findOrFail($request->idtipoaporte);

        $tipoaporte->activo = '0';
        $tipoaporte->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $tipoaporte = Apo_Tipoaporte::findOrFail($request->idtipoaporte);
        $tipoaporte->activo = '1';
        $tipoaporte->save();
    }
}
