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
  
        $cuentas=DB::connection($valuedb)->select("SELECT cuenta, descripcion
        FROM finanzas.con_plan_cuentas");

        $fechaR=DB::connection($valuedb)->select("SELECT fecha_transaccion as fe,par_automatico as tip
        FROM finanzas.con_tr_maestro where id_transaccion='$numcomprobante' and id_tipo ='$valuetipo'");

        if(count($fechaR)>0){
            if($fechaR[0]->tip=='S'){
                // $valida_1=DB::connection($valuedb)->select("select det.analisis_auxiliar,det.id_reg ,cu.descripcion,det.cuenta,det.id_sub_cuenta, det.importe_moneda_local,det.tipo_cambio,p.nombrecompleto, g.abrev 
                // from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
                //         where  det.cuenta =cu.cuenta
                //         and p.par_profesion=g.cod
                //         and det.analisis_auxiliar =cu.analisis_auxiliar
                //         and det.id_sub_cuenta=p.numero_papeleta
                // and det.id_transaccion ='$numcomprobante' 
                // and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
                
                $valida_1=DB::connection($valuedb)->select("select det.analisis_auxiliar,det.id_reg ,cu.descripcion,det.cuenta,det.id_sub_cuenta, det.importe_moneda_local,det.tipo_cambio,p.nombrecompleto , '' as abrev
                from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu,finanzas.con_personas p
                        where  det.cuenta =cu.cuenta 
                        and det.analisis_auxiliar =cu.analisis_auxiliar
                        and det.id_sub_cuenta=p.id_persona
                and det.id_transaccion ='$numcomprobante' 
                and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");

               }else{
                $valida_1=DB::connection($valuedb)->select("select det.*,cu.descripcion,'' as nombrecompleto,'' as abrev 
                from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu 
                   where  det.cuenta =cu.cuenta 
                      and det.analisis_auxiliar =cu.analisis_auxiliar
                and det.id_transaccion ='$numcomprobante' 
                and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
               }
        }else{
            $valida_1=[];
        }

        

        return ['values'=>$valida_1,'cuentas'=>$cuentas,'fecha'=>count($fechaR)>0?$fechaR[0]->fe:date("Y-m-d")];
    }
    public function procesoReservaUpdate(Request $request)
    {
       if (!$request->ajax()) return redirect('/');   

        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);

        $valuedb=$request->valuedb;
        $valuetipo=$request->valuetipo;
        $numcomprobante=$request->numcomprobante;
 
        DB::beginTransaction();
        $valida_1=DB::connection($valuedb)->select("select det.* from finanzas.con_tr_detalles  det 
        where   det.id_transaccion ='$numcomprobante' 
        and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
  
       
 
        $sql_max_reg=DB::connection($valuedb)->select("select max(id_reg) as max_reg from finanzas.con_tr_detalles");
        $max_reg = $sql_max_reg[0]->max_reg;

        foreach($valida_1 as $linea){
                if($linea->cuenta=='41101101'||$linea->cuenta=='41101102'||$linea->cuenta=='41101103'){
                    $max_reg++; 
                    $sql_max_item=DB::connection($valuedb)->select("select max(item)+1 as max from finanzas.con_tr_detalles 
                    where  id_transaccion ='$numcomprobante'
                    and id_tipo = '$valuetipo'");

                    $max_item = $sql_max_item[0]->max; 
                    // para moneda local
                    //11
                    //89
                    $treinta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)*0.11,2);
                    $setenta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)-$treinta_por,2); 
                    $setenta_por=$linea->importe_moneda_local>0?$setenta_por:$setenta_por*-1;
                    $treinta_por=$linea->importe_moneda_local>0?$treinta_por:$treinta_por*-1;
                    $cien_por=(round(($setenta_por+$treinta_por),2));
 
                    $trece_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.13,2); 
                    $ochenta_siete_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)-$trece_por,2); 
                    $ochenta_siete_por=$treinta_por>0?$ochenta_siete_por:$ochenta_siete_por*-1;
                    $trece_por=$treinta_por>0?$trece_por:$trece_por*-1;
                    $cien_por_treinta=(round(($ochenta_siete_por+$trece_por),2));

                    

                    // para moneda origen
                    //11
                    //89
                    $treinta_por2=round(($linea->importe_moneda_origen>0?$linea->importe_moneda_origen:$linea->importe_moneda_origen*-1)*0.11,2);
                    $setenta_por2=round(($linea->importe_moneda_origen>0?$linea->importe_moneda_origen:$linea->importe_moneda_origen*-1)-$treinta_por2,2);
                    $setenta_por2=$linea->importe_moneda_origen>0?$setenta_por2:$setenta_por2*-1;
                    $treinta_por2=$linea->importe_moneda_origen>0?$treinta_por2:$treinta_por2*-1;
                    $cien_por2=(round(($setenta_por2+$treinta_por2),2));

                    $trece_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.13,2);
                    $ochenta_siete_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)-$trece_por2,2); 
                    $ochenta_siete_por2=$treinta_por2>0?$ochenta_siete_por2:$ochenta_siete_por2*-1;
                    $trece_por2=$treinta_por2>0?$trece_por2:$trece_por2*-1;
                    $cien_por_treinta2=(round(($ochenta_siete_por2+$trece_por2),2));




                    $final_validate=(round(($setenta_por+$ochenta_siete_por+$trece_por),2));
                    $final_validate2=(round(($setenta_por2+$ochenta_siete_por2+$trece_por2),2));

                    // echo 'local: '.$linea->importe_moneda_local.' = '. $cien_por.'    = '.$final_validate.'<br>';
                    // echo 'origen: '.$linea->importe_moneda_origen.' = '. $cien_por2.'    = '.$final_validate2.'<br>';
 

                    $tres_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.03,2);
                    $tres_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.03,2);
                    $tres_porA=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.03,2)*-1;
                    $tres_por2A=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.03,2)*-1;
                     
 
                   
                   if(($linea->importe_moneda_local==$cien_por
                   &&$linea->importe_moneda_origen==$cien_por2)
                   &&($linea->importe_moneda_local==$final_validate
                   &&$linea->importe_moneda_origen==$final_validate2)
                   &&($treinta_por==$cien_por_treinta
                   &&$treinta_por2==$cien_por_treinta2)){
                    //actualiza con el 87% del 30%
                    DB::connection($valuedb)->update("update finanzas.con_tr_detalles
                    set importe_moneda_local=$ochenta_siete_por, 
                    importe_moneda_origen =$ochenta_siete_por2
                    where id_transaccion = '$linea->id_transaccion'
                    and id_tipo = '$linea->id_tipo'
                    and item = '$linea->item'
                    and cuenta = '$linea->cuenta'");

                    //registro de la reserva cuenta:22501101
                            DB::connection($valuedb)->insert("INSERT INTO finanzas.con_tr_detalles
                            (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                                importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                            VALUES(
                            '$numcomprobante', 
                            '$valuetipo', 
                            '$linea->fecha_transaccion', 
                            $max_item, 
                            '10001', 
                            '22501101', 
                            0, 
                            '$linea->id_sub_cuenta',  
                            'Bs.', 
                            '$linea->glosa', 
                            '',
                            $setenta_por, 
                            $setenta_por2,
                            '$linea->tipo_cambio',  
                            'an', 
                            '$linea->fecha_transaccion', 
                            '', 
                            null, 
                            '12501', 
                            $max_reg, 
                            'N'::character varying, 
                            '')");

$max_reg++;
$max_item++;

                    //registro debito fiscal de la cuenta:21301102
                    DB::connection($valuedb)->insert("INSERT INTO finanzas.con_tr_detalles
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '21301102', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $trece_por, 
                    $trece_por2,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')");
 
$max_reg++;
$max_item++;
    
                    //registro impuesto a las transacciones de la cuenta:51301106
                    DB::connection($valuedb)->insert("INSERT INTO finanzas.con_tr_detalles
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '51301106', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $tres_por, 
                    $tres_por2,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')");
                  
$max_reg++;
$max_item++;

                    //registro impuesto a las transacciones por pagar de la cuenta:21301103
                    DB::connection($valuedb)->insert("INSERT INTO finanzas.con_tr_detalles
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '21301103', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $tres_porA, 
                    $tres_por2A,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')");

                    }else{
                         DB::rollback();
                         return ['values'=>[],'mensaje'=>'No coenciden los datos 70 - 30 %   item_:'.$linea->item.' 70:'.$setenta_por.'  30:'.$treinta_por];
                    }
                    
                }
        }  
        DB::commit();

      

        $fechaR=DB::connection($valuedb)->select("SELECT par_automatico as tip
        FROM finanzas.con_tr_maestro where id_transaccion='$numcomprobante' and id_tipo ='$valuetipo'");

            if($fechaR[0]->tip=='S'){
                $valida_3=DB::connection($valuedb)->select("select det.*,cu.descripcion,p.nombrecompleto, g.abrev 
                from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
                        where  det.cuenta =cu.cuenta
                        and p.par_profesion=g.cod
                        and det.analisis_auxiliar =cu.analisis_auxiliar
                        and det.id_sub_cuenta=p.numero_papeleta
                        and det.id_transaccion ='$numcomprobante' 
                        and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
            }else{
                $valida_3=DB::connection($valuedb)->select("select det.*,cu.descripcion,'' as nombrecompleto,'' as abrev 
                from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu 
                    where  det.cuenta =cu.cuenta 
                    and det.analisis_auxiliar =cu.analisis_auxiliar
                    and det.id_transaccion ='$numcomprobante' 
                    and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
            }



        
 return ['values'=>$valida_3];
    }
    public function procesoReserva(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');   

        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);

        $valuedb=$request->valuedb;
        $valuetipo=$request->valuetipo;
        $numcomprobante=$request->numcomprobante;

        DB::connection($valuedb)->statement( "CREATE TEMP TABLE  tmp_contable_detalle AS (
            select det.*
           from finanzas.con_tr_detalles  det 
           where 
           det.id_transaccion ='$numcomprobante'   
           and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta )"); 

        $valida_1=DB::connection($valuedb)->select("select det.* from tmp_contable_detalle  det 
        where   det.id_transaccion ='$numcomprobante' 
        and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
 
        // $valida_1=DB::connection($valuedb)->select("select det.analisis_auxiliar,det.id_reg ,cu.descripcion,det.cuenta,det.id_sub_cuenta, det.importe_moneda_local,det.tipo_cambio,p.nombrecompleto, g.abrev 
        // from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
        //         where  det.cuenta =cu.cuenta
        //         and p.par_profesion=g.cod
        //         and det.id_sub_cuenta=p.numero_papeleta
        // and det.id_transaccion ='$numcomprobante' 
        // and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
        $cuentas=DB::connection($valuedb)->select("SELECT cuenta, descripcion
        FROM finanzas.con_plan_cuentas");
 
        $sql_max_reg=DB::connection($valuedb)->select("select max(id_reg) as max_reg from tmp_contable_detalle");
        $max_reg = $sql_max_reg[0]->max_reg;

        foreach($valida_1 as $linea){
                if($linea->cuenta=='41101101'||$linea->cuenta=='41101102'||$linea->cuenta=='41101103'){
                    $max_reg++;

                    $sql_max_item=DB::connection($valuedb)->select("select max(item)+1 as max from tmp_contable_detalle 
                    where  id_transaccion ='$numcomprobante'
                    and id_tipo = '$valuetipo'");

                    $max_item = $sql_max_item[0]->max;
                     
                    
                    // para moneda local
                    $treinta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)*0.11,2);
                    $setenta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)-$treinta_por,2); 
                    $setenta_por=$linea->importe_moneda_local>0?$setenta_por:$setenta_por*-1;
                    $treinta_por=$linea->importe_moneda_local>0?$treinta_por:$treinta_por*-1;
                    $cien_por=(round(($setenta_por+$treinta_por),2));
 
                    $trece_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.13,2); 
                    $ochenta_siete_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)-$trece_por,2); 
                    $ochenta_siete_por=$treinta_por>0?$ochenta_siete_por:$ochenta_siete_por*-1;
                    $trece_por=$treinta_por>0?$trece_por:$trece_por*-1;
                    $cien_por_treinta=(round(($ochenta_siete_por+$trece_por),2));

                    

                    // para moneda origen
                    $treinta_por2=round(($linea->importe_moneda_origen>0?$linea->importe_moneda_origen:$linea->importe_moneda_origen*-1)*0.11,2);
                    $setenta_por2=round(($linea->importe_moneda_origen>0?$linea->importe_moneda_origen:$linea->importe_moneda_origen*-1)-$treinta_por2,2);
                    $setenta_por2=$linea->importe_moneda_origen>0?$setenta_por2:$setenta_por2*-1;
                    $treinta_por2=$linea->importe_moneda_origen>0?$treinta_por2:$treinta_por2*-1;
                    $cien_por2=(round(($setenta_por2+$treinta_por2),2));

                    $trece_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.13,2);
                    $ochenta_siete_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)-$trece_por2,2); 
                    $ochenta_siete_por2=$treinta_por2>0?$ochenta_siete_por2:$ochenta_siete_por2*-1;
                    $trece_por2=$treinta_por2>0?$trece_por2:$trece_por2*-1;
                    $cien_por_treinta2=(round(($ochenta_siete_por2+$trece_por2),2));




                    $final_validate=(round(($setenta_por+$ochenta_siete_por+$trece_por),2));
                    $final_validate2=(round(($setenta_por2+$ochenta_siete_por2+$trece_por2),2));

                    // echo 'local: '.$linea->importe_moneda_local.' = '. $cien_por.'    = '.$final_validate.'<br>';
                    // echo 'origen: '.$linea->importe_moneda_origen.' = '. $cien_por2.'    = '.$final_validate2.'<br>';
  
                    $tres_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.03,2);
                    $tres_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.03,2);
                    $tres_porA=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.03,2)*-1;
                    $tres_por2A=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.03,2)*-1;
                      
                   if(($linea->importe_moneda_local==$cien_por
                   &&$linea->importe_moneda_origen==$cien_por2)
                   &&($linea->importe_moneda_local==$final_validate
                   &&$linea->importe_moneda_origen==$final_validate2)
                   &&($treinta_por==$cien_por_treinta
                   &&$treinta_por2==$cien_por_treinta2)){
                    //actualiza con el 87% del 30%
                    DB::connection($valuedb)->update("update tmp_contable_detalle
                    set importe_moneda_local=$ochenta_siete_por, 
                    importe_moneda_origen =$ochenta_siete_por2
                    where id_transaccion = '$linea->id_transaccion'
                    and id_tipo = '$linea->id_tipo'
                    and item = '$linea->item'
                    and cuenta = '$linea->cuenta'");

                    //registro de la reserva cuenta:22501101
                            DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                            (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                                importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                            VALUES(
                            '$numcomprobante', 
                            '$valuetipo', 
                            '$linea->fecha_transaccion', 
                            $max_item, 
                            '10001', 
                            '22501101', 
                            0, 
                            '$linea->id_sub_cuenta',  
                            'Bs.', 
                            '$linea->glosa', 
                            '',
                            $setenta_por, 
                            $setenta_por2,
                            '$linea->tipo_cambio',  
                            'an', 
                            '$linea->fecha_transaccion', 
                            '', 
                            null, 
                            '12501', 
                            $max_reg, 
                            'N'::character varying, 
                            '')");

$max_reg++;
$max_item++;

                    //registro debito fiscal de la cuenta:21301102
                    DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '21301102', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $trece_por, 
                    $trece_por2,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')");
 
$max_reg++;
$max_item++;
                       
                    //registro impuesto a las transacciones de la cuenta:51301106
                    DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '51301106', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $tres_por, 
                    $tres_por2,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')"); 
$max_reg++;
$max_item++;

                    //registro impuesto a las transacciones por pagar de la cuenta:21301103
                    DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '21301103', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $tres_porA, 
                    $tres_por2A,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')");

                    }else{
                        DB::connection($valuedb)->statement( "DROP TABLE tmp_contable_detalle");
                        return ['values'=>[],'cuentas'=>$cuentas,'mensaje'=>'No coenciden los datos 70 - 30 %   item_:'.$linea->item.' 70:'.$setenta_por.'  30:'.$treinta_por];
                    }
                    
                }
        } 
 
       

        $fechaR=DB::connection($valuedb)->select("SELECT par_automatico as tip
        FROM finanzas.con_tr_maestro where id_transaccion='$numcomprobante' and id_tipo ='$valuetipo'");

            if($fechaR[0]->tip=='S'){
                $valida_3=DB::connection($valuedb)->select("select det.*,cu.descripcion,p.nombrecompleto, g.abrev 
                from tmp_contable_detalle  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
                        where  det.cuenta =cu.cuenta
                        and p.par_profesion=g.cod
                        and det.analisis_auxiliar =cu.analisis_auxiliar
                        and det.id_sub_cuenta=p.numero_papeleta
                and det.id_transaccion ='$numcomprobante' 
                and det.id_tipo ='$valuetipo' order by det.cuenta, det.id_sub_cuenta");
            }else{
                $valida_3=DB::connection($valuedb)->select("select det.*,cu.descripcion,'' as nombrecompleto,'' as abrev 
                from tmp_contable_detalle  det,finanzas.con_plan_cuentas cu 
                    where  det.cuenta =cu.cuenta 
                    and det.analisis_auxiliar =cu.analisis_auxiliar
                    and det.id_transaccion ='$numcomprobante' 
                    and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
            }





 
  DB::connection($valuedb)->statement( "DROP TABLE tmp_contable_detalle");
 
 return ['values'=>$valida_3,'cuentas'=>$cuentas];
    }
    public function procesoReservaReversion(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');   

        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);

        $valuedb=$request->valuedb;
        $valuetipo=$request->valuetipo;
        $numcomprobante=$request->numcomprobante;

        DB::connection($valuedb)->statement( "CREATE TEMP TABLE  tmp_contable_detalle AS (
            select det.*
           from finanzas.con_tr_detalles  det 
           where 
           det.id_transaccion ='$numcomprobante'   
           and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta )"); 

        $valida_1=DB::connection($valuedb)->select("select det.* from tmp_contable_detalle  det 
        where   det.id_transaccion ='$numcomprobante' 
        and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
 
        // $valida_1=DB::connection($valuedb)->select("select det.analisis_auxiliar,det.id_reg ,cu.descripcion,det.cuenta,det.id_sub_cuenta, det.importe_moneda_local,det.tipo_cambio,p.nombrecompleto, g.abrev 
        // from finanzas.con_tr_detalles  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
        //         where  det.cuenta =cu.cuenta
        //         and p.par_profesion=g.cod
        //         and det.id_sub_cuenta=p.numero_papeleta
        // and det.id_transaccion ='$numcomprobante' 
        // and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
        $cuentas=DB::connection($valuedb)->select("SELECT cuenta, descripcion
        FROM finanzas.con_plan_cuentas");
 
        $sql_max_reg=DB::connection($valuedb)->select("select max(id_reg) as max_reg from tmp_contable_detalle");
        $max_reg = $sql_max_reg[0]->max_reg;

        foreach($valida_1 as $linea){
                if($linea->cuenta=='41101101'||$linea->cuenta=='41101102'||$linea->cuenta=='41101103'){
                    $max_reg++;

                    $sql_max_item=DB::connection($valuedb)->select("select max(item)+1 as max from tmp_contable_detalle 
                    where  id_transaccion ='$numcomprobante'
                    and id_tipo = '$valuetipo'");

                    $max_item = $sql_max_item[0]->max;
                     
                    
                    // para moneda local
                    $treinta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)*0.11,2);
                    $setenta_por=round(($linea->importe_moneda_local>0?$linea->importe_moneda_local:$linea->importe_moneda_local*-1)-$treinta_por,2); 
                    $setenta_por=$linea->importe_moneda_local>0?$setenta_por:$setenta_por*-1;
                    $treinta_por=$linea->importe_moneda_local>0?$treinta_por:$treinta_por*-1;
                    $cien_por=(round(($setenta_por+$treinta_por),2));
 
                    $trece_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.13,2); 
                    $ochenta_siete_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)-$trece_por,2); 
                    $ochenta_siete_por=$treinta_por>0?$ochenta_siete_por:$ochenta_siete_por*-1;
                    $trece_por=$treinta_por>0?$trece_por:$trece_por*-1;
                    $cien_por_treinta=(round(($ochenta_siete_por+$trece_por),2));

                    

                    // para moneda origen
                    $treinta_por2=round(($linea->importe_moneda_origen>0?$linea->importe_moneda_origen:$linea->importe_moneda_origen*-1)*0.11,2);
                    $setenta_por2=round(($linea->importe_moneda_origen>0?$linea->importe_moneda_origen:$linea->importe_moneda_origen*-1)-$treinta_por2,2);
                    $setenta_por2=$linea->importe_moneda_origen>0?$setenta_por2:$setenta_por2*-1;
                    $treinta_por2=$linea->importe_moneda_origen>0?$treinta_por2:$treinta_por2*-1;
                    $cien_por2=(round(($setenta_por2+$treinta_por2),2));

                    $trece_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.13,2);
                    $ochenta_siete_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)-$trece_por2,2); 
                    $ochenta_siete_por2=$treinta_por2>0?$ochenta_siete_por2:$ochenta_siete_por2*-1;
                    $trece_por2=$treinta_por2>0?$trece_por2:$trece_por2*-1;
                    $cien_por_treinta2=(round(($ochenta_siete_por2+$trece_por2),2));




                    $final_validate=(round(($setenta_por+$ochenta_siete_por+$trece_por),2));
                    $final_validate2=(round(($setenta_por2+$ochenta_siete_por2+$trece_por2),2));

                    // echo 'local: '.$linea->importe_moneda_local.' = '. $cien_por.'    = '.$final_validate.'<br>';
                    // echo 'origen: '.$linea->importe_moneda_origen.' = '. $cien_por2.'    = '.$final_validate2.'<br>';
  
                    $tres_por=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.03,2)*-1;
                    $tres_por2=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.03,2)*-1;
                    $tres_porA=round(($treinta_por>0?$treinta_por:$treinta_por*-1)*0.03,2);
                    $tres_por2A=round(($treinta_por2>0?$treinta_por2:$treinta_por2*-1)*0.03,2);
                      
                   if(($linea->importe_moneda_local==$cien_por
                   &&$linea->importe_moneda_origen==$cien_por2)
                   &&($linea->importe_moneda_local==$final_validate
                   &&$linea->importe_moneda_origen==$final_validate2)
                   &&($treinta_por==$cien_por_treinta
                   &&$treinta_por2==$cien_por_treinta2)){
                    //actualiza con el 87% del 30%
                    DB::connection($valuedb)->update("update tmp_contable_detalle
                    set importe_moneda_local=$ochenta_siete_por, 
                    importe_moneda_origen =$ochenta_siete_por2
                    where id_transaccion = '$linea->id_transaccion'
                    and id_tipo = '$linea->id_tipo'
                    and item = '$linea->item'
                    and cuenta = '$linea->cuenta'");

                    //registro de la reserva cuenta:22501101
                            DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                            (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                                importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                            VALUES(
                            '$numcomprobante', 
                            '$valuetipo', 
                            '$linea->fecha_transaccion', 
                            $max_item, 
                            '10001', 
                            '22501101', 
                            0, 
                            '$linea->id_sub_cuenta',  
                            'Bs.', 
                            '$linea->glosa', 
                            '',
                            $setenta_por, 
                            $setenta_por2,
                            '$linea->tipo_cambio',  
                            'an', 
                            '$linea->fecha_transaccion', 
                            '', 
                            null, 
                            '12501', 
                            $max_reg, 
                            'N'::character varying, 
                            '')");

$max_reg++;
$max_item++;

                    //registro debito fiscal de la cuenta:21301102
                    DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '21301102', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $trece_por, 
                    $trece_por2,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')");
 
$max_reg++;
$max_item++;
                       
                    //registro impuesto a las transacciones de la cuenta:51301106
                    DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '51301106', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $tres_por, 
                    $tres_por2,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')"); 
$max_reg++;
$max_item++;

                    //registro impuesto a las transacciones por pagar de la cuenta:21301103
                    DB::connection($valuedb)->insert("INSERT INTO tmp_contable_detalle
                    (id_transaccion, id_tipo, fecha_transaccion, item, par_area_funcional, cuenta, analisis_auxiliar, id_sub_cuenta, par_moneda, glosa, documento,
                        importe_moneda_local, importe_moneda_origen, tipo_cambio, usuario_reg, fecha_reg, usuario_mod, fecha_mod, id_oficina, id_reg, ajuste, cuenta_ant)
                    VALUES(
                    '$numcomprobante', 
                    '$valuetipo', 
                    '$linea->fecha_transaccion', 
                    $max_item, 
                    '10001', 
                    '21301103', 
                    0, 
                    '$linea->id_sub_cuenta',  
                    'Bs.', 
                    '$linea->glosa', 
                    '',
                    $tres_porA, 
                    $tres_por2A,
                    '$linea->tipo_cambio',  
                    'an', 
                    '$linea->fecha_transaccion', 
                    '', 
                    null, 
                    '12501', 
                    $max_reg, 
                    'N'::character varying, 
                    '')");

                    }else{
                        DB::connection($valuedb)->statement( "DROP TABLE tmp_contable_detalle");
                        return ['values'=>[],'cuentas'=>$cuentas,'mensaje'=>'No coenciden los datos 70 - 30 %   item_:'.$linea->item.' 70:'.$setenta_por.'  30:'.$treinta_por];
                    }
                    
                }
        } 
 
       

        $fechaR=DB::connection($valuedb)->select("SELECT par_automatico as tip
        FROM finanzas.con_tr_maestro where id_transaccion='$numcomprobante' and id_tipo ='$valuetipo'");

            if($fechaR[0]->tip=='S'){
                $valida_3=DB::connection($valuedb)->select("select det.*,cu.descripcion,p.nombrecompleto, g.abrev 
                from tmp_contable_detalle  det,finanzas.con_plan_cuentas cu,global.gbpersona p,finanzas.apsa_grados g
                        where  det.cuenta =cu.cuenta
                        and p.par_profesion=g.cod
                        and det.analisis_auxiliar =cu.analisis_auxiliar
                        and det.id_sub_cuenta=p.numero_papeleta
                and det.id_transaccion ='$numcomprobante' 
                and det.id_tipo ='$valuetipo' order by det.cuenta, det.id_sub_cuenta");
            }else{
                $valida_3=DB::connection($valuedb)->select("select det.*,cu.descripcion,'' as nombrecompleto,'' as abrev 
                from tmp_contable_detalle  det,finanzas.con_plan_cuentas cu 
                    where  det.cuenta =cu.cuenta 
                    and det.analisis_auxiliar =cu.analisis_auxiliar
                    and det.id_transaccion ='$numcomprobante' 
                    and det.id_tipo ='$valuetipo' order by det.cuenta,det.id_sub_cuenta");
            }





 
  DB::connection($valuedb)->statement( "DROP TABLE tmp_contable_detalle");
 
 return ['values'=>$valida_3,'cuentas'=>$cuentas];
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
        cuenta = '$cuentaB' 
        where  id_transaccion ='$idtransaccion' and 
        id_tipo ='$tipo' and 
        cuenta = '$cuentaA'");
  
        return ['data'=>$valida_1];
    }
    public function updateDateCuentaComprobante(Request $request)
    {
        if (!$request->ajax()) return redirect('/');   

        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);
        $fecha=$request->fecha;
        $fechaantes=$request->fechaantes;
        $idtransaccion=$request->idtransaccion;
        $tipo=$request->tipo;
        $valuedb=$request->valuedb;
        $directory='LogSafcon';
        Storage::makeDirectory($directory); 
        Storage::append($directory.'/logDate.txt','Date:'.date("Y_m_d H:i:s").'  DB:'.$valuedb.'     Comprobante:'.$idtransaccion.'     Fecha antes:'. $fechaantes.'     Fecha new:'.$fecha.'         Tipo:'.$tipo.'  user:'.Auth::user()->username.'             id:'.Auth::id());
                        
        DB::connection($valuedb)->statement("ALTER TABLE finanzas.con_tr_maestro DISABLE TRIGGER am_fecha_con_maestro_tr");
            $valida_1=DB::connection($valuedb)->update("update finanzas.con_tr_maestro set 
            fecha_transaccion = '$fecha', 
            fecha_reg =  TO_TIMESTAMP(CONCAT('$fecha',' ',to_char(fecha_reg, 'HH24:MI:SS')), 'YYYY-MM-DD HH24:MI:SS') ,
            fecha_mod =  TO_TIMESTAMP(CONCAT('$fecha',' ',to_char(fecha_reg, 'HH24:MI:SS')), 'YYYY-MM-DD HH24:MI:SS') 
            where  id_transaccion ='$idtransaccion' and 
            id_tipo ='$tipo'");
        DB::connection($valuedb)->statement("ALTER TABLE finanzas.con_tr_maestro ENABLE TRIGGER am_fecha_con_maestro_tr");

        DB::connection($valuedb)->statement("ALTER TABLE finanzas.con_tr_detalles DISABLE TRIGGER am_fecha_con_detalles_tr");
            $valida_1=DB::connection($valuedb)->update("update finanzas.con_tr_detalles set 
            fecha_transaccion = '$fecha',
            fecha_reg =  TO_TIMESTAMP(CONCAT('$fecha',' ',to_char(fecha_reg, 'HH24:MI:SS')), 'YYYY-MM-DD HH24:MI:SS') ,
            fecha_mod =  TO_TIMESTAMP(CONCAT('$fecha',' ',to_char(fecha_reg, 'HH24:MI:SS')), 'YYYY-MM-DD HH24:MI:SS') 
            where  id_transaccion ='$idtransaccion' and 
            id_tipo ='$tipo'");
        DB::connection($valuedb)->statement("ALTER TABLE finanzas.con_tr_detalles ENABLE TRIGGER am_fecha_con_detalles_tr");
   
    }
    public function pruebaget(Request $request)
    {
        
        
$aray=['11101105',
'11101109',
'11101210',
'11201112',
'11201113',
'11201126',
'11201132',
'11201136',
'11601104',
'11701101',
'11701102',
'11701103',
'11701104',
'11701105',
'11701106',
'11701110',
'11701118',
'11701119',
'11801104',
'11801106',
'12141103',
'12141109',
'12201103',
'12201105',
'12201109',
'12201111',
'12201121',
'21101111',
'21201105',
'21201110',
'21301102',
'21301106',
'21301107',
'21301110',
'21401105',
'21401111',
'21401112',
'21401113',
'21401122',
'21401125',
'21601104',
'21601105',
'22101101',
'22101102',
'22101103',
'22201102',
'22501101',
'22501102',
'21301104',
'11201103',
'11201115',
'12141106',
'12141107',
'21201102',
'21601106',
'21601103',
'21401103'];

$aray2=[
    '11201113',
    '11601104',
    '11801106',
    '12201103',
   '12201105',
    '12201109',
    '12201111',
    '12201121',
    '21301104',
    '31201105',
    '62100102',
    '71101101'];
    $aray3=[
    '11201112',
    '11201134',
    '11201135',
    '11201136',
    '11301101',
    '11301102',
    '11320102',
    '11501101',
    '11601104',
    '11801104',
    '11801109',
    '11801110',
    '12111103',
    '12141103',
    '12141109',
    '12141110',
    '12142103',
    '12201101',
    '12201102',
    '12201103',
    '12201104',
    '12201105',
    '12201106',
    '12201109',
    '12201110',
    '12201111',
    '12201112',
    '12201117',
    '12201118',
    '12201121',
    '12201122',
    '12301101',
    '21101101',
    '21301102',
    '21301103',
    '21301104',
    '21301106',
    '21301107',
    '21301110',
    '21401111',
    '21401112',
    '21401113',
    '21401121',
    '21401122',
    '21401126',
    '21601104',
    '22501101',
    '31101102',
    '31201104',
    '31201105',
    '31201107',
    '62100102',
    '71101101',
    '71101110',
    '61101106'];
foreach($aray3 as $cuenta){
   
    echo $cuenta.'|'. (DB::connection('pgsql2020')->select("select descripcion from finanzas.con_plan_cuentas where cuenta ='$cuenta'"))[0]->descripcion.'<br>';
} 


          
      
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
        $porcentaje_pre_reg = 0.20; //11
        $porcentaje_aportes = 0.80; //89
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

