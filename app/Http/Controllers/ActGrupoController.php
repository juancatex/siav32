<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Act_Grupo;

class ActGrupoController extends Controller
{
    public function listaGrupos(Request $request)
    {   
        $ip=config('app.ip'); 
        
        $sql="select idgrupo,codgrupo,nomgrupo,vida,idcuentaconta,idcuentadepre,idcuentagasto,activo,
        (select nomcuenta from con__cuentas where idcuenta=idcuentaconta) as cuentaconta,
        (select nomcuenta from con__cuentas where idcuenta=idcuentadepre) as cuentadepre,
        (select nomcuenta from con__cuentas where idcuenta=idcuentagasto) as cuentagasto
        from act__grupos where activo=$request->activo";
        if ($request->buscar!='null')
            $sql = $sql . " and nomgrupo like '%$request->buscar%' ";

        $sql = $sql . " order by nomgrupo";
      
        return ['grupos'=>DB::select($sql),'ipbirt'=>$ip];
    }

    public function listaCuentas(Request $request)
    {   
        $sql="(select idcuenta,codcuenta,nomcuenta,1 as grupo
        from con__cuentas where codcuenta like '122%' and nomcuenta not like '%dep%' and nomcuenta not like 'terr%' and activo=1)
        union (select idcuenta,codcuenta,nomcuenta,2 as grupo
        from con__cuentas where codcuenta like '122%' and nomcuenta like '%dep%' and activo=1)
        union (select idcuenta,codcuenta,nomcuenta,3 as grupo
        from con__cuentas where codcuenta like '621%' and nomcuenta like 'dep%' and activo=1)";
        return ['cuentas'=>DB::select($sql)];
    }    

    public function storeGrupo(Request $request)
    {   
        $grupo=new Act_Grupo();
        $grupo->codgrupo=$request->codgrupo;
        $grupo->nomgrupo=$request->nomgrupo;
        $grupo->vida=$request->vida;
        $grupo->idcuentaconta=$request->idcuentaconta;
        $grupo->idcuentadepre=$request->idcuentadepre;
        $grupo->idcuentagasto=$request->idcuentagasto;
        $grupo->save();
    }

    public function updateGrupo(Request $request)
    {
        $grupo=Act_Grupo::findOrFail($request->idgrupo);
        $grupo->nomgrupo=$request->nomgrupo;
        $grupo->vida=$request->vida;
        $grupo->idcuentaconta=$request->idcuentaconta;
        $grupo->idcuentadepre=$request->idcuentadepre;
        $grupo->idcuentagasto=$request->idcuentagasto;
        $grupo->save();
    }

    public function switchGrupo(Request $request)
    {
        $grupo=Act_Grupo::findOrFail($request->idgrupo);
        $grupo->activo=abs($grupo->activo-1);
        $grupo->save();
    }    
}
