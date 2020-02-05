<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Con_Asientodetalle;
use Illuminate\Support\Facades\DB;
use App\Con_Asientomaestro;
use App\Con_Cuentas;

class ConReportesController extends Controller
{
    public function rutas(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaConReportes');  
        return [ 
        'REP_ASIENTO_AUTOMATICO' => $rutas['REP_ASIENTO_AUTOMATICO'],
        'REP_LIBRO_COMPRAS'=>$rutas['REP_LIBRO_COMPRAS'],
        'REP_SEG_CCUENTAS'=>$rutas['REP_SEG_CCUENTAS'],
        'REP_PLAN_CUENTAS'=>$rutas['REP_PLAN_CUENTAS'],
        'REP_DOC_OBLIGACION'=>$rutas['REP_DOC_OBLIGACION']
        ];
    }
    public function libromayor(Request $request)
    {
        
        $fechainicio = date("Y-m-d", strtotime($request->finicio));

        $fechafin=date("Y-m-d",strtotime($request->ffin));
        $cuentainicio=$request->cinicio;
        $cuentafin=$request->cfin;

        $codcuentas=Con_Asientodetalle::join('con__asientomaestros as b','b.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                        ->join('con__cuentas as c','c.idcuenta','=','con__asientodetalles.idcuenta')
                                        ->select('c.idcuenta')
                                        ->wherebetween('codcuenta',[$cuentainicio,$cuentafin])
                                        ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                        ->where('b.estado','1')
                                        ->where('b.idagrupacion',null)
                                        ->where('b.gestion',0)
                                        ->groupBy('nomcuenta')
                                        ->orderBy('codcuenta','asc')
                                        ->get()->toArray();
        
        //dd($codcuentas);
        $arrayfinal=[];
        $diauno=date("Y");
        $diauno=$diauno."-01-01";
        foreach ($codcuentas as $indice => $valor) {
            $idcuenta=$valor['idcuenta'];
            $raw=DB::raw('IFNULL(ifnull(SUM(debe),0)-ifnull(sum(haber),0),0) as saldo');
            $raw2=DB::raw('IFNULL(con__asientodetalles.idcuenta,'.$idcuenta.') as idcuenta');
            $saldos=Con_Asientodetalle::join('con__asientomaestros as b','con__asientodetalles.idasientomaestro','=','b.idasientomaestro')
                                        ->join('con__cuentas as c','con__asientodetalles.idcuenta','=','c.idcuenta')
                                        ->select($raw2,'c.codcuenta','c.nomcuenta',$raw)
                                        //->whereBetween(DB::raw('date(fecharegistro)'), [$diauno, $fechainicio])
                                        ->where(DB::raw('date(fecharegistro)'),'<',$fechainicio)
                                        ->where('b.estado',1)
                                        ->where('b.idagrupacion',null)
                                        ->where('con__asientodetalles.idcuenta',$idcuenta)
                                        ->where('b.gestion',0)
                                        ->get()->toArray();
            $arrayfinal=array_merge($arrayfinal,$saldos);
        
            $detallemayor = Con_Asientodetalle::join('con__asientomaestros as b','b.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                                ->join('par__modulos as c','c.idmodulo','=','b.idmodulo')
                                                ->join('con__cuentas as d','d.idcuenta','=','con__asientodetalles.idcuenta')
                                                ->select('d.idcuenta',
                                                            'b.idasientomaestro',
                                                            'con__asientodetalles.idasientodetalle',
                                                            'b.fecharegistro',
                                                            'b.cod_comprobante',
                                                            'b.tipodocumento',
                                                            'b.numdocumento',
                                                            'b.glosa',
                                                            'monto',
                                                            'debe',
                                                            'haber')
                                                    // ->whereBetween('codcuenta',[$cuentainicio,$cuentafin])
                                                    ->where('d.idcuenta',$idcuenta)
                                                    ->where('b.estado','=',1)
                                                    ->where('b.idagrupacion',null)
                                                    ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                                    ->where('b.gestion',0)
                                                    ->orderBy('codcuenta','desc')
                                                    ->orderBy('fecharegistro','asc')
                                                    ->get()->toarray();
            
            $arrayfinal=array_merge($arrayfinal,$detallemayor);
            
            $raw=DB::raw('ifnull(sum(debe),0) as sdebe, ifnull(sum(haber),0) as shaber');
            $saldofinal=Con_Asientodetalle::join('con__asientomaestros as b','b.idasientomaestro','=','con__asientodetalles.idasientomaestro')
                                            ->select($raw,'idcuenta')
                                            ->where('idcuenta',$idcuenta)
                                            ->where('b.estado','=',1)
                                            ->where('b.idagrupacion',null)
                                            ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                            ->where('b.gestion',0)
                                            ->get()->toarray();
            
            $arrayfinal=array_merge($arrayfinal,$saldofinal);
                                            

        }

        $jsonfinal=json_encode($arrayfinal);
       /*  dd($jsonfinal);
        dd($arrayfinal); */

        return $jsonfinal;
    }
    public function librodiario(Request $request)
    {
        $fechainicio = date("Y-m-d", strtotime($request->finicio));
        $fechafin=date("Y-m-d",strtotime($request->ffin));
        $idtipocomprobante=$request->idtipocomprobante;
        $raw=DB::raw('sum(debe) as sdebe, sum(haber) as shaber');
        if($idtipocomprobante==0){
            $cabeceras=Con_Asientomaestro::join('con__asientodetalles as a','a.idasientomaestro','=','con__asientomaestros.idasientomaestro')
                                            ->select('con__asientomaestros.idasientomaestro',
                                                    'fecharegistro',
                                                    'cod_comprobante',
                                                    'tipodocumento',
                                                    'numdocumento',
                                                    'glosa',
                                                    $raw)
                                                ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                                ->where('estado','=',1)
                                                ->where('idagrupacion',null)
                                                ->where('con__asientomaestros.gestion',0)
                                                ->groupBy('con__asientomaestros.idasientomaestro')
                                                ->orderBy('fecharegistro','asc')
                                                ->get()
                                                ->toarray();
        }
        else{
            $cabeceras=Con_Asientomaestro::join('con__asientodetalles as a','a.idasientomaestro','=','con__asientomaestros.idasientomaestro')
                                            ->select('con__asientomaestros.idasientomaestro',
                                                'fecharegistro',
                                                'cod_comprobante',
                                                'tipodocumento',
                                                'numdocumento',
                                                'glosa',
                                                $raw)
                                            ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                            ->where('estado','=',1)
                                            ->where('idagrupacion',null)
                                            ->where('idtipocomprobante',$idtipocomprobante)
                                            ->where('con__asientomaestros.gestion',0)
                                            ->groupBy('con__asientomaestros.idasientomaestro')
                                            ->orderBy('fecharegistro','asc')
                                            ->get()
                                            ->toarray();
        }
        //dd($cabeceras);
        $jsonfinal=json_encode($cabeceras);
        
        //dd($cabeceras);
        $nuevoarray=[];
        foreach ($cabeceras as $indice => $valor) {
            //$nuevoarray=array_merge($nuevoarray,$indice);
            $jsonfinal=json_encode($valor);
            $nuevoarray[]=$jsonfinal;
            //echo "{$valor['idasientomaestro']} </br>";
            $detallediario=Con_Asientodetalle::join('con__cuentas as d','d.idcuenta','=','con__asientodetalles.idcuenta')
                                                ->select('d.idcuenta',
                                                        'd.codcuenta',
                                                        'd.nomcuenta',
                                                        'idasientomaestro',
                                                        'con__asientodetalles.idasientodetalle',
                                                        'monto',
                                                        'debe',
                                                        'haber')
                                                ->where('idasientomaestro',$valor['idasientomaestro'])                            
                                                ->orderBy('debe','desc')
                                                ->orderBy('haber','desc')
                                                ->get()->toarray();
            foreach ($detallediario as $i => $v) {
                $jsonmedio=json_encode($v);
                $nuevoarray[]=$jsonmedio;
            }
        }
        //dd($nuevoarray);
        //$nuevoarray=json_decode($nuevoarray);
        return $nuevoarray;
    }
    public function conciliacion(Request $request){
        $fechainicio = date("Y-m-d", strtotime($request->finicio));
        $fechafin=date("Y-m-d",strtotime($request->ffin));
        $raw=DB::raw('IFNULL(ifnull(SUM(debe),0)-ifnull(sum(haber),0),0) as saldo');
        $saldos=Con_Asientodetalle::join('con__asientomaestros as b','con__asientodetalles.idasientomaestro','=','b.idasientomaestro')
                                        ->select($raw)
                                        ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                        ->where('b.estado',1)
                                        ->where('b.gestion',0)
                                        ->where('b.idagrupacion',null)
                                        ->where('con__asientodetalles.idcuenta',$request->idcuenta)
                                        ->get()->toArray();
        $respuesta=$saldos[0]['saldo'];
        return $respuesta;
    }


}
