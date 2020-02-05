<?php

namespace App\Http\Controllers;

use App\Par_Prestamos;
use App\Par_prestamos_plan;
use App\Con_Perfilcuentadetalle;
use App\Con_Perfilcuentamaestro;
use App\Con_Asientomaestro;
use App\Liq_Saldosmenor;
use App\AsinalssClass\AsientoMaestroClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LiqSaldosmenorController extends Controller
{

    public function procesarsaldos(Request $request) {
        if (!$request->ajax()) return redirect('/');
        
        $sql="select ppp.fp,pp2.no_prestamo, ppp.idplan, ppp.idprestamo, pp3.codproducto, s2.numpapeleta, 
                CONCAT(s2.nombre,' ',s2.apaterno,' ',s2.amaterno) as nombre,
                ppp.pe, ppp.cut,
                ppp.idestado, pp2.apro_conta, pm.nommoneda, pp3.idproducto, pp3.moneda, pm.tipocambio
                from par__prestamos__plans ppp, par__prestamos pp2, par__productos pp3, socios s2, par__monedas pm
                where pp2.idprestamo=ppp.idprestamo
                and pp2.plazo=ppp.pe
                and pp2.plazo=(select max(ppp3.pe) from par__prestamos__plans ppp3 where ppp3.idprestamo=ppp.idprestamo)
                and pp3.idproducto=pp2.idproducto
                and s2.idsocio=pp2.idsocio
                and pm.idmoneda=pp3.moneda
                and ppp.fp='2019-10-31' -- SELECT LAST_DAY(getfecha())
                and pp2.apro_conta=1
                and pp2.idestado=2
                and (ppp.cut*pm.tipocambio)<". $request->saldomenor;

        $saldosmenores=DB::select($sql);
        return ['saldosmenores'=>$saldosmenores];
    }

    public function liquidarsaldos(Request $request) {
        if (!$request->ajax()) return redirect('/');

        $total_saldo_emergencia=0; 
        $total_saldo_regular=0; 
        $total_saldo_servicio=0; 
        $cuenta1=0;
        $cuenta2=0;
        $cuenta3=0;
        
        foreach ($request->datos as $datas) {
            //sumamos el total de los liquidos
            if ($datas['codproducto']=='G') {
                $total_saldo_emergencia = $total_saldo_emergencia + $datas['cut'];
                $cuenta1=1;
            }                
            else if ($datas['codproducto']=='F' || $datas['codproducto']=='H') {
                $total_saldo_regular = $total_saldo_regular + $datas['cut'];
                $cuenta2=1;
            }
            else {
                $total_saldo_servicio = $total_saldo_servicio + $datas['cut'];
                $cuenta3=1;
            }                
        }
        
        //mandamos a contabilidad.
        //para generar el asiento contable
        $fecharegistro=(DB::select("select getfecha() as total"))[0]->total;  //fecha del sistema
        
        //obteniendo numpapeleta
        //$numpapeleta=(DB::select("select getpapeleta($request->idsocio) as papeleta"))[0]->papeleta;

        //obteniendo los id de las cuentas segin el perfil de cuenta
        $cuentas = Con_Perfilcuentadetalle::select ('idcuenta','tipocargo')
        ->where('idperfilcuentamaestro','=',$request->idperfilcuentamaestro)
        ->get();
        
        //armado del vector a ser llenado
        foreach ($cuentas as $cue) {           
            if ($cue->tipocargo == 'd') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>'',
                    "documento"=>$request->documento,
                    "moneda" => 'bs',
                    "monto"=>$total_saldo_emergencia + $total_saldo_regular + $total_saldo_servicio
                );
            }
            else if ($cue->tipocargo == 'h') {                
                $total_r=0;

                if ($cue->idcuenta==37) {
                    $total_r=$total_saldo_emergencia;
                    $tipo='Emergencia';
                }
                    
                else if ($cue->idcuenta==58) {
                    $total_r=$total_saldo_regular;
                    $tipo='regular';
                }
                    
                else if ($cue->idcuenta==296) {
                    $total_r=$total_saldo_servicio;
                    $tipo='servicio';
                }
                    
                
                array_push($valor,
                    array (
                        "idcuenta"=>$cue->idcuenta,
                        "subcuenta"=>$tipo,
                        "documento"=>$request->documento,
                        "moneda" => 'bs',
                        "monto"=>$total_r * (-1)
                    ));
            }           
        }

        $asientomaestro = new AsientoMaestroClass();
        $respuesta=$asientomaestro->AsientosMaestroArray(
            $request->idperfilcuentamaestro,
            'Liq. Saldos Menores',//$request->nro_documento,
            $request->documento,
            $request->detalle,
            $valor,
            $request->idmodulo,  //obtener el modulo
            $fecharegistro);


        foreach ($request->datos as $datas) {            
            //actualizamos los prestamos que fueron liquidados
            $prestamo = Par_Prestamos::find($datas['idprestamo']);
            $prestamo->idestado = 5;  //estado de cancelado el prestamo
            $prestamo->save();

            //actualizando el plan
            $prestamo_plan = Par_prestamos_plan::find($datas['idplan']);
            $prestamo_plan->idestado=14;  //estado de cancelado por liquidacion de saldo menor
            $prestamo_plan->idtransaccionC='C-'.$datas['idplan'];
            $prestamo_plan->numDocC=$request->nro_documento;
            $prestamo_plan->glosa=$request->obs;
            $prestamo_plan->fechaCobranza=$request->fechaproceso;
            $prestamo_plan->importe=$datas['cut'];
            $prestamo_plan->idasiento=$respuesta;
            $prestamo_plan->idusuario=Auth::user()->id;
            $prestamo_plan->save();

            //guardamos los datos en la tabla liq__saldosmenors
            $liqsaldos = new Liq_Saldosmenor(); 
            $liqsaldos->idproducto=$datas['idproducto'];
            $liqsaldos->nro_prestamo=$datas['no_prestamo'];
            $liqsaldos->numpapeleta=$datas['numpapeleta'];
            $liqsaldos->nombre=$datas['nombre'];
            $liqsaldos->idasientomaestro=$respuesta;
            $liqsaldos->saldo=$datas['cut'];
            $liqsaldos->moneda=$datas['nommoneda'];        
            $liqsaldos->fec_liquidacion=$request->fechaproceso;        
            $liqsaldos->save();
        }           
    }


    public function rutasLiquidar(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaLiquidarsaldos');  
        return [ 
            'LIQ_SALDOS_MENORES'=> $rutas['LIQ_SALDOS_MENORES'].'&user='.Auth::user()->username.'&fecha=',
        ];
    }

    public function rutasLiquidar1(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaLiquidarsaldosprocesados');  
        return [ 
            'LIQ_SALDOS_MENORES_PROCESADOS'=> $rutas['LIQ_SALDOS_MENORES_PROCESADOS'].'&user='.Auth::user()->username.'&fecha=',
        ];
    }

    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $saldosprocesados = Liq_Saldosmenor::join('con__asientomaestros','liq__saldosmenors.idasientomaestro','con__asientomaestros.idasientomaestro')        
        ->select('con__asientomaestros.cod_comprobante','con__asientomaestros.estado','liq__saldosmenors.fec_liquidacion')       
        ->distinct('liq__saldosmenors.fec_liquidacion') 
        ->orderBy('con__asientomaestros.idasientomaestro', 'desc')->paginate(10);
        
        return [
            'pagination' => [
                'total'        => $saldosprocesados->total(),
                'current_page' => $saldosprocesados->currentPage(),
                'per_page'     => $saldosprocesados->perPage(),
                'last_page'    => $saldosprocesados->lastPage(),
                'from'         => $saldosprocesados->firstItem(),
                'to'           => $saldosprocesados->lastItem(),
            ],
            'saldosprocesados' => $saldosprocesados
        ];
    }    
}
