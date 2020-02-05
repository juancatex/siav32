<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApoReportesController extends Controller
{
    public function rutas(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaApoReportes');  
        return [ 
		'REP_ASCII' => $rutas['REP_ASCII'],
        'REP_OBSASCII' => $rutas['REP_OBSASCII'],
        'REP_NUEVOSASCII'=> $rutas['REP_NUEVOSASCII'],
        'REP_APO_VERTICAL'  => $rutas['REP_APO_VERTICAL'],
        'REP_APO_HORIZONTAL'=> $rutas['REP_APO_HORIZONTAL'],
        'REP_APO_ABONO'     => $rutas['REP_APO_ABONO'],
        'REP_APO_DEBITO'    => $rutas['REP_APO_DEBITO'],
        'REP_APO_FALTANTES' => $rutas['REP_APO_FALTANTES'],
        'REP_APO_DEBITO_ASCII'=> $rutas['REP_APO_DEBITO_ASCII'],
        'REP_APO_DEVOLUCION'=>$rutas['REP_APO_DEVOLUCION'],
        'REP_DETALLEASCII'=>$rutas['REP_DETALLEASCII'],
        ];
    }
}
