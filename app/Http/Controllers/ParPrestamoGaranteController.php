<?php

namespace App\Http\Controllers;

use App\par_prestamo_garante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParPrestamoGaranteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $fecha=(DB::select("select getfecha() as total"))[0]->total;
        $garante = new par_prestamo_garante(); 
        $garante->idsocio = $request->idsocio; 
        $garante->factor= $request->factor; 
        $garante->idprestamo= $request->idprestamo; 
        $garante->fecharegistro= $fecha;  
        $garante->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\par_prestamo_garante  $par_prestamo_garante
     * @return \Illuminate\Http\Response
     */
    public function show(par_prestamo_garante $par_prestamo_garante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\par_prestamo_garante  $par_prestamo_garante
     * @return \Illuminate\Http\Response
     */
    public function edit(par_prestamo_garante $par_prestamo_garante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\par_prestamo_garante  $par_prestamo_garante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, par_prestamo_garante $par_prestamo_garante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\par_prestamo_garante  $par_prestamo_garante
     * @return \Illuminate\Http\Response
     */
    public function destroy(par_prestamo_garante $par_prestamo_garante)
    {
        //
    }
}
