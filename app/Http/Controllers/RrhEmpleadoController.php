<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Rrh_Empleado;
use App\Socio;

class RrhEmpleadoController extends Controller
{
    public function listaEmpleados(Request $request)
    {
        $empleados=Rrh_Empleado::
        select('idempleado','nombre','apaterno','amaterno','ci','abrvdep','rrh__empleados.telcelular',
        'foto','codbiom','rrh__empleados.activo','nommunicipio as filial')
        ->join('par_departamentos','par_departamentos.iddepartamento','rrh__empleados.iddepartamento')
        ->join('fil__filials','fil__filials.idfilial','rrh__empleados.idfilial')
        ->join('par_municipios','par_municipios.idmunicipio','fil__filials.idmunicipio');
        if($request->buscado)
        {
            if(is_numeric($request->buscado)) $empleados=$empleados->where('ci','like',$request->buscado.'%');
            else $empleados=$empleados->where('apaterno','like','%'.$request->buscado.'%')
                ->orWhere('amaterno','like','%'.$request->buscado.'%')
                ->orWhere('nombre','like','%'.$request->buscado.'%');
        }       
        if($request->sedelp=='1') $empleados->where('rrh__empleados.idfilial',1);
        if($request->sedelp!='1') $empleados->where('rrh__empleados.idfilial','>',1);
        $empleados->where('rrh__empleados.activo',$request->activo);
        $empleados->orderBy('apaterno')->orderBy('amaterno');
        return ['empleados'=>$empleados->get(),'currfecha'=>date('Y-m-d'),'ipbirt'=>$_SERVER['SERVER_ADDR']];
    }

    public function verEmpleado(Request $request)
    {
        $empleado=Rrh_Empleado::where('idempleado',$request->idempleado);
        return ['empleado'=>$empleado->get()];
    }

    public function storeEmpleado(Request $request)
    {
        $codempleado=substr($request->apaterno,0,1).substr($request->amaterno,0,1).substr($request->nombre,0,1);
        $codempleado.=str_replace("-","",$request->fechanacimiento);
        if($request->foto) Image::make($request->foto)->save(public_path("/img/empleados/").$codempleado.".jpg");
        $empleado=new Rrh_Empleado();
        $empleado->codempleado=$codempleado;
        $empleado->nombre=$request->nombre;
        $empleado->apaterno=$request->apaterno;
        $empleado->amaterno=$request->amaterno;
        $empleado->ci=$request->ci;
        $empleado->iddepartamento=$request->iddepartamento;
        $empleado->sexo=$request->sexo;
        $empleado->fechanacimiento=$request->fechanacimiento;
        $empleado->idestadocivil=$request->idestadocivil;
        $empleado->gruposangre=$request->gruposangre;
        $empleado->foto=$request->foto?$codempleado.".jpg":"";
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
    {
        $codempleado=substr($request->apaterno,0,1).substr($request->amaterno,0,1).substr($request->nombre,0,1);
        $codempleado.=str_replace("-","",$request->fechanacimiento);
        if($request->foto) Image::make($request->foto)->save(public_path("/img/empleados/").$codempleado.".jpg");
        $empleado=Rrh_Empleado::findOrFail($request->idempleado);
        $empleado->nombre=$request->nombre;
        $empleado->apaterno=$request->apaterno;
        $empleado->amaterno=$request->amaterno;
        $empleado->ci=$request->ci;
        $empleado->iddepartamento=$request->iddepartamento;
        $empleado->sexo=$request->sexo;
        $empleado->fechanacimiento=$request->fechanacimiento;
        $empleado->idestadocivil=$request->idestadocivil;
        $empleado->gruposangre=$request->gruposangre;
        $empleado->foto=$request->foto?$codempleado.".jpg":"";
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
        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $rawidempleado=DB::raw('socios.numpapeleta as idempleado,socios.numpapeleta as id');
        $rawdirectivos=DB::raw('1 as directivos');
        $rawnodirectivos=DB::raw('0 as directivos');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
                else
                    $sqls.=" and (apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or nombre like '%".$valor."%' or ci like '%".$valor."%')";
            }   
            $first= Socio::select($rawidempleado,$raw,'ci',$rawdirectivos)
                                    ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    ->where('fil__directivos.activo',1)
                                    ->whereraw($sqls);
                                    //->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')

            
            $empleados = Rrh_Empleado::select('idempleado',DB::raw('idempleado as id'),$raw,'ci',$rawnodirectivos)
                                    ->where('activo',1)
                                    ->whereraw($sqls)
                                    ->union($first)
                                    ->orderBy('nombres','asc')->get();
                                    
            //dd($empleados);                        
        }
        else {
            if ($request->id)
            
            {    $first= Socio::select($rawidempleado,$raw,'ci',$rawdirectivos)
                                    ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                    ->where('fil__directivos.activo',1)
                                    ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc');

                $empleados = Rrh_Empleado::select('idempleado',DB::raw('idempleado as id'),$raw,'ci',$rawnodirectivos)
                ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')->where('idempleado','=',$request->id)
                ->union($first)->get();
            }
            else
            {
                    $first= Socio::select($rawidempleado,$raw,'ci',$rawdirectivos)
                                            ->join('fil__directivos','socios.idsocio','fil__directivos.idsocio')
                                            ->where('fil__directivos.activo',1)
                                            ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc');

                
                    $empleados = Rrh_Empleado::select('idempleado',DB::raw('idempleado as id'),$raw,'ci',$rawnodirectivos)
                    ->where('activo',1)
                    ->orderBy('apaterno','desc')->orderBy('amaterno','desc')->orderBy('nombres','desc')
                    ->union($first)->get();
            }
        } 
        //dd($empleados);
        return ['empleados' => $empleados];
    }
}
