<?php

namespace App\Http\Controllers;
use App\Par_Prestamos_Escala; 
use Illuminate\Http\Request;

class ParPrestamosEscalaController extends Controller
{



   /* SELECT maxmonto FROM `par__escalas` WHERE idescala = 4 and minanios<=9 and maxanios>=9*/
 
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $escalas = Par_Prestamos_Escala::orderBy('nomescala', 'asc')->get();
        return ['escalas' => $escalas ];
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

        $escala = new Par_Prestamos_Escala(); 
        $escala->nomescala = $request->nomescala; 
        $escala->save();  
       /* $escala->idescala;*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_Prestamos_Escala  $par_Prestamos_Escala
     * @return \Illuminate\Http\Response
     */
    public function show(Par_Prestamos_Escala $par_Prestamos_Escala)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Par_Prestamos_Escala  $par_Prestamos_Escala
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_Prestamos_Escala $par_Prestamos_Escala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Par_Prestamos_Escala  $par_Prestamos_Escala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Par_Prestamos_Escala $par_Prestamos_Escala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Par_Prestamos_Escala  $par_Prestamos_Escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Par_Prestamos_Escala $par_Prestamos_Escala)
    {
        //
    }
}
