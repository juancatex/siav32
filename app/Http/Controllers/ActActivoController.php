<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Act_Activo;
use App\Act_Depreciacion;

class ActActivoController extends Controller
{
    public function listaActivos(Request $request)
    {   
        $activos=Act_Activo::select('idactivo','idfilial',
        'codactivo','nomauxiliar','descripcion','act__activos.activo','act__activos.fechaingreso')
        ->join('act__auxiliars','act__auxiliars.idauxiliar','act__activos.idauxiliar')
        ->where('act__activos.idfilial',$request->idfilial)
        ->where('act__activos.idambiente',$request->idambiente)
        ->where('act__activos.idgrupo',$request->idgrupo);
        if($request->idauxiliar) $activos->where('act__activos.idauxiliar',$request->idauxiliar);
        if($request->activo) $activos->where('act__activos.activo',1);
        if($request->idactivo) $activos=Act_Activo::where('act__activos.idactivo',$request->idactivo);   
        return ['activos'=>$activos->get(),'currfecha'=>date('Y-m-d'),'ipbirt'=>$_SERVER['SERVER_ADDR']];
    }

    public function verActivo(Request $request)
    {        
        $activo=Act_Activo::selectRaw("act__activos.*,'curdate() as currfecha', fechaingreso,
        codfilial,nommunicipio,codambiente,nomambiente,codgrupo,nomgrupo,
        vida,'round(100/vida,1) as coeficiente',codauxiliar,nomauxiliar")  //valor as ufvini,
        ->join('fil__filials','fil__filials.idfilial','act__activos.idfilial')
        ->join('par_municipios','par_municipios.idmunicipio','fil__filials.idmunicipio')
        ->join('act__ambientes','act__ambientes.idambiente','act__activos.idambiente')
        ->join('act__auxiliars','act__auxiliars.idauxiliar','act__activos.idauxiliar')
        ->join('act__grupos','act__grupos.idgrupo','act__activos.idgrupo')
        //->join('act__ufvs','act__ufvs.fecha','act__activos.fechaingreso')
        //->join('act__asignacions','act__asignacions.idactivo','=','act__activos.idactivo')
        ->where('act__activos.idactivo',$request->idactivo);
        return ['activo'=>$activo->get()];
    }

    
    public function listaBajas(Request $request)
    {
        $activos=Act_Activo::select('act__activos.*','nommotivo','nomauxiliar')
        ->join('act__motivos','act__motivos.idmotivo','act__activos.idmotivo')
        ->join('act__grupos','act__grupos.idgrupo','act__activos.idgrupo')
        ->join('act__auxiliars','act__auxiliars.idauxiliar','act__activos.idauxiliar')
        ->where('act__activos.fechabaja','>',1)->orderBy('codactivo')->get();
        return ['activos'=>$activos];
    }

    public function storeActivo(Request $request)
    {
        $activo=new Act_Activo();
        $activo->codactivo=$request->codactivo;
        $activo->idfilial=$request->idfilial;
        $activo->idambiente=$request->idambiente;
        $activo->idgrupo=$request->idgrupo;
        $activo->idauxiliar=$request->idauxiliar;
        $activo->descripcion=$request->descripcion;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        $activo->fechaingreso=$request->fechaingreso;
        $activo->costo=$request->costo;
        $activo->obs=$request->obs;
        $activo->save();
    }

    public function updateActivo(Request $request)
    {
        $activo=Act_Activo::findOrFail($request->idactivo);
        $activo->descripcion=$request->descripcion;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        $activo->fechaingreso=$request->fechaingreso;
        $activo->costo=$request->costo;
        $activo->obs=$request->obs;
        $activo->save();
    }

    public function switchActivo(Request $request)
    {
        $activo=Act_Activo::findOrFail($request->idactivo);
        $activo->activo=abs($activo->activo-1);
        $activo->save();
    }

    public function storeBaja(Request $request)
    {
        $activo=Act_Activo::findOrFail($request->idactivo);
        $activo->activo=0;
        $activo->fechabaja=$request->fechabaja;
        $activo->nrorden=$request->nrorden;
        $activo->idmotivo=$request->idmotivo;
        $activo->obsbaja=$request->obsbaja;
        $activo->save();
    }

    public function calcDepreciacion(Request $request)
    {
        DB::table('act__depreciacions')->truncate();
        $sql="select valor from act__ufvs where fecha like '%-01-01'";
        $ufvini=DB::select($sql);
        $sql="select valor from act__ufvs where fecha like '%-12-31'";
        $ufvfin=DB::select($sql);
        $sql="select substr(fechaingreso,1,4) as gesini, substr(fechaingreso,6,2) as mesini,
        valor as ufvini, costo,residual,vida, floor(datediff(curdate(),fechaingreso)/30) as meses
        from act__activos
        join act__ufvs on act__ufvs.fecha=act__activos.fechaingreso
        join act__grupos on act__grupos.idgrupo=act__activos.idgrupo
        where idactivo=".$request->idactivo;
        $activo=DB::select($sql)[0];
        $meses=13-(($activo->mesini)*1);
        $gesini=$activo->gesini;
        $gesfin=2019;
        $depracum=0;
        for($ges=$gesini; $ges<$gesfin; $ges++)
        {
            $depreciacion=new Act_Depreciacion();
            $depreciacion->gestion=$ges;
            $depreciacion->ufvini=$ufvini[$ges-2009]->valor;
            $depreciacion->ufvfin=$ufvfin[$ges-2009]->valor;
            $depranual=$activo->costo/$activo->vida;
            if($ges==$gesini)
            {
                $depreciacion->ufvini=$activo->ufvini;
                $depranual=$depranual/$meses;
            }
            $depreciacion->incranual=$activo->costo*(($depreciacion->ufvfin/$depreciacion->ufvini)-1);
            $depreciacion->depranual=$depranual;
            $depracum+=$depreciacion->depranual;
            $depreciacion->depracum=$depracum;
            $depreciacion->valorfin=$activo->costo-$depreciacion->depracum+$depreciacion->incranual;
            $transc=$ges-$gesini;
            $depreciacion->consumido=$transc."a ".$meses."m";
            $depreciacion->saldovida=($activo->vida-$transc-1)."a ".(12-$meses)."m";
            $depreciacion->save();
        }
        /*
        $depreciacion=new Act_Depreciacion();
        $depreciacion->ufvini=$activo->ufvfin;
        $depreciacion->ufvfin=DB::select("select valor from act__ufvs where fecha like curdate()")[0];
        $depreciacion->consumido=$activo->meses;
        $depreciacion->saldovida=$activo->meses;
        $depreciacion->incranual=$activo->costo*(($depreciacion->ufvfin/$depreciacion->ufvini)-1);
        $depreciacion->depranual=($activo->costo/($activo->vida*12))*$activo->meses;
        $depreciacion->valorfin=$activo->costo+$depreciacion->incranual-$depreciacion->depranual;
        $depreciacion->save();
*/
        $sql="select * from act__depreciacions";
        return ['depreciaciones'=>DB::select($sql)];
    }

}
