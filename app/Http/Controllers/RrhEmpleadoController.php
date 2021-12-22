<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Rrh_Empleado;
use App\Socio;

class RrhEmpleadoController extends Controller
{
    public function listaEmpleados(Request $request)
    {if (!$request->ajax()) return redirect('/'); 
        $ip=config('app.rutaRrhh'); 
        $value=($request->activou=='1')?1:0;
        $empleados=Rrh_Empleado:: join('par_departamentos','par_departamentos.iddepartamento','rrh__empleados.iddepartamento')
        ->join('fil__filials','fil__filials.idfilial','rrh__empleados.idfilial')
        ->join('par_municipios','par_municipios.idmunicipio','fil__filials.idmunicipio')
        ->select('idempleado','nombre','apaterno','amaterno','ci','abrvdep','rrh__empleados.telcelular',
        'foto','codbiom','rrh__empleados.activo','nommunicipio as filial')
        ->where('rrh__empleados.activo','=',$value)
        ->where('rrh__empleados.idfilial',($request->sedelp=='1'?'=':'>='),1); 
        $buscararray = array();  
        if(!empty($request->buscado)){
            $buscararray = explode(" ",$request->buscado);
        } 
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                         $sqls="(rrh__empleados.apaterno like '%".$valor."%' or rrh__empleados.amaterno like '%".$valor."%' or rrh__empleados.nombre like '%".$valor."%' or rrh__empleados.ci like '%".$valor."%')";
                }else{
                    $sqls.=" or (rrh__empleados.apaterno like '%".$valor."%' or rrh__empleados.amaterno like '%".$valor."%' or rrh__empleados.nombre like '%".$valor."%' or rrh__empleados.ci like '%".$valor."%')";
                } 
            } 
            $empleados=$empleados->whereraw($sqls);
        }
 
        $empleados=$empleados->orderBy('apaterno')->orderBy('amaterno')->get();
        return ['empleados'=>$empleados,'currfecha'=>date('Y-m-d'),'ipbirt'=>$ip];
    }

    public function verEmpleado(Request $request)
    {if (!$request->ajax()) return redirect('/'); 
        $empleado=Rrh_Empleado::where('idempleado',$request->idempleado);
        return ['empleado'=>$empleado->get()];
    }

    public function verEmpleadopdf(Request $request)
    {  
        $empleado=Rrh_Empleado:: join('par_departamentos','par_departamentos.iddepartamento','rrh__empleados.iddepartamento')
        ->leftJoin('rrh__profesions','rrh__profesions.idprofesion','rrh__empleados.idprofesion')
        ->join('rrh__cargos','rrh__cargos.idcargo','rrh__empleados.idcargo')
        ->select('codempleado','nombre','apaterno','amaterno','ci','abrvdep','nomprofesion',
        'foto','nomcargo')
        ->where('idempleado',$request->idempleado)->get();
        return ['empleado'=>$empleado];
    }

    public function storeEmpleado(Request $request)
    {    if (!$request->ajax()) return redirect('/');  
        $empleado=new Rrh_Empleado();
        $empleado->codempleado=$request->codempleado;
        $empleado->nombre=$request->nombre;
        $empleado->apaterno=$request->apaterno;
        $empleado->amaterno=$request->amaterno;
        $empleado->ci=$request->ci;
        $empleado->iddepartamento=$request->iddepartamento;
        $empleado->sexo=$request->sexo;
        $empleado->fechanacimiento=$request->fechanacimiento;
        $empleado->idestadocivil=$request->idestadocivil;
        $empleado->gruposangre=$request->gruposangre;
        if($request->foto) {
            $var = Str::random(32);
            $var.='.jpg'; 
            $value = substr($request->foto, strpos($request->foto, ',') + 1); 
            $value = base64_decode($value); 
            Storage::put('app/public/emp/'.$var, $value); 
                $empleado->foto=$var;
            } 
        $empleado->idformacion=$request->idformacion;
        $empleado->idprofesion=$request->idprofesion;
        $empleado->telcelular=$request->telcelular;
        $empleado->telfijo=$request->telfijo;
        $empleado->email=$request->email;
        $empleado->domicilio=$request->domicilio;
        $empleado->zona=$request->zona;
        $empleado->idfilial=$request->idfilial;
        $empleado->idoficina=$request->idoficina;
        $empleado->idcargo=$request->idcargo;
        $empleado->fechaingreso=$request->fechaingreso;
        $empleado->idbanco=$request->idbanco;
        $empleado->nrcuenta=$request->nrcuenta;
        $empleado->codbiom=$request->codbiom;
        $empleado->save();
    }

    public function updateEmpleado(Request $request)
    {    if (!$request->ajax()) return redirect('/');  
        $empleado=Rrh_Empleado::findOrFail($request->idempleado);
        $empleado->codempleado=$request->codempleado;
        $empleado->nombre=$request->nombre;
        $empleado->apaterno=$request->apaterno;
        $empleado->amaterno=$request->amaterno;
        $empleado->ci=$request->ci;
        $empleado->iddepartamento=$request->iddepartamento;
        $empleado->sexo=$request->sexo;
        $empleado->fechanacimiento=$request->fechanacimiento;
        $empleado->idestadocivil=$request->idestadocivil;
        $empleado->gruposangre=$request->gruposangre; 
        if($request->foto) {
        $var = Str::random(32);
        $var.='.jpg'; 
        $value = substr($request->foto, strpos($request->foto, ',') + 1); 
        $value = base64_decode($value); 
        Storage::put('app/public/emp/'.$var, $value); 
            $empleado->foto=$var;
        } 
        $empleado->idformacion=$request->idformacion;
        $empleado->idprofesion=$request->idprofesion;
        $empleado->telcelular=$request->telcelular;
        $empleado->telfijo=$request->telfijo;
        $empleado->email=$request->email;
        $empleado->domicilio=$request->domicilio;
        $empleado->zona=$request->zona;
        $empleado->idfilial=$request->idfilial;
        $empleado->idoficina=$request->idoficina;
        $empleado->idcargo=$request->idcargo;
        $empleado->fechaingreso=$request->fechaingreso;
        $empleado->idbanco=$request->idbanco;
        $empleado->nrcuenta=$request->nrcuenta;
        $empleado->codbiom=$request->codbiom;
        $empleado->fecharetiro=$request->fecharetiro;
        $empleado->obs=$request->obs;
        $empleado->ssocial=$request->ssocial;
        $empleado->codssocial=$request->codssocial;
        $empleado->ssalud=$request->ssalud;
        $empleado->codssalud=$request->codssalud;
        $empleado->save();
    }

    public function switchEmpleado(Request $request)
    {
        $empleado=Rrh_Empleado::findOrFail($request->idempleado);
        $empleado->activo=abs($empleado->activo-1);
        $empleado->save();
    }

    public function selectEmpleados(Request $request)  //AjaxSelect
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
                else
                    $sqls.=" and (apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
            }   
            $empleados = Rrh_Empleado::select('idempleado',$raw,'ci')
            ->where('activo',1)
            ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')->whereraw($sqls);
        }
        else {
            if ($request->id)
                $empleados = Rrh_Empleado::select('idempleado',$raw,'ci')
                ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')->where('idempleado','=',$request->id);
            else
                $empleados = Rrh_Empleado::select('idempleado',$raw,'ci')
                ->where('activo',1)
                ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc');
        }
        return ['empleados' => $empleados->get()];
    }
    public function selectEmpleados2(Request $request)  //AjaxSelect
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre," - ",fil__filials.sigla) as nombres');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
                else
                    $sqls.=" and (apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
            }   
           
                                    //->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')

            
            $empleados = Rrh_Empleado::select('idempleado',DB::raw('idempleado as id'),$raw,'ci','fil__filials.sigla')
                                    ->join('fil__filials','rrh__empleados.idfilial','fil__filials.idfilial')
                                    ->where('rrh__empleados.activo',1)
                                    ->whereraw($sqls)
                                    ->orderBy('nombres','asc')->get();
                                    
            //dd($empleados);                        
        }
        else {
            if ($request->id)
            
            {    

                $empleados = Rrh_Empleado::select('idempleado',DB::raw('idempleado as id'),$raw,'ci','fil__filials.sigla')
                ->join('fil__filials','rrh__empleados.idfilial','fil__filials.idfilial')
                ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')->where('rrh__empleados.idempleado','=',$request->id)
                ->get();
            }
            else
            {
                    

                
                    $empleados = Rrh_Empleado::select('idempleado',DB::raw('idempleado as id'),$raw,'ci','fil__filials.sigla')
                    ->join('fil__filials','rrh__empleados.idfilial','fil__filials.idfilial')
                    ->where('rrh__empleados.activo',1)
                    ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')
                    ->get();
            }
        } 
        //dd($empleados);
        return ['empleados' => $empleados];
    }
    public function selectDirectivos(Request $request)  //AjaxSelect
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre," - ",fil__filials.sigla) as nombres');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
                else
                    $sqls.=" and (apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
            }   
            $empleados = Socio::select('socios.idsocio','socios.numpapeleta',$raw,'ci','fil__filials.sigla')
                                    ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                    ->where('fil__directivos.activo',1)
                                    ->whereraw($sqls)
                                    ->orderBy('nombres','asc')
                                    ->get();
        }
        else {
            if ($request->id){
                    $empleados = Socio::select('socios.idsocio','socios.numpapeleta',$raw,'ci')
                                    ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                    ->where('fil__directivos.activo',1)
                                    ->where('socios.idsocio','=',$request->id)
                                    ->orderBy('nombres','asc')
                                    ->get();
            }
                
            else
            {
                $empleados = Socio::select('socios.idsocio','socios.numpapeleta',$raw,'ci')
                                    ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    ->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                    ->where('fil__directivos.activo',1)
                                    ->orderBy('nombres','asc')
                                    ->get();
            }
              
        }
        return ['empleados' => $empleados];
    }
    public function selectSocios(Request $request)  //AjaxSelect
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw('concat(numpapeleta," - ",apaterno," ",amaterno," ",nombre) as nombres');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
                else
                    $sqls.=" and (apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
            }   
            $empleados = Socio::select('socios.idsocio','socios.numpapeleta',$raw,'ci')
                                    //->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    //->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                    //->where('fil__directivos.activo',1)
                                    ->whereraw($sqls)
                                    ->orderBy('nombres','asc')
                                    ->get();
        }
        else {
            if ($request->id){
                    $empleados = Socio::select('socios.idsocio','socios.numpapeleta',$raw,'ci')
                                    //->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    //->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                    //->where('fil__directivos.activo',1)
                                    ->where('socios.idsocio','=',$request->id)
                                    ->orderBy('nombres','asc')
                                    ->get();
            }
                
            else
            {
                $empleados = Socio::select('socios.idsocio','socios.numpapeleta',$raw,'ci')
                                    //->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    //->join('fil__filials','fil__directivos.idfilial','fil__filials.idfilial')
                                    //->where('fil__directivos.activo',1)
                                    ->orderBy('nombres','asc')
                                    ->get();
            }
              
        }
        return ['empleados' => $empleados];
    }
    public function selectSocios2(Request $request) 
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw('concat(socios.numpapeleta," - ",IF(socios.idfuerza=5,par_grados.abrev, par_grados.nomgrado)," ",socios.nombre," ",socios.apaterno," ",socios.amaterno) as nombres');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.ci like '%".$valor."%')";
                else
                    $sqls.=" and (socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.ci like '%".$valor."%')";
            }   
            $empleados = Socio::select('socios.idsocio',$raw)
                                    ->join('ser__asignacions','socios.idsocio','ser__asignacions.idcliente')
                                    ->join('par_grados','socios.idgrado','par_grados.idgrado') 
                                    ->where('ser__asignacions.tipocliente',1) 
                                    ->whereraw($sqls)
                                    ->groupBy('idsocio', 'nombres')
                                    ->orderBy('nombres','asc')
                                    ->limit(20)->get();
        }
        else {
               $empleados = Socio::select('socios.idsocio',$raw)
                                    ->join('ser__asignacions','socios.idsocio','ser__asignacions.idcliente')
                                    ->join('par_grados','socios.idgrado','par_grados.idgrado') 
                                    ->where('ser__asignacions.tipocliente',1) 
                                    ->groupBy('idsocio', 'nombres')
                                    ->orderBy('nombres','asc')
                                    ->limit(20)->get(); 
        }
        return ['empleados' => $empleados];
    }
}
