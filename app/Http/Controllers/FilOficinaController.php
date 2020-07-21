<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fil_Oficina;

class FilOficinaController extends Controller
{
    public function listaOficinas(Request $request)
    {      
        $ip=config('app.ip'); 
        if($request->responsables)
        {
            $oficinas=Fil_Oficina::selectRaw("fil__oficinas.*,nomunidad,
            if(fil__oficinas.tiporesponsable='s',
            (select concat(nomgrado,' ',nombre,' ',apaterno) from socios  
                join par_grados on par_grados.idgrado=socios.idgrado where idsocio=fil__oficinas.idresponsable),
            (select concat(nombre,' ',apaterno) from rrh__empleados where idempleado=fil__oficinas.idresponsable)) 
                as nomresponsable");
        }
        else $oficinas=Fil_Oficina::select('idoficina','idfilial','codoficina','nomoficina','nomunidad');
        
        $oficinas->join('fil__unidads','fil__unidads.idunidad','fil__oficinas.idunidad')
        ->where('idfilial',$request->idfilial);
        if($request->idunidad) $oficinas->where('fil__unidads.idunidad',$request->idunidad);
        else $oficinas->where('fil__oficinas.activo',$request->activo);
        if($request->orden) $oficinas->orderBy($request->orden)->orderBy('codoficina');         
        return ['oficinas'=>$oficinas->get(),'ipbirt'=>$ip];
    }

    public function storeOficina(Request $request)
    {
        $oficina=new Fil_Oficina();
        $oficina->idfilial=$request->idfilial;
        $oficina->idunidad=$request->idunidad;
        $oficina->codoficina=$request->codoficina;
        $oficina->nomoficina=$request->nomoficina;
        $oficina->idresponsable=$request->idresponsable;
        $oficina->tiporesponsable=$request->tiporesponsable;
        $oficina->save();
    }

    public function updateOficina(Request $request)
    {
        $oficina=Fil_Oficina::findOrFail($request->idoficina);
        $oficina->idunidad=$request->idunidad;
        $oficina->nomoficina=$request->nomoficina;
        $oficina->idresponsable=$request->idresponsable;
        $oficina->tiporesponsable=$request->tiporesponsable;
        $oficina->save();
    }

    public function switchOficina(Request $request)
    {
        $oficina=Fil_Oficina::findOrFail($request->idoficina);
        $oficina->activo=abs($oficina->activo-1);
        $oficina->save();
    }    
}
