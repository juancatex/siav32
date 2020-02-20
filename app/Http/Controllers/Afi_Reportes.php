<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Afi_Reportes extends Controller
{
    public function rutas(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaReportes');  
        return [ 
		'RUTE_REPORT'=> $rutas['RUTE_REPORT'].'&user='.Auth::user()->id.'&id=',
		'REP_KARDEX' => $rutas['REP_KARDEX'].'&user='.Auth::user()->id.'&id=',
		'REP_FUERZA' => $rutas['REP_FUERZA'].'&user='.Auth::user()->id.'&id=',
        'REP_EGRESO' => $rutas['REP_EGRESO'].'&user='.Auth::user()->id.'&id=',
        'REP_INSCRIPCION' => $rutas['REP_INSCRIPCION'].'&user='.Auth::user()->id.'&id=',
        'REP_RESUMEN' => $rutas['REP_RESUMEN'].'&user='.Auth::user()->id.'&id=',  // mover a contabilidad
        'REP_CARNET' => $rutas['REP_CARNET'],
        'SER_CASACOM_ENT'=> $rutas['SER_CASACOM_ENT'],
        'SER_CASACOM_SAL'=> $rutas['SER_CASACOM_SAL'],
        ];
    }
}
