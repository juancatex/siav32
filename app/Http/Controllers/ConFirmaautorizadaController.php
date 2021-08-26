<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Con_Firmaautorizada;
use Illuminate\Support\Facades\DB;

class ConFirmaautorizadaController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $first=Con_Firmaautorizada::join('socios','socios.idsocio','con__firmaautorizadas.idpersona')
                                    ->join('fil__directivos','fil__directivos.idsocio','socios.idsocio')
                                    ->join('fil__unidads','fil__unidads.idunidad','fil__directivos.idunidad')
                                    ->join('fil__filials','fil__filials.idfilial','fil__directivos.idfilial')
                                    ->select('orden',
                                            $raw,
                                            'idfirmaautorizada',
                                            DB::raw('concat(nomcargo," - ",fil__filials.sigla) as cargo'))
                                    ->where('con__firmaautorizadas.activo',1)
                                    ->where('tipo_persona',1)
                                    ->where('orden','>=',2);
        
        
        $firmas=Con_Firmaautorizada::join('rrh__empleados','rrh__empleados.idempleado','con__firmaautorizadas.idpersona')
                                    ->join('rrh__cargos','rrh__empleados.idcargo','rrh__cargos.idcargo')
                                    ->select('orden',
                                            $raw,
                                            'idfirmaautorizada',
                                            DB::raw('nomcargo as cargo'))
                                    ->where('con__firmaautorizadas.activo',1)
                                    ->where('tipo_persona',2)
                                    ->where('orden','>=',2)
                                    ->union($first)
                                    ->orderby('orden','asc')
                                    ->get();
        /*  $first=Con_Firmaautorizada::join('socios','socios.idsocio','con__firmaautorizadas.idpersona'
                                    ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio'); */

        return $firmas;

    }
    public function store(Request $request)
    {
        if (!$request->ajax()) 
            return redirect('/');
        
        $firmas = new Con_Firmaautorizada();
        $firmas->idpersona = $request->idpersona;
        $firmas->tipo_persona=$request->tipo_persona;
        $firmas->orden = $request->orden;
        $firmas->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $firmas = Con_Firmaautorizada::findOrFail($request->idfirma);
        $firmas->activo = '0';
        $firmas->save();
    }
}
