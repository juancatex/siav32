<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ser_Servicio;

class SerServicioController extends Controller
{
    public function listaServicios(Request $request)
    {   
        $servicios=Ser_Servicio::select('*');
        if($request->activo) $servicios=$servicios->where('activo',1);
        return ['servicios'=>$servicios->get()];
    }

    public function storeServicio(Request $request)
    {   
        $servicio = new Ser_Servicio();
        $servicio->codservicio = $request->codservicio;
        $servicio->nomservicio = $request->nomservicio;
        $servicio->descripcion = $request->descripcion;
        $servicio->save();
    }    

    public function updateServicio(Request $request)
    {   
        $servicio=Ser_Servicio::findOrFail($request->idservicio);
        $servicio->codservicio = $request->codservicio;
        $servicio->nomservicio = $request->nomservicio;
        $servicio->descripcion = $request->descripcion;
        $servicio->save();        
    }

    public function switchServicio(Request $request)
    {   
        $servicio=Ser_Servicio::findOrFail($request->idservicio);
        $servicio->activo=abs($servicio->activo-1);
        $servicio->save();
    }
}
