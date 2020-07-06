<?php

namespace App\Http\Controllers;

use App\Par_prestamos_plan_cargo;
use Illuminate\Http\Request;

class ParPrestamosPlanCargoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
 
}
