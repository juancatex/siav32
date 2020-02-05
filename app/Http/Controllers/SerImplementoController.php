<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ser_Implemento;

class SerImplementoController extends Controller
{
    public function listaImplementos(Request $request)
    {   
        $implementos=Ser_Implemento::select('*');
        if($request->activo) $implementos=$implementos->where('activo',1);
        return ['implementos'=>$implementos->get()];
    }

    public function storeImplemento(Request $request)
    {   
        $implemento = new Ser_Implemento();
        $implemento->nomimplemento = $request->nomimplemento;
        $implemento->save();
    }    

    public function updateImplemento(Request $request)
    {   
        $implemento=Ser_Implemento::findOrFail($request->idimplemento);
        $implemento->nomimplemento = $request->nomimplemento;
        $implemento->save();        
    }

    public function switchImplemento(Request $request)
    {   
        $implemento=Ser_Implemento::findOrFail($request->idimplemento);
        $implemento->activo=abs($implemento->activo-1);
        $implemento->save();
    }
}
