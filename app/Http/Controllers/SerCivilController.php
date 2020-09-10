<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Ser_Civil;

class SerCivilController extends Controller
{
    //
    public function store(Request $request)
    {
        if(!$request->ajax()) return paginate('/');
        $civil=new Ser_Civil();
        $civil->nombre=$request->nombre;
        $civil->apaterno=$request->apaterno;
        $civil->amaterno=$request->amaterno;
        $civil->ci=$request->ci;
        $civil->iddepartamento=$request->iddepartamento;
        $civil->fechanac=$request->fechanac;
        $civil->sexo=$request->sexo;
        $civil->telcelular=$request->telcelular;
        $civil->save();
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
