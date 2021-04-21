<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Afi_Beneficiario;
use Illuminate\Support\Str;
use App\Socio;

class AfiBeneficiarioController extends Controller
{
    public function index(Request $request)
    {
        $beneficiarios=Afi_Beneficiario::select('afi__beneficiarios.*','abrvdep','socios.codsocio','socios.carnetmilitar','socios.numpapeleta','socios.idtiposocio')
        ->join('par_departamentos','par_departamentos.iddepartamento','afi__beneficiarios.iddepartamento')
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

    public function socioResponsable(Request $request)
    { // en caso de que el responsable es el socios
        $socios=Socio::select('socios.idsocio as idbeneficiario','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.telcelular','numpapeleta as resp', 'abrvdep')
        ->join('par_departamentos','par_departamentos.iddepartamento','socios.iddepartamentoexpedido')
        ->where('socios.idsocio',$request->idsocio);
        if($request->idactivo) $socios->where('socios.activo',1);        
        return ['beneficiarios'=>$socios->get()];
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
        $beneficiario->iddepartamento=$request->iddepartamentoexpedido;
        $beneficiario->fechanac=$request->fechanac;
        $beneficiario->telcelular=$request->telcelular;  
        $beneficiario->foto=$request->foto;  
        $beneficiario->save();
        if($request->parentesco=='Esposa'||$request->parentesco=='Esposo')
        {   $socio=Socio::findOrFail($request->idsocio);
            $socio->idestadocivil=2;
            $socio->save();
        }
    }
    public function saveimagebene(Request $request)
    {
        if (!$request->ajax()) return redirect('/');  
        $var = Str::random(32);
        $var.='.jpg';
        $request->file('foto')->storeAs('fotos/bene',$var); 
       return ['foto'=>$var];
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
        $beneficiario->iddepartamento=$request->iddepartamentoexpedido;
        $beneficiario->fechanac=$request->fechanac;
        $beneficiario->telcelular=$request->telcelular;
        $beneficiario->foto=$request->foto;  
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
