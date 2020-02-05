<?php

namespace App\Http\Controllers;

use App\Daa_Estudiomatematico;
use Illuminate\Http\Request;

class DaaEstudiomatematicoController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Daa_Estudiomatematico  $daa_Estudiomatematico
     * @return \Illuminate\Http\Response
     */
    public function show(Daa_Estudiomatematico $daa_Estudiomatematico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Daa_Estudiomatematico  $daa_Estudiomatematico
     * @return \Illuminate\Http\Response
     */
    public function edit(Daa_Estudiomatematico $daa_Estudiomatematico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Daa_Estudiomatematico  $daa_Estudiomatematico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Daa_Estudiomatematico $daa_Estudiomatematico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Daa_Estudiomatematico  $daa_Estudiomatematico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Daa_Estudiomatematico $daa_Estudiomatematico)
    {
        //
    }

    public function selectEstudiomatematico(Request $request){
        if (!$request->ajax()) return redirect('/');

            $estudiomatematico = Daa_Estudiomatematico::where('activo', '=', '1')->get(); 
            return ['estudiomatematico' => $estudiomatematico];        
    }
}
