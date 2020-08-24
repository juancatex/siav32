<?php

namespace App\Http\Controllers;

use App\Con_Factura;
use App\Con_FacturaParametro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\AsinalssClass\FacturaClass;
use Illuminate\Support\Facades\DB;

class ConFacturaController extends Controller
{
    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $factura = Con_factura::orderBy('idfactura', 'desc')->paginate(10);
        }
        else{
            $factura = Con_factura::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idfactura', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $factura->total(),
                'current_page' => $factura->currentPage(),
                'per_page'     => $factura->perPage(),
                'last_page'    => $factura->lastPage(),
                'from'         => $factura->firstItem(),
                'to'           => $factura->lastItem(),
            ],
            'factura' => $factura
        ];
    }
        
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');      
        
        $validator = Validator::make($request->all(), [
            'idfactura' => 'unique:con__facturas'
        ]);         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }

        $detalle_t='';
        for ($i=0; $i<count($request->detalle); $i++) {            
            $detalle_d = $request->detalle[$i]['product_name'].'|'.$request->detalle[$i]['product_price']
            .'|'.$request->detalle[$i]['product_qty'].'|'.$request->detalle[$i]['line_total'];
            if ($i==0)
                $detalle_t = $detalle_d;
            else 
                $detalle_t = $detalle_t.','.$detalle_d; 
        } 

        if (!$request->ajax()) return redirect('/');
        $factura = new Con_factura();
        $factura->idfacturaparametro = 1;
        $factura->numerofactura = $request->numerofactura;
        $factura->codigocontrol = $request->codigocontrol;
        $factura->razonsocial = $request->razonsocial;        
        $factura->nit = $request->nit;
        $factura->detalle = $detalle_t;
        $factura->importetotal = $request->importetotal;
        $factura->importecf = $request->importetotal;        
        $factura->activo = '1';
        $factura->save();
    }

    public function update(Request $request)
    {
        
        // $facturaclass = new FacturaClass();
        // $respuesta=$facturaclass->RegistraFactura('nombre','214243','detalle',23,23);
        // exit();
        
        if (!$request->ajax()) return redirect('/'); 
        $factura = Con_factura::findOrFail($request->idfactura);

        $detalle_t='';
        for ($i=0; $i<count($request->detalle); $i++) {            
            $detalle_d = $request->detalle[$i]['product_name'].'|'.$request->detalle[$i]['product_price']
            .'|'.$request->detalle[$i]['product_qty'].'|'.$request->detalle[$i]['line_total'];
            if ($i==0)
                $detalle_t = $detalle_d;
            else 
                $detalle_t = $detalle_t.','.$detalle_d; 
        } 

        $factura->idfacturaparametro = 1;
        $factura->numerofactura = $request->numerofactura;
        $factura->codigocontrol = $request->codigocontrol;
        $factura->razonsocial = $request->razonsocial;        
        $factura->nit = $request->nit;
        $factura->detalle = $detalle_t;
        $factura->importetotal = $request->importetotal;
        $factura->importecf = $request->importetotal;           
        $factura->activo = '1';
        $factura->save();
    }

    public function dataparametro(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
                
        $facturaparametro = Con_FacturaParametro::orderBy('idfacturaparametro', 'desc')->where('activo','=','1')->paginate(10);
        
        return [
            'facturaparametro' => $facturaparametro
        ];
    }    

    public function maxfactura(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
                
        $maxfactura = Con_Factura::orderBy('idfactura', 'desc')->where('activo','=','1')->max('numerofactura');         
        return ['maxfactura' => $maxfactura];
    }   

    public function proceso(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
                
        //variables
        $comprobante = $request->com;
        $cuenta_pre_reg = '41101101';
        $porcentaje_pre_reg = 0.20; 
        $porcentaje_aportes = 0.80;
        $porcentaje_87 = 0.87;
        $porcentaje_deb_fis = 0.13;
        $porcentaje_imp_tra = 0.03;
        $tipo = 'SEC_CON_COM_TRASPASO';
        $cuenta_aporte='22501101';
        $subcuenta='20000000000';
        $cuenta_deb_fis = '21301102';
        $cuenta_imp_tra = '21301103';
        $cuenta_imp_tra_debe = '51301106';
        $ok = "Query Ok";
        $nook = "Query Error";


        $productos=DB::connection('pgsql')->select("SELECT 
        round(importe_moneda_local*$porcentaje_pre_reg,2) as bol20, round(importe_moneda_origen*$porcentaje_pre_reg,2) as dol20,
        round(importe_moneda_local*$porcentaje_aportes,2) as bol80, round(importe_moneda_origen*$porcentaje_aportes,2) as dol80,
        importe_moneda_local, importe_moneda_origen, fecha_transaccion, glosa,
        round((importe_moneda_local*$porcentaje_pre_reg*$porcentaje_87),2) as bol87, round((importe_moneda_origen*$porcentaje_pre_reg*$porcentaje_87),2) as dol87,
        round((importe_moneda_local*$porcentaje_pre_reg*$porcentaje_deb_fis),2) as bol13, round((importe_moneda_origen*$porcentaje_pre_reg*$porcentaje_deb_fis),2) as dol13,
        round((importe_moneda_local*$porcentaje_pre_reg*$porcentaje_imp_tra),2) as bol03, round((importe_moneda_origen*$porcentaje_pre_reg*$porcentaje_imp_tra),2) as dol03
        FROM finanzas.con_tr_detalles ctd 
        where id_transaccion = '$comprobante' 
        and id_tipo = '$tipo'
        and cuenta = '$cuenta_pre_reg'
        order by item");    

        $sumabol20=0;
        $sumausd20=0;
        $sumabol80=0;
        $sumausd80=0;
        $sumabol87=0;
        $sumausd87=0;
        $sumabol13=0;
        $sumausd13=0;
        $sumabol03=0;
        $sumausd03=0;
        $sumabol=0;
        $sumausd=0;
        $fecha_transaccion='';
        $glosa='';
        $array_out=[];

        foreach($productos as $rowpsql){  
            $sumabol20 = $rowpsql->bol20 + $sumabol20;
            $sumausd20 = $rowpsql->dol20 + $sumausd20;
            $sumabol80 = $rowpsql->bol80 + $sumabol80;
            $sumausd80 = $rowpsql->dol80 + $sumausd80;
            $sumabol = $rowpsql->importe_moneda_local + $sumabol;
            $sumausd = $rowpsql->importe_moneda_origen + $sumausd;
            $fecha_transaccion = $rowpsql->fecha_transaccion;
            $glosa = $rowpsql->glosa;
            $sumabol87 = $rowpsql->bol87 + $sumabol87;
            $sumausd87 = $rowpsql->dol87 + $sumausd87;
            $sumabol13 = $rowpsql->bol13 + $sumabol13;
            $sumausd13 = $rowpsql->dol13 + $sumausd13;
            $sumabol03 = $rowpsql->bol03 + $sumabol03;
            $sumausd03 = $rowpsql->dol03 + $sumausd03;
        }

        $array_out['sumabol20']=$sumabol20;

        echo 'total BOL 20%: ', $sumabol20, '<br>';
        echo 'total BOL 80%: ', $sumabol80, '<br>';
        echo 'total BOL 87%: ', $sumabol87, '<br>';
        echo 'total BOL 13%: ', $sumabol13, '<br>';
        echo 'total BOL 03%: ', $sumabol03, '<br>';

        echo 'total BOL 100%: ', $sumabol, '<br><br>';
        echo 'total USD 20%: ', $sumausd20, '<br>';
        echo 'total USD 80%: ', $sumausd80, '<br>';
        echo 'total USD 87%: ', $sumausd87, '<br>';
        echo 'total USD 13%: ', $sumausd13, '<br>';
        echo 'total USD 03%: ', $sumausd03, '<br>';
        echo 'total USD 100%: ', $sumausd, '<br>';


        return ['proceso'=>$array_out,'pgsql'=>count($productos)];

    }
}

