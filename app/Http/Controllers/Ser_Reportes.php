<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Ser_Reportes extends Controller
{
    public function rutas(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaServicios');  
        return [ 	
        'REP_REGISTRO' => $rutas['REP_REGISTRO'].'&user='.Auth::user()->username.'&id=',        
        'REP_PERMANENTE' => $rutas['REP_PERMANENTE'].'&user='.Auth::user()->username.'&id=',        
        'REP_ENTRADACC' => $rutas['REP_ENTRADACC'].'&user='.Auth::user()->username,        
        'REP_SALIDACC' => $rutas['REP_SALIDACC'].'&user='.Auth::user()->username,        
        ];
    }
}
