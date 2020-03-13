<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Act_Asignacion;


class ActAsignacionController extends Controller
{
    public function listaAsignaciones(Request $request)
    {   
        $asignaciones=Act_Asignacion::selectRaw("act__asignacions.*, act__ambientes.nomambiente, par_municipios.nommunicipio,
            if(tiporesponsable='s',
            (select concat(nomgrado,' ',nombre,' ',apaterno) from socios 
                join par_grados on par_grados.idgrado=socios.idgrado where idsocio=act__asignacions.idresponsable),
            (select concat(nombre,' ',apaterno) from rrh__empleados where idempleado=act__asignacions.idresponsable)) 
                as nomresponsable");
        $asignaciones->join('act__activos','act__asignacions.idactivo','act__activos.idactivo');
        $asignaciones->join('act__ambientes','act__activos.idambiente','act__ambientes.idambiente');
        $asignaciones->join('fil__filials','act__activos.idfilial','fil__filials.idfilial');
        $asignaciones->join('par_municipios','fil__filials.idmunicipio','par_municipios.idmunicipio');
        if($request->idactivo) $asignaciones->where('act__asignacions.idactivo',$request->idactivo)->orderBy('idasignacion','desc');
        if($request->idfilial) { $asignaciones
            ->where('act__activos.idfilial',$request->idfilial)
            ->where('act__activos.idoficina',$request->idoficina)
            ->where('act__activos.idgrupo',$request->idgrupo);
            if($request->idauxiliar) $activos->where('act__activos.idauxiliar',$request->idauxiliar);
        }
        return['asignaciones'=>$asignaciones->get(),'ipbirt'=>$_SERVER['SERVER_ADDR']];
    }

    public function listaResponsables(Request $request)
    {
        $responsables=Act_Asignacion::selectRaw("act__activos.idactivo, 
            if(act__asignacions.tiporesponsable='s',
                (select CONCAT(nomgrado,' ',nombre, ' ', apaterno,' ',amaterno) from socios 
                inner join par_grados on par_grados.idgrado=socios.idgrado where idsocio=act__asignacions.idresponsable ),
                (select CONCAT(nombre, ' ', apaterno,' ',amaterno) from rrh__empleados where idempleado=act__asignacions.idresponsable)) 
                as nomresponsable")
        ->join('act__activos','act__activos.idactivo','=','act__asignacions.idactivo')
        ->where('activo','=',1)
        ->where('act__activos.idfilial','=',$request->idfilial)
        ->where('act__activos.idoficina','=',$request->idoficina)
        ->where('act__activos.idcuenta','=',$request->idcuenta);
        if($request->idauxiliar) $activos=$activos->where('act__activos.idauxiliar','=',$request->idauxiliar);
        $responsables=$responsables->orderBy('idactivo')->get();

        return ['responsables'=>$responsables];
    }

    /*
    public function verResponsable(Request $request)
    {   
        $responsable=Act_Asignacion::selectRaw("if(tiporesponsable='s',
        (select concat(nomgrado,' ',nombre,' ',apaterno) from socios 
            join par_grados on par_grados.idgrado=socios.idgrado where idsocio=act__asignacions.idresponsable),
        (select concat(nombre,' ',apaterno) from rrh__empleados where idempleado=act__asignacions.idresponsable)) 
            as nomresponsable")
        ->where('idactivo','=',$request->idactivo)
        ->where('activo','=',1)->get();
        return ['responsable'=>$responsable[0]];
    }
    */

    public function storeAsignacion(Request $request)
    {
        $asignacion=Act_Asignacion::where('idactivo',$request->idactivo)->update(['activo'=>0]);
        $asignacion=new Act_Asignacion();
        $asignacion->idactivo=$request->idactivo;
        $asignacion->idresponsable=$request->idresponsable;
        $asignacion->tiporesponsable=$request->tiporesponsable;
        $asignacion->fechaini=$request->fechaini;
        $asignacion->estadoini=$request->estadoini;
        $asignacion->activo=1;
        $asignacion->obs=$request->obs;
        $asignacion->save();
    }

    public function updateAsignacion(Request $request)
    {   
        $asignacion=Act_Asignacion::findOrFail($request->idasignacion);
        $asignacion->idresponsable=$request->idresponsable;
        $asignacion->tiporesponsable=$request->tiporesponsable;
        $asignacion->fechaini=$request->fechaini;
        $asignacion->fechafin=$request->fechafin;
        $asignacion->estadoini=$request->estadoini;
        $asignacion->estadofin=$request->estadofin;        
        $asignacion->obs=$request->obs;
        //si existe fecha de devolucion y estado devolucion -> cambiamos de estado
        if ($request->fechafin && $request->estadofin)
            $asignacion->activo=0;
        $asignacion->save();
    }
    /*
    public function activarAsignacion(Request $request)
    {   $asignacion=Act_Asignacion::where('idactivo',$request->idactivo)->update(['activo'=>0]);
        $asignacion=Act_Asignacion::where('idasignacion',$request->idasignacion)->update(['activo'=>1]);
    }
    */

}
