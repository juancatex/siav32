<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Act_Activo;
use App\Act_Depreciacion;
use App\Act_Asignacion;

class ActActivoController extends Controller
{
    public function listaActivos(Request $request)
    {   
        //para obtener el ip de los reportes
        $ip=config('app.ip'); 

        $raw=DB::raw('(select concat(re.nombre," ",re.apaterno," ",re.amaterno) 
                        from act__asignacions aa, rrh__empleados re 
                        where aa.idactivo = act__activos.idactivo
                        and re.idempleado = aa.idresponsable 
                        and aa.activo =1) as nombre');
        $activos=Act_Activo::select('act__activos.idactivo','act__activos.idfilial','act__activos.imagen',
        'act__activos.codactivo','act__auxiliars.nomauxiliar','act__auxiliars.idauxiliar','act__auxiliars.nomauxiliar',
        'act__grupos.nomgrupo',
        'act__grupos.codgrupo','act__ambientes.codambiente','act__auxiliars.codauxiliar',
        'act__activos.descripcion','act__activos.activo','act__activos.fechaingreso', $raw)
        ->join('act__auxiliars','act__auxiliars.idauxiliar','act__activos.idauxiliar')
        ->join('act__grupos','act__activos.idgrupo','act__grupos.idgrupo')
        ->join('act__ambientes','act__activos.idambiente','act__ambientes.idambiente')
        ->whereNull('act__activos.fechabaja')->whereNull('act__activos.idmotivo');

        if($request->idfilial>0) $activos->where('act__activos.idfilial',$request->idfilial);
        if($request->idambiente>0) $activos->where('act__activos.idambiente',$request->idambiente);
        if($request->idgrupo>0) $activos->where('act__activos.idgrupo',$request->idgrupo);                
        if($request->idauxiliar) $activos->where('act__activos.idauxiliar',$request->idauxiliar);
        if($request->buscar!='') $activos->where('act__activos.codactivo', 'like', '%'. $request->buscar . '%');
        if($request->activo) $activos->where('act__activos.activo',1);
        
        // si solo el por el id del activo (cuando editamos)
        if($request->idactivo) {
            $activos=Act_Activo::select('act__activos.*','act__auxiliars.idauxiliar',
            'act__auxiliars.nomauxiliar','act__auxiliars.nomauxiliar',
            'act__grupos.nomgrupo',
            'act__grupos.codgrupo','act__ambientes.codambiente','act__auxiliars.codauxiliar')            
            ->where('act__activos.idactivo',$request->idactivo)   
            ->join('act__auxiliars','act__auxiliars.idauxiliar','act__activos.idauxiliar')            
            ->join('act__grupos','act__activos.idgrupo','act__grupos.idgrupo')
            ->join('act__ambientes','act__activos.idambiente','act__ambientes.idambiente');            
        }
        
        return ['activos'=>$activos->get(),'fechahoy'=>date('Y-m-d'),'ipbirt'=>$ip];
    }

