<?php

namespace App\Http\Controllers;

use App\Con_Asientodetalle;
use Illuminate\Http\Request;
use App\Con_Asientomaestro;
use App\Con_Asientosubcuenta;
use Illuminate\Support\Facades\DB;

class ConAsientodetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* var $asientosubdetalles; */
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
    public function selectAsientoDetalle2(Request $request){

        //if (!$request->ajax()) return redirect('/');
        //var $asientosubdetalles;
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
        
        //dd($asientodetalles);
        $asientosubdetalles=[];
        $cont=0;
        foreach ($asientodetalles as $value) {
            $socios =Con_Asientosubcuenta::select('subdetalle as detalle',
                                                        'subdebe',
                                                        'subhaber',
                                                        'idcuenta',
                                                        DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                                        'idsocio as idsubcuenta',
                                                        'numpapeleta as subcuenta',
                                                        'tipo_subcuenta as tiposubcuenta')
                                                ->join('socios','socios.idsocio','con__asientosubcuentas.idsubcuenta')
                                                ->where('idasientomaestro',$idasientomaestro)
                                                ->where('tipo_subcuenta',1)//tipo subcuenta  socios
                                                ->where('idcuenta',$value->idcuenta);
                                                
            $personal=Con_Asientosubcuenta::select('subdetalle as detalle',
                                                    'subdebe',
                                                    'subhaber',
                                                    'idcuenta',
                                                    DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre'),
                                                    'idempleado as idsubcuenta',
                                                    'ci as subcuenta',
                                                    'tipo_subcuenta as tiposubcuenta')                                    
                                                ->join('rrh__empleados','rrh__empleados.idempleado','con__asientosubcuentas.idsubcuenta')
                                                ->where('idasientomaestro',$idasientomaestro)
                                                ->where('idcuenta',$value->idcuenta)
                                                ->where('tipo_subcuenta',2)//tipo subcuenta personal
                                                ->union($socios);
            
            $otros=Con_Asientosubcuenta::select('subdetalle as detalle',
                                                'subdebe',
                                                'subhaber',
                                                'idcuenta',
                                                'nomproveedor as nombre',
                                                'idproveedor as idsubcuenta',
                                                'nit as subcuenta',
                                                'tipo_subcuenta as tiposubcuenta')
                                                ->join('alm__proveedors','alm__proveedors.idproveedor','con__asientosubcuentas.idsubcuenta')
                                                ->where('idasientomaestro',$idasientomaestro)
                                                ->where('idcuenta',$value->idcuenta)
                                                ->where('tipo_subcuenta',3)// tipo subcuenta otros
                                                ->union($personal);
            
            $subascinalss=Con_Asientosubcuenta::select('subdetalle as detalle',
                                                        'subdebe',
                                                        'subhaber',
                                                        'idcuenta',
                                                        'descripcion as nombre',
                                                        'idconconfig as idsubcuenta',
                                                        'valor as subcuenta',
                                                        'tipo_subcuenta as tiposubcuenta')
                                                        ->join('con__configuracions','con__configuracions.idconconfig','con__asientosubcuentas.idsubcuenta')
                                                        ->where('idasientomaestro',$idasientomaestro)
                                                        ->where('idcuenta',$value->idcuenta)
                                                        ->where('tipo_subcuenta',4)// tipo subcuenta subascinalss
                                                        ->union($otros)
                                                        ->get();
            
                                                        
                array_push($asientosubdetalles,$subascinalss);
                $cont++;
            }
        
            //dd($asientosubdetalles);
        return ['asientodetalles' => $asientodetalles,
                'subdetalles'=>$asientosubdetalles];
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
