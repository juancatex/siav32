<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Con_Asientodetalle;
use Illuminate\Support\Facades\DB;
use App\Con_Asientomaestro;
use App\Con_Cuentas;
use App\Con_Asientosubcuenta;
use App\AsinalssClass\AsientoMaestroClass;
use App\Fil_Filial;
use App\Fil_Unidad;
use App\Con_Cuentanivel1;
use App\Con_Cuentanivel2;
use App\Con_Cuentanivel3;
use App\Con_Cuentanivel4;
use App\Con_Cuentanivel5;



class ConReportesController extends Controller
{
    public function rutas(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaConReportes');  
        return [ 
        'REP_ASIENTO_AUTOMATICO' => $rutas['REP_ASIENTO_AUTOMATICO'],
        'REP_LIBRO_COMPRAS'=>$rutas['REP_LIBRO_COMPRAS'],
        'REP_LIBRO_VENTAS'=>$rutas['REP_LIBRO_VENTAS'],
        'REP_SEG_CCUENTAS'=>$rutas['REP_SEG_CCUENTAS'],
        'REP_PLAN_CUENTAS'=>$rutas['REP_PLAN_CUENTAS'],
        'REP_DOC_OBLIGACION'=>$rutas['REP_DOC_OBLIGACION'],
        'REP_DOC_OBLIGACION_DIRECTORIO'=>$rutas['REP_DOC_OBLIGACION_DIRECTORIO']
        ];
    }
    public function libromayor(Request $request)
    {
        $fechainicio = date("Y-m-d", strtotime($request->fechai));
        $fechafin=date("Y-m-d",strtotime($request->fechaf));
        $cuentainicio=$request->cuentai;
        $cuentafin=$request->cuentaf;
        $filial=$request->filial;
        $unidad=$request->idunidad;
        $idempleado=$request->idempleado;
        if($filial!=0)
        {
            if($filial==1)
            {
                if($unidad!=0)
                {
                    $datos_filial=Fil_Filial::select('sigla')
                                                ->where('idfilial',$filial)
                                                ->get();
                    
                    $datos_unidad=Fil_Unidad::select('nomunidad')
                                                ->where('idunidad',$unidad)
                                                ->get();
                    $dunidad=$datos_unidad[0]->nomunidad;
                }
                else
                {
                    $datos_filial=Fil_Filial::select('sigla')
                                        ->where('idfilial',$filial)
                                        ->get();
                    $dunidad='Todas';
                }   
            }
            else 
            {
                $datos_filial=Fil_Filial::select('sigla')
                                            ->where('idfilial',$filial)
                                            ->get();
                $dunidad='';
            }   
            
            $dfilial=$datos_filial[0]->sigla;
            
        }  
        else
        {
            $dfilial='Todas';
            $dunidad='';
        }   




        $codcuentas=Con_Asientodetalle::join('con__asientomaestros as b','b.idasientomaestro','con__asientodetalles.idasientomaestro')
                                        ->join('con__cuentas as c','c.idcuenta','con__asientodetalles.idcuenta')
                                        ->select('c.idcuenta','c.codcuenta','c.nomcuenta')
                                        ->wherebetween('codcuenta',[$cuentainicio,$cuentafin])
                                        ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                        //->where('b.estado','1')
                                        ->where(function($query) {
                                            $query->where('b.estado', 1)
                                                ->orWhere('b.estado', 5);
                                        })
                                        ->where('b.idagrupacion',null)
                                        ->where('b.gestion',0)
                                        ->where(function($query) use ($filial,$unidad){
                                            if($filial!=0)
                                            {
                                                if($filial==1)
                                                {
                                                    if($unidad!=0)
                                                    {
                                                        //echo "entra idunidad";
                                                        $query->where('b.idfilial',$filial);
                                                        $query->where('b.idunidad',$unidad);
                                                    }
                                                    else
                                                        $query->where('b.idfilial',$filial);
                                                }
                                                else
                                                    $query->where('b.idfilial',$filial);
                                            }
                                        })
                                        ->groupBy('nomcuenta')
                                        ->orderBy('codcuenta','asc')
                                        ->get();

        
        $diauno=date("Y");
        $diauno=$diauno."-01-01";
        //return $codcuentas;
        foreach ($codcuentas as $indice => $valor) {
            $idcuenta=$valor->idcuenta;
            $raw=DB::raw('IFNULL(ifnull(SUM(debe),0)-ifnull(sum(haber),0),0) as saldo');
            $raw2=DB::raw('IFNULL(con__asientodetalles.idcuenta,'.$idcuenta.') as idcuenta');
            $saldos=Con_Asientodetalle::join('con__asientomaestros as b','con__asientodetalles.idasientomaestro','b.idasientomaestro')
                                        ->join('con__cuentas as c','con__asientodetalles.idcuenta','c.idcuenta')
                                        ->select($raw)
                                        //->whereBetween(DB::raw('date(fecharegistro)'), [$diauno, $fechainicio])
                                        ->where(DB::raw('date(fecharegistro)'),'<',$fechainicio)
                                        ->where('b.estado',1)
                                        ->where('b.idagrupacion',null)
                                        ->where('con__asientodetalles.idcuenta',$idcuenta)
                                        ->where('b.gestion',0)
                                        ->get()->toArray();

            //dd($saldos);
            if(count($saldos)>0)
                $valor->saldo=$saldos[0]['saldo'];
            else
                $valor->saldo=0;
            //dd($codcuentas);
            $detallemayor = Con_Asientodetalle::join('con__asientomaestros as b','b.idasientomaestro','con__asientodetalles.idasientomaestro')
                                                ->join('par__modulos as c','c.idmodulo','b.idmodulo')
                                                ->join('con__cuentas as d','d.idcuenta','con__asientodetalles.idcuenta')
                                                ->join('con__tipocomprobantes','b.idtipocomprobante','con__tipocomprobantes.idtipocomprobante')
                                                ->select('b.idasientomaestro',
                                                            'b.fechavalidado',
                                                            'b.cod_comprobante',
                                                            'nomtipocomprobante',
                                                            'b.tipodocumento',
                                                            'b.numdocumento',
                                                            'b.glosa',
                                                            'monto',
                                                            'debe',
                                                            'haber',
                                                            'b.fecharegistro',
                                                            'b.estado')
                                                    // ->whereBetween('codcuenta',[$cuentainicio,$cuentafin])
                                                    ->where('d.idcuenta',$idcuenta)
                                                    //->where('b.estado',1)
                                                    ->where(function($query) {
                                                        $query->where('b.estado', 1)
                                                            ->orWhere('b.estado', 5);
                                                    })
                                                    ->where('b.idagrupacion',null)
                                                    //->whereBetween(DB::raw('date(fechavalidado)'), [$fechainicio, $fechafin])  //para registros validados
                                                    ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])  //incluye registros en borrador
                                                    ->where('b.gestion',0)
                                                    ->orderBy('codcuenta','desc')
                                                    //->orderBy('fechavalidado','asc')
                                                    ->orderBy('fecharegistro','asc')
                                                    ->get();
            //dd($detallemayor);
            $valor->detalles=$detallemayor;
            //dd($codcuentas);
            $saldofinal=$valor->saldo;
            foreach ($detallemayor as $value) {
                $saldofinal=$saldofinal+$value->monto;
                $idasientomaestro=$value->idasientomaestro;
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
                                                    ->where('idcuenta',$idcuenta)
                                                    ->where('tipo_subcuenta',1);//tipo subcuenta  socios
                                                    
                                                    
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
                                                    ->where('idcuenta',$idcuenta)
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
                                                    ->where('idcuenta',$idcuenta)
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
                                                            ->where('idcuenta',$idcuenta)
                                                            ->where('tipo_subcuenta',4)// tipo subcuenta subascinalss
                                                            ->union($otros)
                                                            ->get();
                //dd($subascinalss);

                if(count($subascinalss)==0)
                    $value->subdetalles=[];
                else
                    $value->subdetalles=$subascinalss;
            
                $value->saldofinal=$saldofinal;
            }
            
        
        }
        return view('libro_mayor')->with(['arraymayor'=>$codcuentas,
                                            'nomfilial'=>$dfilial,
                                            'nomsigla'=>$dunidad]);
        //return($codcuentas);
        //dd($codcuentas);


       
    }
    public function balancegeneral(Request $request)
    {
        $nivel=$request->nivel;
        $fechainicio = date("Y-m-d", strtotime($request->fechai));
        $fechafin=date("Y-m-d",strtotime($request->fechaf));
        $activo=1;
        $pasivo=2;
        $patrimonio=3;

        /* $cuentas=Con_Cuentanivel1::join('ccon__cuentanivel2','con__cuentanivel1.idcuentanivel1','con__cuentanivel2.codn1')
                                    ->join('con__cuentanivel3','con__cuentanivel2.codn2','con__cuentanivel3.codn2')
                                    ->join('con__cuentanivel4','con__cuentanivel3.codn3','con__cuentanivel4.codn3')
                                    ->join('con__cuentanivel5','con__cuentanivel4.codn4','con__cuentanivel5.codn4')
                                    ->join('con__cuentas','con__cuentas.codn5','con__cuentanivel5.codn5')
                                    ->select('nomcuentan1',
                                                'nomcuentan2',
                                                'nomcuentan3',
                                                'nomcuenta',
                                                'codcuenta')
                                    ->where('con__cuentanivel1.activo',1)
                                    ->where('con__cuentanivel2.activo',1)
                                    ->where('con__cuentanivel3.activo',1)
                                    ->where('con__cuentanivel4.activo',1)
                                    ->where('con__cuentanivel5.activo',1)
                                    ->where('con__cuentas.activo',1)
                                    ->orderby('codcuenta','asc')
                                    ->get(); */
        
        
        
        
        
        
        $cuentan1=Con_Cuentanivel1::select('codn1','nomcuentan1')
                                    ->where('activo',1)
                                    ->wherein('codn1',[1,2,3]) // cambiar los ids de las cuentas para que sea dinamico
                                    ->get();
        $saldonn=0;
        foreach ($cuentan1 as $valorn1) {
            
            $cuentan2=Con_cuentanivel2::select('codn2','nomcuentan2')
                                        ->where('activo',1)
                                        ->where('codn1',$valorn1->codn1)
                                        ->get();
            $valorn1->cuentan2=$cuentan2; 
            $saldon1=0;
            //dd($cuentan2);
            foreach ($cuentan2 as $valorn2) {
                
                $cuentan3=Con_cuentanivel3::select('codn3','nomcuentan3')
                                            ->where('activo',1)
                                            ->where('codn2',$valorn2->codn2)
                                            ->get();
                $valorn2->cuentan3=$cuentan3;
                $saldon2=0;
                foreach ($cuentan3 as $valorn3) {
                    
                    $cuentan4=Con_cuentanivel4::select('codn4','nomcuentan4')
                                                ->where('activo',1)
                                                ->where('codn3',$valorn3->codn3)
                                                ->get();
                    //dd($valorn3);
                    $valorn3->cuentan4=$cuentan4;
                    $saldon3=0;
                    foreach ($cuentan4 as $valorn4) {
                        
                        $cuentan5=Con_cuentanivel5::select('codn5','nomcuentan5')
                                                    ->where('activo',1)
                                                    ->where('codn4',$valorn4->codn4)
                                                    ->get();
                        $valorn4->cuentan5=$cuentan5;
                        $saldon4=0;
                        //dd($cuentan1);
                        foreach ($cuentan5 as $valorn5) {
                            
                            $cuentas=Con_Cuentas::select('idcuenta','codcuenta','nomcuenta')
                                                    ->where('activo',1)
                                                    ->where('codn5',$valorn5->codn5)
                                                    ->get();
                            $valorn5->cuentas=$cuentas;
                            $saldon5=0;
                            
                            foreach ($cuentas as $valuec) {
                                    $raw=DB::raw('IFNULL(ifnull(SUM(debe),0)-ifnull(sum(haber),0),0) as saldo');
                                    /* $saldos=Con_Asientodetalle::join('con__asientomaestros as b','con__asientodetalles.idasientomaestro','=','b.idasientomaestro')
                                            ->select($raw)
                                            ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                            ->where('b.estado',1)
                                            ->where('b.gestion',0)
                                            ->where('b.idagrupacion',null)
                                            ->where('con__asientodetalles.idcuenta',$request->idcuenta)
                                            ->get() */
                                    $detalles=Con_Asientomaestro::join('con__asientodetalles','con__asientomaestros.idasientomaestro','con__asientodetalles.idasientomaestro')
                                                                    ->select($raw)
                                                                    ->where('idcuenta',$valuec->idcuenta)
                                                                    ->where('con__asientomaestros.estado',1)
                                                                    ->where('con__asientomaestros.gestion',0)
                                                                    ->where('con__asientomaestros.idagrupacion',null)
                                                                    ->groupBy('idcuenta')
                                                                    ->get();
                                    //dd($detalles[0]->saldo);
                                    if(count($detalles)>0)
                                        $saldoc=$detalles[0]->saldo;
                                    else
                                        $saldoc=0;
                                    
                                    $valuec->saldos=$saldoc;
                                    $saldon5=$saldon5+$saldoc;
                                    
                            }
                            //dd($cuentan1);
                            $saldon4=$saldon4+$saldon5;
                            $valorn5->saldon5=$saldon5;
                        }
                        $saldon3=$saldon3+$saldon4;
                        $valorn4->saldon4=$saldon4;
                    }
                    $saldon2=$saldon2+$saldon3;
                    $valorn3->saldon3=$saldon3;
                } 
                $saldon1=$saldon1+$saldon2;
                $valorn2->saldon2=$saldon2;
            }
            $saldonn=$saldonn+$saldon1;
            $valorn1->saldonn=$saldonn;
        }
            
        return view('balance_general')->with(['cuentas'=>$cuentan1]);
        //dd($cuentan1);
        //return ['cuentas'=>$cuentan1];
    }

    /* public function libromayor(Request $request) // reporte vue
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
       

        return $jsonfinal;
    } */
    /* public function librodiario(Request $request) //reporte vue
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
    } */
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

    public function librodiario(Request $request) //reporte doompdf
    {
        $totaldebe=0;
        $totalhaber=0;
        $fechainicio = date("Y-m-d", strtotime($request->fechai));
        $fechafin=date("Y-m-d",strtotime($request->fechaf));
        $idtipocomprobante=$request->idtipocomprobante;
        $filial=$request->filial;
        $unidad=$request->idunidad;
        $subcuenta=$request->idempleado;
        $raw=DB::raw('sum(debe) as sdebe, sum(haber) as shaber');
        $filtro=$idtipocomprobante;
        $maestros=Con_Asientomaestro::select('con__asientomaestros.idasientomaestro',
                                        'cod_comprobante',
                                        'fecharegistro',
                                        'fechavalidado',
                                        'tipodocumento',
                                        'numdocumento',
                                        'nomtipocomprobante',
                                        'glosa',
                                        'cont_comprobante',
                                        $raw,
                                        'sigla',
                                        'nomunidad')
                                    ->join('con__tipocomprobantes','con__asientomaestros.idtipocomprobante','con__tipocomprobantes.idtipocomprobante')
                                    ->join('con__asientodetalles','con__asientomaestros.idasientomaestro','con__asientodetalles.idasientomaestro')
                                    ->join('fil__filials','con__asientomaestros.idfilial','fil__filials.idfilial')
                                    ->leftjoin('fil__unidads','con__asientomaestros.idunidad','fil__unidads.idunidad')
                                    ->whereBetween(DB::raw('date(fecharegistro)'), [$fechainicio, $fechafin])
                                    ->where('estado','=',1)
                                    ->where('idagrupacion',null)
                                    ->where('con__asientomaestros.gestion',0)
                                    ->where(function($query) use ($filtro){
                                        if($filtro!=0)
                                            $query->where('con__asientomaestros.idtipocomprobante',$filtro);
                                    })
                                    ->where(function($query) use ($filial,$unidad){
                                        if($filial!=0)
                                        {
                                            if($filial==1)
                                            {
                                                if($unidad!=0)
                                                {
                                                    //echo "entra idunidad";
                                                    $query->where('con__asientomaestros.idfilial',$filial);
                                                    $query->where('con__asientomaestros.idunidad',$unidad);
                                                }
                                                else
                                                    $query->where('con__asientomaestros.idfilial',$filial);
                                            }
                                            else
                                                $query->where('con__asientomaestros.idfilial',$filial);
                                        }
                                        
                                    })

                                    ->groupBy('con__asientomaestros.idasientomaestro')
                                    ->orderBy('fecharegistro','asc')
                                    ->get();
        //dd($maestros);
        
        foreach ($maestros as $key => $value) {
            //echo $value->idasientomaestro;
            $asientodetalle=Con_Asientodetalle::join('con__cuentas as d','d.idcuenta','=','con__asientodetalles.idcuenta')
                                                ->select('d.idcuenta',
                                                        'd.codcuenta',
                                                        'd.nomcuenta',
                                                        'idasientomaestro',
                                                        'con__asientodetalles.idasientodetalle',
                                                        'monto',
                                                        'debe',
                                                        'haber')
                                                ->where('idasientomaestro',$value->idasientomaestro)                            
                                                ->orderBy('debe','desc')
                                                ->orderBy('haber','desc')
                                                ->get();
            
            
            $idasientomaestro=$value->idasientomaestro;
            
            $arrayrespuesta=new AsientoMaestroClass();
            $respuesta=$arrayrespuesta->recuperarsubdetalles($asientodetalle,$idasientomaestro);

            $value->asientodetalle=$respuesta;
            $totaldebe=$totaldebe+$value->sdebe;
            $totalhaber=$totalhaber+$value->shaber;
        }

        return view('libro_diario')->with(['arraydiario'=>$maestros,
                                            'totaldebe'=>$totaldebe,
                                            'totalhaber'=>$totalhaber]);
        //return $maestros;

            
            
            
            
            
            
            ///////////
           /*  $cabeceras=Con_Asientomaestro::join('con__asientodetalles as a','a.idasientomaestro','=','con__asientomaestros.idasientomaestro')
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
        return $nuevoarray; */
    }



}
