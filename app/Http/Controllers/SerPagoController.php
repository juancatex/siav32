<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ser_Pago;
use App\Con_Perfilcuentamaestro;
use App\AsinalssClass\AsientoMaestroClass;



class SerPagoController extends Controller
{
    public function listaPagos(Request $request)
    {
        if ($request->tipo=='b') {
            $pagos=Ser_Pago:: select('ser__pagos.*','nombre','apaterno','idestado')
            ->leftjoin('socios','socios.idsocio','=','ser__pagos.idresponsable')
            ->leftjoin('par__prestamos','par__prestamos.no_prestamo','=','ser__pagos.nrdocumento')
            ->where('ser__pagos.idasignacion','=',$request->idasignacion);//mausoleo
            
            // $pagos=Ser_Pago::
            // where('idasignacion',$request->idasignacion);
            // if($request->nit) $pagos=Ser_Pago::select('razon')->where('nit',$request->nit);
            // if($request->periodo) $pagos=Ser_Pago::where('fecha','like',$request->periodo.'%');
            return ['pagos'=>$pagos->get()];
        }
        
        else {
            $pagos=Ser_Pago:: select('ser__pagos.*','nombre','apaterno','idestado')
            ->leftjoin('afi__beneficiarios','afi__beneficiarios.idbeneficiario','=','ser__pagos.idresponsable')
            ->leftjoin('par__prestamos','par__prestamos.no_prestamo','=','ser__pagos.nrdocumento')
            ->where('ser__pagos.idasignacion','=',$request->idasignacion);//mausoleo
            
            // $pagos=Ser_Pago::
            // where('idasignacion',$request->idasignacion);
            // if($request->nit) $pagos=Ser_Pago::select('razon')->where('nit',$request->nit);
            // if($request->periodo) $pagos=Ser_Pago::where('fecha','like',$request->periodo.'%');
            return ['pagos'=>$pagos->get()];
        }
        
    }
    
    public function verPago(Request $request)
    {
        $pago=Ser_Pago:: where('idasignacion',$request->idasignacion);
        return ['pago'=>$pago->get()];
    }

    public function storePago(Request $request)
    {   
        $asientomaestro=new AsientoMaestroClass();
        $idasientomaestro=$asientomaestro->AsientosMaestroDetalle(
            $request->idperfilcuentamaestro,'Factura',
            $request->nrdocumento,
            $request->glosa,
            $request->importe,4,
            $request->fecha,
            $request->idfilial,
            $request->numpapeleta);

        $pago=new Ser_Pago();
        $pago->idasignacion=$request->idasignacion;
        $pago->concepto=$request->concepto;
        $pago->periodo=$request->periodo;
        $pago->nit=$request->nit;
        $pago->razon=$request->razon;
        $pago->modopago=$request->modopago;
        $pago->nrdocumento=$request->nrdocumento;
        $pago->importe=$request->importe;
        $pago->fecha=$request->fecha;
        $pago->idresponsable=$request->idresponsable;
        $pago->idasientomaestro=$idasientomaestro;
        $pago->save();
    }

    public function updatePago(Request $request)
    {   
        $pago=Ser_Pago::findOrFail($request->idpago);
        $pago->concepto=$request->concepto;
        $pago->periodo=$request->periodo;
        $pago->nit=$request->nit;
        $pago->razon=$request->razon;
        $pago->modopago=$request->modopago;        
        $pago->nrdocumento=$request->nrdocumento;
        $pago->idasientomaestro=$request->idasientomaestro;
        $pago->importe=$request->importe;       
        $pago->fecha=$request->fecha;
        $pago->idresponsable=$request->idresponsable;
        $pago->save();
    }

}
