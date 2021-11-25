<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Ser_Civil;

class SerCivilController extends Controller
{
    //
    
    public function selectCivil(Request $request){
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        $raw=DB::raw('concat(nombre," ",apaterno, " ",amaterno) as nomcivil');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(nombre like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (nombre like '%".$valor."%' or apaterno like '%".$valor."%' or amaterno like '%".$valor."%' or ci like '%".$valor."%')";
                } 
            }   
            $civils = Ser_Civil::select('idcivil','ci',$raw)->orderBy('apaterno', 'desc')->whereraw($sqls)->get();
        }
        else {
            if (!empty($request->id))
                $civils = Ser_Civil::select('idcivil','ci',$raw)->orderBy('apaterno', 'desc')->where('idcivil','=',$request->id)->get();
            else
                $civils = Ser_Civil::select('idcivil','ci',$raw)->orderBy('apaterno', 'desc')->get();
        }
        return ['civils' => $civils];
        //return ['subcuentas' => $subcuentas]; */
    }
    
    
    public function store(Request $request)
    {
        if(!$request->ajax()) return paginate('/');
        $civil=new Ser_Civil();
        $civil->nombre=ucfirst($request->nombre);
        $civil->apaterno=ucfirst($request->apaterno);
        $civil->amaterno=ucfirst($request->amaterno);
        $civil->ci=$request->ci;
        $civil->iddepartamento=$request->iddepartamento;
        $civil->fechanac=$request->fechanac;
        $civil->sexo=$request->sexo;
        $civil->telcelular=$request->telcelular;
        $civil->save();
        return $civil->idcivil;
    }

    public function ultimo(Request $request)
    {   
        $civil=Ser_Civil::select('idcivil as idcliente','sexo as nomgrado',
        'apaterno','amaterno','nombre','telcelular','ci','iddepartamento','fechanac')
        ->orderBy('idcivil','desc')->limit(1)->get();
        return ['civil'=>$civil];
    }

    public function encontrar(Request $request)
    {   
        $civil=Ser_Civil::where('idcivil','=',$request->idcivil)->get();
        return ['civil'=>$civil];
    }

    public function update(Request $request)
    {   //print $request->idcivil."--";
        $civil=Ser_Civil::findOrFail($request->idcivil);
        $civil->nombre=$request->nombre;
        $civil->apaterno=$request->apaterno;
        $civil->amaterno=$request->amaterno;
        $civil->ci=$request->ci;
        $civil->iddepartamentoexpedido=$request->iddepartamentoexpedido;
        $civil->fechanac=$request->fechanac;
        $civil->sexo=$request->sexo;
        $civil->telcelular=$request->telcelular;
        $civil->save();
    }
}
