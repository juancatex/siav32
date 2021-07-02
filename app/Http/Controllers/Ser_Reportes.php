<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Ser_Reportes extends Controller
{
    public function rutas(Request $request)
    {   
      //  if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaServicios');  
        return [ 	
        'REP_REGISTRO' => $rutas['REP_REGISTRO'].'&user='.Auth::user()->username.'&id=',        
        'REP_REGISTRO_TODO' => $rutas['REP_REGISTRO_TODO'].'&user='.Auth::user()->username.'&id=',        
        'REP_REGISTRO_SALIDA' => $rutas['REP_REGISTRO_SALIDA'].'&user='.Auth::user()->username.'&id=',        
        'REP_PERMANENTE' => $rutas['REP_PERMANENTE'].'&user='.Auth::user()->username.'&id=',        
        'REP_PERMANENTE_SOCIO' => $rutas['REP_PERMANENTE_SOCIO'].'&user='.Auth::user()->username,        
        'REP_ENTRADACC' => $rutas['REP_ENTRADACC'].'&user='.Auth::user()->username,        
        'REP_SALIDACC' => $rutas['REP_SALIDACC'].'&user='.Auth::user()->username,        
        //'REP_SALIDACC' => 'http://'.request()->server('SERVER_ADDR').$rutas['REP_SALIDACC'].'&user='.Auth::user()->username,        
        'REP_PAGOMAUSOLEO' => $rutas['REP_PAGOMAUSOLEO'].'&user='.Auth::user()->username,        
        'REP_EXTRACTO_MAUSOLEO' => $rutas['REP_EXTRACTO_MAUSOLEO'].'&user='.Auth::user()->username,        
        'REP_PAGOMAUSOLEO_BE' => $rutas['REP_PAGOMAUSOLEO_BE'].'&user='.Auth::user()->username,        
        'REP_EXTRACTO_MAUSOLEO_BE' => $rutas['REP_EXTRACTO_MAUSOLEO_BE'].'&user='.Auth::user()->username,        
        ];
    }
}
