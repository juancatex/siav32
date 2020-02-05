<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alm_Entrada;

class AlmEntradaController extends Controller
{
    function listaEntradas(Request $request)
    {
        $sql="select alm__entradas.*, nomsuministro, nomproveedor, nommedida
        from alm__entradas
        join alm__suministros on alm__suministros.idsuministro=alm__entradas.idsuministro
        join con__cuentas on con__cuentas.idcuenta=alm__suministros.idcuenta
        join alm__proveedors on alm__proveedors.idproveedor=alm__entradas.idproveedor
        join alm__medidas on alm__medidas.idmedida=alm__suministros.idmedida
        where alm__entradas.idcuenta=".$request->idcuenta." order by nomsuministro, nrlote";
        return ['entradas'=>DB::select($sql)];
    }

    function storeEntrada(Request $request)
    {
        $entrada=new Alm_Entrada();
        $entrada->idsuministro=$request->idsuministro;
        $entrada->idcuenta=$request->idcuenta;
        $entrada->idproveedor=$request->idproveedor;
        //$entrada->nrlote=$request->nrlote;
        $entrada->cantentrada=$request->cantentrada;
        $entrada->fechaentrada=$request->fechaentrada;
        $entrada->costo=$request->costo;
        $entrada->nrfactura=$request->nrfactura;
        $entrada->usado=0;
        $entrada->save();
    }

    function updateEntrada(Request $request)
    {
        $entrada=Alm_Entrada::findOrFail($request->identrada);
        $entrada->idsuministro=$request->idsuministro;
        $entrada->idproveedor=$request->idproveedor;
        $entrada->cantentrada=$request->cantentrada;
        $entrada->fechaentrada=$request->fechaentrada;
        $entrada->costo=$request->costo;
        $entrada->nrfactura=$request->nrfactura;
        $entrada->save();
    }

    function switchEntrada(Request $request)
    {
        $entrada=Alm_Entrada::where('identrada','=',$request->identrada)
        ->update(['activo'=>abs($entrada->activo-1)]);
    }
}
