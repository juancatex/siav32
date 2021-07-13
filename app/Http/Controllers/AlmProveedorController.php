<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alm_Proveedor;

class AlmProveedorController extends Controller
{
    public function selectProveedor(Request $request){
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        $raw=DB::raw('concat(nit," ",nomproveedor) as nit_proveedor');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(nomproveedor like '%".$valor."%' or nit like '%".$valor."%')";
                }else{
                    $sqls.=" and (nomproveedor like '%".$valor."%' or nit like '%".$valor."%')";
                } 
            }   
            $proveedores = Alm_Proveedor::select('idproveedor',$raw)->orderBy('nit', 'desc')->whereraw($sqls)->get();
        }
        else {
            if (!empty($request->id))
                $proveedores = Alm_Proveedor::select('idproveedor',$raw)->orderBy('nit', 'desc')->where('idproveedor','=',$request->id)->get();
            else
                $proveedores = Alm_Proveedor::select('idproveedor',$raw)->orderBy('nit', 'desc')->get();
        }
        return ['proveedores' => $proveedores];
       /*  $raw=DB::raw('concat(nit," ",nomproveedor) as mostrar');
        $proveedors = Alm_Proveedor::select('idproveedor',$raw)
        ->where('activo','=','1')
        ->orderBy('nit', 'asc')->get();

        return response()->json($proveedors);
        //return ['subcuentas' => $subcuentas]; */
    }

    public function selectProveedor2(Request $request){
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        $raw=DB::raw('concat(nit," ",nomproveedor) as nit_proveedor');
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(nomproveedor like '%".$valor."%' or nit like '%".$valor."%')";
                }else{
                    $sqls.=" and (nomproveedor like '%".$valor."%' or nit like '%".$valor."%')";
                } 
            }   
            $proveedores = Alm_Proveedor::select('idproveedor','nit',$raw,'nit as nit1','nomproveedor')->orderBy('nit', 'desc')->whereraw($sqls)->get();
        }
        else {
            if (!empty($request->id))
                $proveedores = Alm_Proveedor::select('idproveedor','nit',$raw,'nit as nit1','nomproveedor')->orderBy('nit', 'desc')->where('idproveedor','=',$request->id)->get();
            else
                $proveedores = Alm_Proveedor::select('idproveedor','nit',$raw,'nit as nit1','nomproveedor')->orderBy('nit', 'desc')->get();
        }
        //dd($proveedores);
        return ['proveedores' => $proveedores];
       /*  $raw=DB::raw('concat(nit," ",nomproveedor) as mostrar');
        $proveedors = Alm_Proveedor::select('idproveedor',$raw)
        ->where('activo','=','1')
        ->orderBy('nit', 'asc')->get();

        return response()->json($proveedors);
        //return ['subcuentas' => $subcuentas]; */
    }



    public function listaProveedores(Request $request)
    {
        $sql="select * from alm__proveedors ";
        if($request->buscado) 
        {
            if(is_numeric($request->buscado)) $sql.="where nit like '%".$request->buscado."%'";
            else $sql.="where nomproveedor like '%".$request->buscado."%'";
        }
        if($request->inicial) $sql.="where nomproveedor like '".$request->inicial."%'";
        $sql.="order by idproveedor desc";
        return ['proveedores'=>DB::select($sql)];
    }

    public function storeProveedor(Request $request)
    {
        $proveedor=new Alm_Proveedor();
        $proveedor->nomproveedor=$request->nomproveedor;
        $proveedor->nomcontacto=$request->nomcontacto;
        $proveedor->nit=$request->nit;
        $proveedor->direccion=$request->direccion;
        $proveedor->telefono=$request->telefono;
        $proveedor->celular=$request->celular;
        $proveedor->save();
    }

    public function updateProveedor(Request $request)
    { 
        $proveedor=Alm_Proveedor::findOrFail($request->idproveedor);
        $proveedor->nomproveedor=$request->nomproveedor;
        $proveedor->nomcontacto=$request->nomcontacto;
        $proveedor->nit=$request->nit;
        $proveedor->direccion=$request->direccion;
        $proveedor->telefono=$request->telefono;
        $proveedor->celular=$request->celular;
        $proveedor->save();
    }

    public function switchProveedor(Request $request)
    {
        $proveedor=Alm_Proveedor::findOrFail($request->idproveedor);
        $proveedor->activo=abs($proveedor->activo-1);
        $proveedor->save();        
    }
}
