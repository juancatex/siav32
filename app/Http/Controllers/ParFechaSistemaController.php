<?php

namespace App\Http\Controllers;

use App\Par_Fecha_Sistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParFechaSistemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if (!$request->ajax()) return redirect('/'); 
        if ($request->modal)
        {
            return  DB::select('select getfecha2() as fecha');     
        }
        else 
        {
            return  DB::select('select getfecha() as fecha');     
        }
        
    }
    public function calculoplanpagos(Request $request)
    {
       if (!$request->ajax()) return redirect('/');  
        return  DB::select('select checkcut() as corte, getfecha() as fecha, getfechas() as fechas');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_Fecha_Sistema  $par_Fecha_Sistema
     * @return \Illuminate\Http\Response
     */
    public function show(Par_Fecha_Sistema $par_Fecha_Sistema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Par_Fecha_Sistema  $par_Fecha_Sistema
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_Fecha_Sistema $par_Fecha_Sistema)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Par_Fecha_Sistema  $par_Fecha_Sistema
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Par_Fecha_Sistema $par_Fecha_Sistema)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Par_Fecha_Sistema  $par_Fecha_Sistema
     * @return \Illuminate\Http\Response
     */
    public function destroy(Par_Fecha_Sistema $par_Fecha_Sistema)
    {
        //
    }
}
