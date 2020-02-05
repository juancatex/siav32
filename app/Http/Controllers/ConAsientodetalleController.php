<?php

namespace App\Http\Controllers;

use App\Con_Asientodetalle;
use Illuminate\Http\Request;
use App\Con_Asientomaestro;

class ConAsientodetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    public function selectAsientoDetalle(Request $request){

        //if (!$request->ajax()) return redirect('/');

        $idasientomaestro=$request->idasientomaestro;
        $asientodetalles = Con_Asientodetalle::join('con__asientomaestros','con__asientomaestros.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                ->join('con__cuentas','con__asientodetalles.idcuenta','=','con__cuentas.idcuenta')
                                                ->leftjoin('con__tipocomprobantes','con__tipocomprobantes.idtipocomprobante','=','con__asientomaestros.idtipocomprobante')
                                                ->select('cont_comprobante',
                                                            'moneda',
                                                            'usuarioregistro',
                                                            'usuariomodifica',
                                                            'nomcuenta',
                                                            'codcuenta',
                                                            'monto',
                                                            'debe',
                                                            'haber',
                                                            'con__asientodetalles.idcuenta',
                                                            'con__asientodetalles.documento'
                                                            /*'nomtipocomprobante'
                                                            'con__asientomaestros.idasientomaestro',
                                                            'con__asientomaestros.idtipocomprobante',
                                                            'fecharegistro',
                                                            'tipodocumento',
                                                            'numdocumento',
                                                            'idfilial',
                                                            'glosa',
                                                            ,
                                                            */
                                                            )
                                                ->where('con__asientomaestros.idasientomaestro','=',$idasientomaestro)
                                                ->orderBy('monto', 'desc')->get();
        
                                                return ['asientodetalles' => $asientodetalles];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function selectCuentaHaber(Request $request)
    {
        $idasientomaestro=$request->id;
        $asientodetalles = Con_Asientodetalle::select('idcuenta')
                                                ->where('idasientomaestro',$idasientomaestro)
                                                ->where('monto','>',0)->get()->toArray();
        
        return $asientodetalles[0]['idcuenta'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Con_Asientodetalle  $con_Asientodetalle
     * @return \Illuminate\Http\Response
     */
    public function show(Con_Asientodetalle $con_Asientodetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Con_Asientodetalle  $con_Asientodetalle
     * @return \Illuminate\Http\Response
     */
    public function edit(Con_Asientodetalle $con_Asientodetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Con_Asientodetalle  $con_Asientodetalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Con_Asientodetalle $con_Asientodetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Con_Asientodetalle  $con_Asientodetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Con_Asientodetalle $con_Asientodetalle)
    {
        //
    }
}
