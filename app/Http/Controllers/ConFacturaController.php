<?php

namespace App\Http\Controllers;

use App\Con_Factura;
use App\Con_FacturaParametro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\AsinalssClass\FacturaClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

    public function procesoservicio(Request $request)
    {
        if (!$request->ajax()) return redirect('/');   

        $valuedb=$request->valuedb;
        $valuetipo=$request->valuetipo;
        $numcomprobante=$request->numcomprobante;
 
        $valida_1=DB::connection($valuedb)->select("select det.analisis_auxiliar,det.id_reg ,cu.descripcion,det.cuenta,det.id_sub_cuenta, det.importe_moneda_local,det.tipo_cambio,p.nombrecompleto, g.abrev 
        from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
                where  det.cuenta =cu.cuenta
                and p.par_profesion=g.cod
                and det.id_sub_cuenta=p.numero_papeleta
        and det.id_transaccion ='$numcomprobante' 
        and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");

        $cuentas=DB::connection($valuedb)->select("SELECT cuenta, descripcion
        FROM finanzas.con_plan_cuentas");

        return ['values'=>$valida_1,'cuentas'=>$cuentas];
    }

    public function procesoReserva(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');   

        $valuedb=$request->valuedb;
        $valuetipo=$request->valuetipo;
        $numcomprobante=$request->numcomprobante;

        DB::connection($valuedb)->statement( "CREATE TEMP TABLE  tmp_contable_detalle AS (
            select det.*
           from finanzas.con_tr_detalles  det 
           where 
           det.id_transaccion ='$numcomprobante'   
           and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta )");

 
        $valida_1=DB::connection($valuedb)->select("select det.*,cu.descripcion,p.nombrecompleto, g.abrev 
        from tmp_contable_detalle  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
                where  det.cuenta =cu.cuenta
                and p.par_profesion=g.cod
                and det.id_sub_cuenta=p.numero_papeleta
        and det.id_transaccion ='$numcomprobante' 
        and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");


        

        // $valida_1=DB::connection($valuedb)->select("select det.analisis_auxiliar,det.id_reg ,cu.descripcion,det.cuenta,det.id_sub_cuenta, det.importe_moneda_local,det.tipo_cambio,p.nombrecompleto, g.abrev 
        // from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
        //         where  det.cuenta =cu.cuenta
        //         and p.par_profesion=g.cod
        //         and det.id_sub_cuenta=p.numero_papeleta
        // and det.id_transaccion ='$numcomprobante' 
        // and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");

        

        $sql_max_reg=DB::connection($server)->select("select max(id_reg) as max_reg from tmp_contable_detalle");
        $max_reg = $sql_max_reg[0]->max_reg;

        foreach($valida_1 as $linea){
                if($linea->cuenta=='41101101'){
                    $max_reg++;

                    $sql_max_item=DB::connection($server)->select("select max(item)+1 as max from finanzas.con_tr_detalles 
                    where  id_transaccion ='$numcomprobante'
                    and id_tipo = '$valuetipo'");
                            
                    $max_item = $sql_max_item[0]->max;
                     
                    
                    $setenta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)*0.7,2);
                    $treinta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)*0.3,2);
                    $setenta_por=$linea->importe_moneda_local>0?$setenta_por:$setenta_por*-1;
                    $treinta_por=$linea->importe_moneda_local>0?$treinta_por:$treinta_por*-1;
                    $cien_por=(round(($setenta_por+$treinta_por),2));
                   
                    echo $linea->fecha_transaccion.'<br>';
                    echo $linea->importe_moneda_local.' = '. $cien_por.'<br>';
                   // $linea->importe_moneda_local=$setenta_por;
                   
                    if($linea->importe_moneda_local==$cien_por){

                    }else{
                        return ['mensaje'=>'No coenciden los datos 70 - 30 %'];
                    }
                   
                    echo $linea->importe_moneda_local;
                }
        } 


 
 
   
$productList = DB::connection($valuedb)->statement( "DROP TABLE tmp_contable_detalle");
 
    }

    public function updateCuentaComprobante(Request $request)
    {
        if (!$request->ajax()) return redirect('/');   

        $cuentaA=$request->cuentaA;// cuenta origen
        $cuentaB=$request->cuentaB;//cuenta a cambiar
        $idtransaccion=$request->idtransaccion;
        $tipo=$request->tipo;
        $valuedb=$request->valuedb;
        $directory='LogSafcon';
        Storage::makeDirectory($directory); 
        Storage::append($directory.'/log.txt','DB:'.$valuedb.'     Comprobante:'.$idtransaccion.'         Tipo:'.$tipo.'          Cuenta origen:'.$cuentaA.'  a Cuenta destino:'.$cuentaB.'     user:'.Auth::user()->username.'             id:'.Auth::id());
                        
 
        $valida_1=DB::connection($valuedb)->update("update finanzas.con_tr_detalles set 
        cuenta = '$cuentaB' where 
        id_transaccion ='$idtransaccion' and 
        id_tipo ='$tipo' and 
        cuenta = '$cuentaA'");
  
        return ['data'=>$valida_1];
    }

    public function proceso(Request $request)
    {
        if (!$request->ajax()) return redirect('/');        
                
        //variables
        $datos[]='';
        $flag=$request->flag;
        $host=$request->host;
        $tipo=$request->tipo;

        $comprobante = $request->com;
        $cuenta_pre_reg = '41101101';
        $porcentaje_pre_reg = 0.20; 
        $porcentaje_aportes = 0.80;
        $porcentaje_87 = 0.87;
        $porcentaje_deb_fis = 0.13;
        $porcentaje_imp_tra = 0.03;
        
        $subcuenta='20000000000';
        $datos['cuenta_aporte']=$cuenta_aporte='22501101';
        $datos['cuenta_deb_fis']=$cuenta_deb_fis = '21301102';
        $datos['cuenta_imp_tra']=$cuenta_imp_tra = '21301103';
        $datos['cuenta_imp_tra_debe']=$cuenta_imp_tra_debe = '51301106';
        $ok = "Query Ok";
        $nook = "Query Error";

        if ($host==1) $server='pgsql_desarrollo'; 
        else if ($host==2) $server='pgsql'; 
        else {return ['mensaje'=>'No se eligio un servidor'];}

        //verificamos que  no se hice ya el procedimiento,
        //verificando si existen las cuentas:22501101,21301102,21301103
        //si existen en el coprobante => no se hace nada

        $valida_1=DB::connection($server)->select("select cuenta, importe_moneda_local from finanzas.con_tr_detalles ctd 
        where id_transaccion ='$comprobante' 
        and id_tipo ='$tipo'
        and (cuenta = '$cuenta_aporte' or cuenta = '$cuenta_deb_fis' or cuenta = '$cuenta_imp_tra' or cuenta = '$cuenta_imp_tra_debe')");
        
        if (count($valida_1)>0) {
            foreach ($valida_1 as $va1) {
                $ex['cuenta']=$va1->cuenta;
                $ex['importe']=$va1->importe_moneda_local;                
            }
            
            return ['mensaje'=>'Cuentas con importes ', 'existe'=>$ex];
        }
        
        $productos=DB::connection($server)->select("SELECT 
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

        if (count($productos)==0) {
            return ['mensaje'=>'No existen datos'];
        }        

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
            $sumabol87 = $rowpsql->bol87 + $sumabol87;
            $sumausd87 = $rowpsql->dol87 + $sumausd87;
            $sumabol13 = $rowpsql->bol13 + $sumabol13;
            $sumausd13 = $rowpsql->dol13 + $sumausd13;
            $sumabol03 = $rowpsql->bol03 + $sumabol03;
            $sumausd03 = $rowpsql->dol03 + $sumausd03;
            $sumabol = $rowpsql->importe_moneda_local + $sumabol;
            $sumausd = $rowpsql->importe_moneda_origen + $sumausd;
            $fecha_transaccion = $rowpsql->fecha_transaccion;
            $glosa = $rowpsql->glosa;
        }

        $array_out['sumabol20']=$sumabol20;
        $array_out['sumausd20']=$sumausd20;
        $array_out['sumabol80']=$sumabol80;
        $array_out['sumausd80']=$sumausd80;
        $array_out['sumabol87']=$sumabol87;
        $array_out['sumausd87']=$sumausd87;
        $array_out['sumabol13']=$sumabol13;
        $array_out['sumausd13']=$sumausd13;
        $array_out['sumabol03']=$sumabol03;
        $array_out['sumausd03']=$sumausd03;
        $array_out['sumabol']=$sumabol;
        $array_out['sumausd']=$sumausd;


        //actualizacion de los porcentajes
        if ($flag==1) {
            DB::connection($server)->update("update finanzas.con_tr_detalles 
            set importe_moneda_local=round((importe_moneda_local*$porcentaje_pre_reg*$porcentaje_87),2), 
            importe_moneda_origen =round((importe_moneda_origen*$porcentaje_pre_reg*$porcentaje_87),2)
            where id_transaccion = '$comprobante'
            and id_tipo = '$tipo'
            and cuenta = '$cuenta_pre_reg'");
        }
                

        $sql_max_item=DB::connection($server)->select("select max(item)+1 as max from finanzas.con_tr_detalles 
        where id_transaccion = '$comprobante'
        and id_tipo = '$tipo'
        and cuenta = '$cuenta_pre_reg'");        
        $max_item = $sql_max_item[0]->max;

        $sql_max_reg=DB::connection($server)->select("select max(id_reg)+1 as max_reg from finanzas.con_tr_detalles");
        $max_reg = $sql_max_reg[0]->max_reg;

        if ($flag==1) {

            //insertar registro el 87% a la cuenta de prestamos regualres
            DB::connection($server)->insert("INSERT INTO finanzas.con_tr_detalles
            (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento, importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
            VALUES(
            '$comprobante', 
            '$tipo', 
            '$fecha_transaccion', 
            $max_item, 
            '10001', 
            '$cuenta_aporte', 
            0, 
            '$subcuenta', 
            'Bs.', 
            '$glosa', 
            '',
            $sumabol80, 
            $sumausd80,
            6.96, 
            'an', 
            '$fecha_transaccion', 
            '', 
            null, 
            '12501', 
            $max_reg, 
            'N'::character varying, 
            '')");
            
            //insertar registro el 13% a la cuenta de debito fiscal
            $max_item++;
            $max_reg++;
            DB::connection($server)->insert("INSERT INTO finanzas.con_tr_detalles
            (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento, importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
            VALUES(
            '$comprobante', 
            '$tipo', 
            '$fecha_transaccion', 
            $max_item, 
            '10001', 
            '$cuenta_deb_fis', 
            0, 
            '$subcuenta', 
            'Bs.', 
            '$glosa', 
            '',
            $sumabol13, 
            $sumausd13,
            6.96, 
            'an', 
            '$fecha_transaccion', 
            '', 
            null, 
            '12501', 
            $max_reg, 
            'N'::character varying, 
            '')");

            //insertar registro el 3% a la cuenta de impuestos transacciones haber
            $max_item++;
            $max_reg++;
            DB::connection($server)->insert("INSERT INTO finanzas.con_tr_detalles
            (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento, importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
            VALUES(
            '$comprobante', 
            '$tipo', 
            '$fecha_transaccion', 
            $max_item, 
            '10001', 
            '$cuenta_imp_tra', 
            0, 
            '$subcuenta', 
            'Bs.', 
            '$glosa', 
            '',
            $sumabol03, 
            $sumausd03,
            6.96, 
            'an', 
            '$fecha_transaccion', 
            '', 
            null, 
            '12501', 
            $max_reg, 
            'N'::character varying, 
            '')");

            //insertar registro el 3% a la cuenta de impuestos transacciones debe
            $max_item++;
            $max_reg++;
            $sumabol03 = $sumabol03*-1;
            $sumausd03 = $sumausd03*-1;
            DB::connection($server)->insert("INSERT INTO finanzas.con_tr_detalles
            (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento, importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
            VALUES(
            '$comprobante', 
            '$tipo', 
            '$fecha_transaccion', 
            $max_item, 
            '10001', 
            '$cuenta_imp_tra_debe', 
            0, 
            '$subcuenta', 
            'Bs.', 
            '$glosa', 
            '',
            $sumabol03, 
            $sumausd03,
            6.96, 
            'an', 
            '$fecha_transaccion', 
            '', 
            null, 
            '12501', 
            $max_reg, 
            'N'::character varying, 
            '')");

        }
        
        return ['proceso'=>$array_out,'mensaje'=>'Datos','datos'=>$datos];

    }
}

