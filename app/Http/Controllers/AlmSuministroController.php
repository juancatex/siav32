<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alm_Suministro;
use App\Alm_Medida;
use App\Con_Cuentas;

class AlmSuministroController extends Controller
{    
    public function listaCuentas()
    {   
        $cuentas=Con_Cuentas::where('codcuenta','like','11701%')->get();
        return ['cuentas'=>$cuentas];
    }

    public function updateCuenta(Request $request)
    {   
        $cuenta=Con_Cuentas::findOrFail($request->idcuenta);
        $cuenta->nomcuenta=$request->nomcuenta;
        $cuenta->save();
    }

    public function listaSuministros(Request $request)
    {
        $suministros=Alm_Suministro::where('idcuenta','=',$request->idcuenta)
        ->join('alm__medidas','alm__medidas.idmedida','=','alm__suministros.idmedida')
        ->orderBy('codsuministro')->get();
        return ['suministros'=>$suministros,'ipbirt'=>$_SERVER['SERVER_ADDR']];
    }

    public function storeSuministro(Request $request)
    {
        $suministro=new Alm_Suministro();
        $suministro->idcuenta=$request->idcuenta;
        $suministro->codsuministro=$request->codsuministro;
        $suministro->nomsuministro=$request->nomsuministro;
        $suministro->idmedida=$request->idmedida;
        $suministro->maximo=$request->maximo;
        $suministro->minimo=$request->minimo;
        $suministro->save();
    }

    public function updateSuministro(Request $request)
    {   
        $suministro=Alm_Suministro::findOrFail($request->idsuministro);
        //$suministro->idcuenta=$request->idcuenta;
        //$suministro->codsuministro=$request->codsuministro;
        $suministro->nomsuministro=$request->nomsuministro;
        $suministro->idmedida=$request->idmedida;
        $suministro->maximo=$request->maximo;
        $suministro->minimo=$request->minimo;
        $suministro->save();
    }

    public function switchSuministro(Request $request)
    {
        $suministro=Alm_Suministro::findOrFail($request->idsuministro);
        $suministro->activo=abs($suministro->activo-1);
        $suministro->save();
    }

    public function listaMedidas()
    {
        $medidas=Alm_Medida::get();
        return ['medidas'=>$medidas];
    }
    
}
