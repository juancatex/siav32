<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Contrato;

class RrhContratoController extends Controller
{
    public function listaContratos(Request $request)
    {   $ip=config('app.rutaRrhh'); 
        $contratos=Rrh_Contrato::select('rrh__contratos.*','nomtipocontrato')
        ->join('rrh__tipocontratos','rrh__tipocontratos.idtipocontrato','rrh__contratos.idtipocontrato')
        ->where('idempleado',$request->idempleado)->orderBy('inicontrato','desc');
        return ['contratos'=>$contratos->get(),'ipbirt'=>$ip];
    }

    public function storeContrato(Request $request)
    {
        $contrato=new Rrh_Contrato();
        $contrato->idempleado=$request->idempleado;
        $contrato->nrcontrato=$request->nrcontrato;
        $contrato->idtipocontrato=$request->idtipocontrato;
        $contrato->inicontrato=$request->inicontrato;
        $contrato->fincontrato=$request->fincontrato;
        $contrato->salario=$request->salario;
        $contrato->obs=$request->obs;
        $contrato->save();
    }

    public function updateContrato(Request $request)
    {
        $contrato=Rrh_Contrato::findOrFail($request->idcontrato);
        $contrato->nrcontrato=$request->nrcontrato;
        $contrato->idtipocontrato=$request->idtipocontrato;
        $contrato->inicontrato=$request->inicontrato;
        $contrato->fincontrato=$request->fincontrato;
        $contrato->salario=$request->salario;
        $contrato->obs=$request->obs;
        $contrato->save();
    }

    public function switchContrato(Request $request)
    {
        //$contrato=Rrh_Contrato::where('idempleado','127')->update(['activo'=>0]);
        $contrato=Rrh_Contrato::findOrFail($request->idcontrato);
        $contrato->activo=abs($contrato->activo-1);
        $contrato->save();
    }

}
