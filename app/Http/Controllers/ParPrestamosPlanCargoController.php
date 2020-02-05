<?php

namespace App\Http\Controllers;

use App\Par_prestamos_plan_cargo;
use Illuminate\Http\Request;

class ParPrestamosPlanCargoController extends Controller
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
        $prestamo = new Par_prestamos_plan_cargo();  
        $prestamo->idplan=$request->idplan;
        $prestamo->idperfilcuentadetalle=$request->idperfilcuentadetalle;
        $prestamo->cargo=$request->cargo; 
        $prestamo->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_prestamos_plan_cargo  $par_prestamos_plan_cargo
     * @return \Illuminate\Http\Response
     */
    public function show(Par_prestamos_plan_cargo $par_prestamos_plan_cargo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Par_prestamos_plan_cargo  $par_prestamos_plan_cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_prestamos_plan_cargo $par_prestamos_plan_cargo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Par_prestamos_plan_cargo  $par_prestamos_plan_cargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Par_prestamos_plan_cargo $par_prestamos_plan_cargo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Par_prestamos_plan_cargo  $par_prestamos_plan_cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Par_prestamos_plan_cargo $par_prestamos_plan_cargo)
    {
        //
    }
}
