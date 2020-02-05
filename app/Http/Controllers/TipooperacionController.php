<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipooperacionController extends Controller
{
    public function selectTipooperacion(Request $request){
        //if (!$request->ajax()) return redirect('/');
        
        /*
        $tipooperaciones = Apo_Canal::
        select('idcanal','nomcanal')
        ->where('activo','=','1')
        ->orderBy('idcanal', 'asc')->get();
        */
        $tipooperaciones=config('app.tipo_operacion');
        return ['tipooperaciones' => $tipooperaciones];
    
    }
}

  