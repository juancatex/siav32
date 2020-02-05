<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Con_Cierrelibrocompra;

class ConCierrelibrocompraController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');  //quitar y ver el objeto json

        $mes = $request->mes;
        $anio = $request->anio;
        $verdad = 0;
        $respuesta = Con_Cierrelibrocompra::where('mes',$mes)
                                            ->where('anio',$anio)
                                        ->count();
        //dd($respuesta);
        $verdad=0;

        if($respuesta>0)
        $verdad=1;    

        return $verdad;
    }
    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        /* $validator = Validator::make($request->all(), [
            'nommunicipio'=>'unique:par_municipios'
        ]); 
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }*/
        
        
        $cierrelibrocompra = new Con_Cierrelibrocompra();
        $cierrelibrocompra->mes = $request->mes;
        $cierrelibrocompra->anio= $request->anio;
        $cierrelibrocompra->save();
    }

}
