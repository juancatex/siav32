<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Par_Grado;

class Par_GradoController extends Controller
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
            $grados = Par_Grado::select('idgrado','nomgrado','activo')
            ->orderBy('idgrado', 'desc')->paginate(10);

        }
        else{
            $grados = Par_Grado::select('idgrado','nomgrado','activo')
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('idgrado', 'desc')->paginate(10);

            
        }
        

        return [
            'pagination' => [
                'total'        => $grados->total(),
                'current_page' => $grados->currentPage(),
                'per_page'     => $grados->perPage(),
                'last_page'    => $grados->lastPage(),
                'from'         => $grados->firstItem(),
                'to'           => $grados->lastItem(),
            ],
            'grados' => $grados
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
        if (!$request->ajax()) return redirect('/');

        $grado = new Par_Grado(); 
        $grado->nomgrado = $request->nomgrado; 
        $grado->activo = '1';
        $grado->save();
    }
  
    public function selectGrado(Request $request){
        if (!$request->ajax()) return redirect('/');

        $grados = Par_Grado::where('activo','=','1')
        ->select('idgrado','nomgrado')->orderBy('nomgrado', 'asc')->get();
        return ['grados' => $grados];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idgrado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $grado = Par_Grado::findOrFail($request->idgrado); 
        $grado->nomgrado = $request->nomgrado; 
        $grado->activo = '1';
        $grado->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $grado = Par_Grado::findOrFail($request->idgrado);

        $grado->activo = '0';
        $grado->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $grado = Par_Grado::findOrFail($request->idgrado);
        $grado->activo = '1';
        $grado->save();
    }
}
