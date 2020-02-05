<?php

namespace App\Http\Controllers;

use App\Liq_Acreedor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\AsinalssClass\AsientoMaestroClass;
use App\Con_Perfilcuentadetalle;
use App\Con_Perfilcuentamaestro;
use App\Con_Asientomaestro;

class LiqAcreedorController extends Controller
{
    
    public function index1(Request $request)
    {         //devolucion por cobranzas
        
        if (!$request->ajax()) return redirect('/');

        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        }
        
        // if ($request->tipo=="all")
        //     $importeSaldoMenor=0;
        // else 
        //     $importeSaldoMenor=20;

        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre');
        $raw1=DB::raw('(select sum(importe) from liq__acreedors aa where aa.idsocio=liq__acreedors.idsocio) as importe1');

        if (sizeof($buscararray)>0) {
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 

                $acreedores = Liq_Acreedor::join('socios','liq__acreedors.numpapeleta','socios.numpapeleta')
                ->join('par_grados','socios.idgrado','par_grados.idgrado')
                ->leftjoin('con__asientomaestros','liq__acreedors.idasientomaestro','con__asientomaestros.idasientomaestro')
                ->select($raw,$raw1,'liq__acreedors.estado','liq__acreedors.idacreedor','liq__acreedors.numpapeleta',
                        'liq__acreedors.idsocio','liq__acreedors.moneda',
                        'liq__acreedors.importe','par_grados.nomgrado','con__asientomaestros.estado as estado_conta',
                        'con__asientomaestros.cod_comprobante')
                // ->where('importe','>',$importeSaldoMenor) //para las devoluciones        
                ->where('liq__acreedors.estado','=',0)
                ->where('liq__acreedors.idasientomaestro','=',0)
                ->where('liq__acreedors.migrado','=',0)
                ->whereraw($sqls)
                ->orderBy('idacreedor', 'asc')
                ->paginate(10);
            }
        }        
        else {
            $acreedores = Liq_Acreedor::join('socios','liq__acreedors.numpapeleta','socios.numpapeleta')
            ->join('par_grados','socios.idgrado','par_grados.idgrado')
            ->leftjoin('con__asientomaestros','liq__acreedors.idasientomaestro','con__asientomaestros.idasientomaestro')
            ->select($raw,$raw1,'liq__acreedors.estado','liq__acreedors.idacreedor','liq__acreedors.numpapeleta','liq__acreedors.idsocio',
                    'liq__acreedors.idsocio','liq__acreedors.moneda',
                    'liq__acreedors.importe','par_grados.nomgrado','con__asientomaestros.estado as estado_conta',
                    'con__asientomaestros.cod_comprobante')
            // ->where('importe','>',$importeSaldoMenor) //para las devoluciones
            // ->where('liq__acreedors.tipo','=','ascii')
            ->where('liq__acreedors.estado','=',0)
            ->where('liq__acreedors.idasientomaestro','=',0)
            ->where('liq__acreedors.migrado','=',0)
            ->orderBy('idacreedor', 'asc')
            ->paginate(10);
        }
                                                    
        return [
            'pagination' => [
                'total'        => $acreedores->total(),
                'current_page' => $acreedores->currentPage(),
                'per_page'     => $acreedores->perPage(),
                'last_page'    => $acreedores->lastPage(),
                'from'         => $acreedores->firstItem(),
                'to'           => $acreedores->lastItem(),
            ],
            'acreedores' => $acreedores
        ];

    }

    public function index(Request $request)
    {  //lista de devolucion por acreedor      
        if (!$request->ajax()) return redirect('/');

        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        }
        
        // if ($request->tipo=="all")
        //     $importeSaldoMenor=0;
        // else 
        //     $importeSaldoMenor=20;

        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre');
        $raw1=DB::raw('(select sum(importe) from liq__acreedors aa where aa.idsocio=liq__acreedors.idsocio) as importe1');
        if (sizeof($buscararray)>0) {
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                } 

                $acreedores = Liq_Acreedor::join('socios','liq__acreedors.numpapeleta','socios.numpapeleta')
                ->join('par_grados','socios.idgrado','par_grados.idgrado')
                ->leftjoin('con__asientomaestros','liq__acreedors.idasientomaestro','con__asientomaestros.idasientomaestro')
                ->select($raw,'liq__acreedors.estado','liq__acreedors.idacreedor','liq__acreedors.numpapeleta',
                        'liq__acreedors.idsocio','liq__acreedors.moneda',
                        'liq__acreedors.importe','par_grados.nomgrado','con__asientomaestros.estado as estado_conta',
                        'con__asientomaestros.cod_comprobante','con__asientomaestros.idasientomaestro')
                // ->where('importe','>',$importeSaldoMenor) //para las devoluciones
                ->where('liq__acreedors.estado','=',0)
                ->where('liq__acreedors.migrado','=',0)
                ->orwhere('liq__acreedors.estado','=',2)
                ->whereraw($sqls)
                ->orderBy('idacreedor', 'asc')
                ->paginate(10);
            }
        }        
        else {
            $acreedores = Liq_Acreedor::join('socios','liq__acreedors.numpapeleta','socios.numpapeleta')
            ->join('par_grados','socios.idgrado','par_grados.idgrado')
            ->leftjoin('con__asientomaestros','liq__acreedors.idasientomaestro','con__asientomaestros.idasientomaestro')
            ->select($raw,$raw1,'liq__acreedors.estado','liq__acreedors.idacreedor','liq__acreedors.numpapeleta','liq__acreedors.idsocio',
                    'liq__acreedors.idsocio','liq__acreedors.moneda',
                    'liq__acreedors.importe','par_grados.nomgrado','con__asientomaestros.estado as estado_conta',
                    'con__asientomaestros.cod_comprobante','con__asientomaestros.idasientomaestro')
            // ->where('importe','>',$importeSaldoMenor) //para las devoluciones
            // ->where('liq__acreedors.estado','=',0)
            ->where('liq__acreedors.estado','=',0)
            ->where('liq__acreedors.migrado','=',0)
            ->orderBy('idacreedor', 'asc')
            ->paginate(10);
        }
                                                    
        return [
            'pagination' => [
                'total'        => $acreedores->total(),
                'current_page' => $acreedores->currentPage(),
                'per_page'     => $acreedores->perPage(),
                'last_page'    => $acreedores->lastPage(),
                'from'         => $acreedores->firstItem(),
                'to'           => $acreedores->lastItem(),
            ],
            'acreedores' => $acreedores
        ];

    }

    public function cobranza(Request $request)
    {   //echo $request->importe; //saldo acreedor
        if (!$request->ajax()) return redirect('/');

        $raw=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombre');
        $raw1=DB::raw('(par__prestamos__plans.cut <'.$request->importe.' or par__prestamos__plans.in <'.$request->importe.')');

        $acreedores = Liq_Acreedor::
        join('socios','liq__acreedors.idsocio','socios.idsocio')        
        ->join('par_grados','socios.idgrado','par_grados.idgrado')
        ->join('par__prestamos','liq__acreedors.idsocio','par__prestamos.idsocio')
        ->join('par__prestamos__plans','par__prestamos.idprestamo','par__prestamos__plans.idprestamo')
        ->select($raw,'liq__acreedors.estado','liq__acreedors.idacreedor','liq__acreedors.numpapeleta',
                'liq__acreedors.importe','par_grados.nomgrado','par__prestamos.idprestamo',
                'par__prestamos__plans.in','par__prestamos__plans.cut','par__prestamos__plans.indi',
                'par__prestamos__plans.car','par__prestamos__plans.fp','par__prestamos.no_prestamo')
        ->where('par__prestamos__plans.idestado','=',2) 
        ->where('par__prestamos__plans.fp','=','2019-05-31') //-- SELECT LAST_DAY(getfecha())
        ->whereraw($raw1)
        ->where('liq__acreedors.estado','=',0) 
        ->where('liq__acreedors.idsocio','=',$request->idsocio) 
        ->orderBy('liq__acreedors.idacreedor', 'asc')
        ->paginate(10);
    
                                                    
        return [
            'pagination' => [
                'total'        => $acreedores->total(),
                'current_page' => $acreedores->currentPage(),
                'per_page'     => $acreedores->perPage(),
                'last_page'    => $acreedores->lastPage(),
                'from'         => $acreedores->firstItem(),
                'to'           => $acreedores->lastItem(),
            ],
            'cobranzaacreedordetalle' => $acreedores
        ];
    }

    public function devolucion(Request $request) {
        if (!$request->ajax()) return redirect('/');
        
        //mandamos para contabilidad
        $cuentas = Con_Perfilcuentadetalle::select ('idcuenta','tipocargo')
        ->where('idperfilcuentamaestro','=',$request->idperfilcuentamaestro)
        ->get();
        
        //armado del vector a ser llenado
        foreach ($cuentas as $cue) {           
            if ($cue->tipocargo == 'd') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>$request->numpapeleta,
                    "documento"=>$request->documento,
                    "moneda" => 'bs',
                    "monto"=>$request->importe
                );
            }
            else if ($cue->tipocargo == 'h') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>$request->numpapeleta,
                    "documento"=>$request->documento,
                    "moneda" => 'bs',
                    "monto"=>$request->importe*(-1)
                );
            }           
        }

        //para generar el asiento contable
        $fecharegistro=(DB::select("select getfecha() as total"))[0]->total;  //fecha del sistema

        $asientomaestro = new AsientoMaestroClass();
        $respuesta=$asientomaestro->AsientosMaestroArray(
            $request->idperfilcuentamaestro,
            'Dev. Descuento Indebido',
            $request->documento,
            $request->detalle,
            $valor,
            $request->idmodulo,  //obtener el modulo
            $fecharegistro);

        //actualizamos el registro 
        $devolucion = Liq_Acreedor::findorfail($request->idacreedor);        
        $devolucion->estado = 2; // ha sido devuelto
        $devolucion->save();
        
        //adicionamos el nuevo registro
        $devolucion = new Liq_Acreedor();
        
        $devolucion->detalle = $request->detalle;
        $devolucion->idsocio = $request->idsocio;
        $devolucion->numpapeleta = $request->numpapeleta;
        $devolucion->moneda = $request->moneda;
        $devolucion->documento = $request->documento;
        $devolucion->detalle = $request->detalle;
        $devolucion->importe = $request->importe*-1;
        $devolucion->fechaproceso = date('Y-m-d');
        $devolucion->idperfilcuenta = $request->idperfilcuentamaestro;
        $devolucion->idasientomaestro = $respuesta;
        $devolucion->tipo = 'DEBITO';
        $devolucion->estado = 0; // ha sido devuelto
        $devolucion->iduser = Auth::user()->id;
        $devolucion->save();
    }

    public function rutas(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaDevacreedor');  
        return [ 
        'REP_DEV_ACREEDOR'=> $rutas['REP_DEV_ACREEDOR'].'&user='.Auth::user()->username.'&id=',
        ];
    }


    //para mostrar todos los que se van liquidar
    public function procesarsaldosacreedor(Request $request) {
        if (!$request->ajax()) return redirect('/');
        
        $saldosmenoresacreedor="select la.idsocio,la.fechaproceso,la.idacreedor,la.numpapeleta,la.importe,concat(s2.nombre,' ' ,s2.apaterno, ' ',s2.amaterno) as nombre, pm.nommoneda, la.moneda
            from liq__acreedors la, socios s2, par__monedas pm
            where s2.numpapeleta=la.numpapeleta
            and la.estado=0
            and pm.idmoneda=la.moneda 
            and la.importe<".$request->saldomenoracreedor." and la.importe>0";

        $saldosmenores=DB::select($saldosmenoresacreedor);
        return ['saldosmenoresacreedor'=>$saldosmenores];
    }

    public function liquidaracreedores(Request $request) {
        if (!$request->ajax()) return redirect('/');

        $total_saldo=0;
        foreach ($request->datos as $datas) {
            //sumamos el total de los liquidos
            $total_saldo =  $total_saldo + $datas['importe'];
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
                    "monto"=>$total_saldo
                );
            }
            else if ($cue->tipocargo == 'h') {
                $valor[] = array (
                    "idcuenta"=>$cue->idcuenta,
                    "subcuenta"=>'',
                    "documento"=>$request->documento,
                    "moneda" => 'bs',
                    "monto"=>$total_saldo*(-1)
                );
            }           
        }

        $asientomaestro = new AsientoMaestroClass();
        $respuesta=$asientomaestro->AsientosMaestroArray(
            $request->idperfilcuentamaestro,
            'Liq. Saldo Acreedor',
            $request->documento,
            $request->detalle,
            $valor,
            $request->idmodulo,  //obtener el modulo
            $fecharegistro);

        foreach ($request->datos as $datas) {                        
            //actualizamos la tabla de acreedores 
            $liqacreedor =  Liq_Acreedor::findorfail($datas['idacreedor']);
            $liqacreedor->estado = 2;            
            $liqacreedor->save();
        }
        
        foreach ($request->datos as $datas) {                        
            //registramos los cobrados
            $liqacreedor =  new Liq_Acreedor();
            $liqacreedor->idsocio = $datas['idsocio'];
            $liqacreedor->numpapeleta = $datas['numpapeleta'];
            $liqacreedor->importe = $datas['importe']*-1;
            $liqacreedor->moneda = $datas['moneda'];
            $liqacreedor->documento = $request->documento;
            $liqacreedor->detalle = $request->detalle;
            $liqacreedor->tipo = 'DEBITO';
            $liqacreedor->idperfilcuenta = $request->idperfilcuentamaestro;
            $liqacreedor->idasientomaestro = $respuesta;
            $liqacreedor->estado = 99;
            $liqacreedor->fechaproceso = $request->fechaproceso;
            $liqacreedor->iduser= Auth::user()->id;
            $liqacreedor->save();
        }
    }

    public function rutasLiquidar(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaLiquidaracreedor');  
        return [ 
            'LIQ_SALDOS_ACREEDOR'=> $rutas['LIQ_SALDOS_ACREEDOR'].'&user='.Auth::user()->username.'&fecha=',
        ];
    }

    public function reportesgeneral(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
         $rutas=config('app.rutaLiqReportes');  
        return [ 
		'REP_GENERAL'=> $rutas['REP_GENERAL'].'&user='.Auth::user()->id,		
        ];
    }

    public function cobra(Request $request)
    {   
        if (!$request->ajax()) return redirect('/'); 
        
        //actualizamos el registro 
        
        //enviamos a cobrar
        //$cobrar = jjcc($request->idacreedor, $request->idprestamo);    
        $cobrar['importe']=-10.58;

        $cobra1 = Liq_Acreedor::findorfail($request->idacreedor);
        if ($cobrar['importe']*-1 == $request->importe)
            $cobra1->estado = 2; // se cobro todo
        else
            $cobra1->estado = 1; // se cobro parte
        $cobra1->save();

        //creamos nuevo registro 
        $cobra = new Liq_Acreedor();
        $cobra->idsocio = $request->idsocio;
        $cobra->numpapeleta = $request->numpapeleta;
        $cobra->importe = $cobrar['importe'];
        $cobra->moneda = $request->moneda;
        $cobra->detalle = $request->detalle;
        $cobra->documento = $request->documento;
        $cobra->tipo = 'DEBITO';
        $cobra->idprestamo = $request->idprestamo;            
        $cobra->fechaproceso = date('Y-m-d');            
        $cobra->iduser = Auth::user()->id;

        if ($cobrar['importe']*-1 == $request->importe)
            $cobra->estado = 2;     // se cobro todo         
        else
            $cobra->estado = 0; // se cobro parte
        $cobra->save();
        
    }

    public function lista(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $saldosprocesadosacreedor = Liq_Acreedor::join('con__asientomaestros','liq__acreedors.idasientomaestro','con__asientomaestros.idasientomaestro')
        ->select('liq__acreedors.fechaproceso','con__asientomaestros.cod_comprobante','con__asientomaestros.estado')       
        ->where('liq__acreedors.estado','=',99)
        ->distinct('liq__acreedors.fechaproceso') 
        ->orderBy('con__asientomaestros.idasientomaestro', 'desc')->paginate(10);
        
        return [
            'pagination' => [
                'total'        => $saldosprocesadosacreedor->total(),
                'current_page' => $saldosprocesadosacreedor->currentPage(),
                'per_page'     => $saldosprocesadosacreedor->perPage(),
                'last_page'    => $saldosprocesadosacreedor->lastPage(),
                'from'         => $saldosprocesadosacreedor->firstItem(),
                'to'           => $saldosprocesadosacreedor->lastItem(),
            ],
            'saldosprocesadosacreedor' => $saldosprocesadosacreedor
        ];
    }

}
