<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Ventanamodulo;

class AdmVentanamoduloController extends Controller
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
            $ventanamodulos = Par_Ventanamodulo::join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')      
            ->select('par__modulos.*','par__ventanamodulos.*')
            ->orderBy('idventanamodulo', 'desc')->paginate(10);
        }
        else{
            $ventanamodulos = Par_Ventanamodulo::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idventanamodulo', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $ventanamodulos->total(),
                'current_page' => $ventanamodulos->currentPage(),
                'per_page'     => $ventanamodulos->perPage(),
                'last_page'    => $ventanamodulos->lastPage(),
                'from'         => $ventanamodulos->firstItem(),
                'to'           => $ventanamodulos->lastItem(),
            ],
            'ventanamodulos' => $ventanamodulos
        ];
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
            'template' => 'unique:par__ventanamodulos'
        ]);
         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        $tem = $request->template;
        
        if (!$request->ajax()) return redirect('/');
        $ventanamodulo = new Par_Ventanamodulo();
        $ventanamodulo->nomventanamodulo = $request->nomventanamodulo;
        //$ventanamodulo->template = '<'.$tem.'></'.$tem.'>';
        $ventanamodulo->template = $tem;
        $ventanamodulo->idmodulo = $request->idmodulo;
        $ventanamodulo->activo = '1';
        $ventanamodulo->save();
    }

 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $tem = $request->template;
        $ventanamodulo = Par_Ventanamodulo::findOrFail($request->idventanamodulo); 
        $ventanamodulo->nomventanamodulo = $request->nomventanamodulo;
        //$ventanamodulo->template = '<'.$tem.'></'.$tem.'>';
        $ventanamodulo->template = $tem;
        $ventanamodulo->idmodulo = $request->idmodulo;
        $ventanamodulo->activo = '1';
        $ventanamodulo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $ventanamodulo = Par_Ventanamodulo::findOrFail($request->idventanamodulo);
        $ventanamodulo->activo = '0';
        $ventanamodulo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ventanamodulo = Par_Ventanamodulo::findOrFail($request->idventanamodulo);
        $ventanamodulo->activo = '1';
        $ventanamodulo->save();
    }
}
