<?php

namespace App\Http\Controllers;
use App\Par_Productos_Factores;
use Illuminate\Http\Request;

class ParProductoFactoresController extends Controller
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
    public function selectfactores(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $factores = Par_Productos_Factores::orderBy('idfactor', 'asc')
       ->where('activo','=','1')
        ->get();
        return ['factores' => $factores];
    }
    public function factores(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $factores = Par_Productos_Factores::orderBy('idfactor', 'asc') 
        ->paginate(10);
        return ['pagination' => [
            'total'        => $factores->total(),
            'current_page' => $factores->currentPage(),
            'per_page'     => $factores->perPage(),
            'last_page'    => $factores->lastPage(),
            'from'         => $factores->firstItem(),
            'to'           => $factores->lastItem(),
        ],'factores' => $factores];
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $factores = Par_Productos_Factores::findOrFail($request->id);
        $factores->activo = '0';
        $factores->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $factores = Par_Productos_Factores::findOrFail($request->id);
        $factores->activo = '1';
        $factores->save();
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
        $factores = new Par_Productos_Factores(); 
        $factores->nombrefactor=$request->nombre;
        $factores->descripcion=$request->des;
        $factores->aprobacion=$request->apro;
        $factores->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