    public function verActivo(Request $request)
    {   
        //verificar si se tiene una asignacion
        $asignacion=Act_Asignacion::select('idasignacion')->where('idactivo','=',$request->idactivo)->get();
        foreach($asignacion as $asig) {
            $d_asig = $asig->idasignacion;
        }
        if (isset($d_asig)) {
            $raw=DB::raw('(select concat(re.nombre," ",re.apaterno," ",re.amaterno) 
            from act__asignacions aa, rrh__empleados re 
            where aa.idactivo = act__activos.idactivo
            and re.idempleado = aa.idresponsable 
            and aa.activo =1) as nombre');
        }
        else {
            $raw='0';
        }

        $activo=Act_Activo::selectRaw("act__activos.*,curdate() as currfecha, fechaingreso,
        $raw,
        codfilial,nommunicipio,codambiente,nomambiente,imagen,codgrupo,nomgrupo,valor as ufvini,
        vida,round(100/vida,1) as coeficiente,codauxiliar,nomauxiliar")  
        ->join('fil__filials','fil__filials.idfilial','act__activos.idfilial')
        ->join('par_municipios','par_municipios.idmunicipio','fil__filials.idmunicipio')
        ->join('act__ambientes','act__ambientes.idambiente','act__activos.idambiente')
        ->join('act__auxiliars','act__auxiliars.idauxiliar','act__activos.idauxiliar')
        ->join('act__grupos','act__grupos.idgrupo','act__activos.idgrupo')
        ->join('act__ufvs','act__ufvs.fecha','act__activos.fechaingreso');
        
        if (isset($d_asig)) {
            $activo->join('act__asignacions','act__asignacions.idactivo','=','act__activos.idactivo');
        }
        
        $activo->where('act__activos.idactivo',$request->idactivo);
        
        return ['activo'=>$activo->get()];
    }

    
    public function listaBajas(Request $request)
    {
        $ip=config('app.ip'); 
        $activos=Act_Activo::select('act__activos.*','nommotivo','nomauxiliar')
        ->join('act__motivos','act__motivos.idmotivo','act__activos.idmotivo')
        ->join('act__grupos','act__grupos.idgrupo','act__activos.idgrupo')
        ->join('act__auxiliars','act__auxiliars.idauxiliar','act__activos.idauxiliar');

        if($request->buscar!='null') $activos->where('act__activos.codactivo', 'like', '%'. $request->buscar . '%');


        $activos->where('act__activos.fechabaja','>',1)->orderBy('codactivo');
        return ['activos'=>$activos->get(),'ipbirt'=>$ip];
    }

    public function storeActivo(Request $request)
    {
        $rutas=config('app.ruta_imagen');
        $imageData = $request->get('image');      
        $fileName =  "foto". time() . ".".explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path($rutas['DIRE_FOTO_ACTIVO']).$fileName);
        
        $activo=new Act_Activo();
        $activo->codactivo=$request->codactivo;
        $activo->idfilial=$request->idfilial;
        $activo->idambiente=$request->idambiente;
        $activo->idgrupo=$request->idgrupo;
        $activo->idauxiliar=$request->idauxiliar;
        $activo->descripcion=$request->descripcion;
        $activo->imagen=$fileName;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        $activo->fechaingreso=$request->fechaingreso;
        $activo->costo=$request->costo;
        $activo->obs=$request->obs;
        $activo->save();
    }

    public function updateActivo(Request $request)
    {
        $rutas=config('app.ruta_imagen');
        $imageData = $request->get('image'); 
        if (isset($imageData)) {
            $fileName =  "foto". time() . ".".explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path($rutas['DIRE_FOTO_ACTIVO']).$fileName);
        }     
        
        $activo=Act_Activo::findOrFail($request->idactivo);
        $activo->descripcion=$request->descripcion;
        $activo->marca=$request->marca;
        $activo->idfilial=$request->idfilial;
        $activo->idambiente=$request->idambiente;
        if (isset($imageData)) {
            $activo->imagen=$fileName;
        }
        $activo->serie=$request->serie;
        $activo->fechaingreso=$request->fechaingreso;
        $activo->costo=$request->costo;
        $activo->obs=$request->obs;
        $activo->save();
    }

    public function storeBaja(Request $request)
    {
        //verificamos si no esta asignado
        $verifica=Act_Activo::join('act__asignacions','act__activos.idactivo','act__asignacions.idactivo')
        ->select('act__asignacions.activo')->where('act__activos.idactivo','=',$request->idactivo)->get();
        foreach($verifica as $data) {            
            $data = $data->activo;           
        }        
        if (isset($data) && $data==1) {
            return 1;
        }
        else { 
                $activo=Act_Activo::findOrFail($request->idactivo);
                $activo->activo=0;
                $activo->fechabaja=$request->fechabaja;
                $activo->nrorden=$request->nrorden;
                $activo->idmotivo=$request->idmotivo;
                $activo->obsbaja=$request->obsbaja;
                $activo->save();
            }
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
        $gesfin=date('Y');
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

    public function validaAsignacion(Request $request)
    {  // validar si un activo esta asignado y vigente
        $idactivo =  $request->idactivo;
        $valida=Act_Activo::select('act__activos.*','act__asignacions.*')
        ->join('act__asignacions','act__activos.idactivo','act__asignacions.idactivo')
        ->where('act__activos.idactivo','=',$idactivo)
        ->where('act__asignacions.activo','=',1)->get();         
        return ['valida'=>count($valida)];
    
    }
}
