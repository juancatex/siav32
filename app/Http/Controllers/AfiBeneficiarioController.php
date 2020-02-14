<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Afi_Beneficiario;
use App\Socio;

class AfiBeneficiarioController extends Controller
{
    public function index(Request $request)
    {
        $beneficiarios=Afi_Beneficiario::select('afi__beneficiarios.*')
        ->join('socios','afi__beneficiarios.idsocio','=','socios.idsocio')
        ->where('afi__beneficiarios.idsocio','=',$request->idsocio)
        ->orderBy('idbeneficiario', 'asc')->get();
        return ['beneficiarios' => $beneficiarios];
    }

    public function listaBeneficiarios(Request $request)
    {
        $beneficiarios=Afi_Beneficiario::select('afi__beneficiarios.*','abrvdep')
        ->join('par_departamentos','par_departamentos.iddepartamento','afi__beneficiarios.iddepartamento')
        ->join('socios','afi__beneficiarios.idsocio','socios.idsocio')
        ->where('afi__beneficiarios.idsocio',$request->idsocio);
        if($request->idactivo) $beneficiarios->where('afi__beneficiarios.activo',1);
        if($request->idbeneficiario) 
            $beneficiarios=Afi_Beneficiario::where('idbeneficiario',$request->idbeneficiario);
        return ['beneficiarios'=>$beneficiarios->get()];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $beneficiario = new Afi_Beneficiario();
        $beneficiario->idsocio=$request->idsocio;
        $beneficiario->nombre=$request->nombre;
        $beneficiario->apaterno=$request->apaterno;
        $beneficiario->amaterno=$request->amaterno;
        $beneficiario->parentesco=$request->parentesco;
        $beneficiario->ci=$request->ci;
        $beneficiario->iddepartamentoexpedido=$request->iddepartamentoexpedido;
        $beneficiario->fechanac=$request->fechanac;
        $beneficiario->telcelular=$request->telcelular;
        $beneficiario->save();
        if($request->parentesco=='Espos@')
        {   $socio=Socio::findOrFail($request->idsocio);
            $socio->idestadocivil=2;
            $socio->save();
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $beneficiario = Afi_Beneficiario::findOrFail($request->idbeneficiario);
        $beneficiario->nombre=$request->nombre;
        $beneficiario->apaterno=$request->apaterno;
        $beneficiario->amaterno=$request->amaterno;
        $beneficiario->parentesco=$request->parentesco;
        $beneficiario->ci=$request->ci;
        $beneficiario->iddepartamentoexpedido=$request->iddepartamentoexpedido;
        $beneficiario->fechanac=$request->fechanac;
        $beneficiario->telcelular=$request->telcelular;
        $beneficiario->save();
        if($request->parentesco=='Espos@') {
            $socio=Socio::findOrFail($request->idsocio);
            $socio->idestadocivil=2;
            $socio->save();
        }
    }

    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $beneficiario = Afi_Beneficiario::findOrFail($request->idbeneficiario);
        $beneficiario->activo = '0';
        $beneficiario->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $beneficiario = Afi_Beneficiario::findOrFail($request->idbeneficiario);
        $beneficiario->activo = '1';
        $beneficiario->save();
    }

    public function verEsposa(Request $request)
    {
        $esposa=Afi_Beneficiario::
        where('idsocio','=',$request->idsocio)
        ->where('parentesco','like','Espo%')->get();
        return ['esposa'=>$esposa];
    }
                       
}
