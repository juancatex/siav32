<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rrh_Planilla;
use App\Rrh_Sueldo;

class RrhPlanillaController extends Controller
{
    public function listaPlanillas(Request $request)
    {
        $planillas=Rrh_Planilla::select('periodo')->get();
        return ['planillas'=>$planillas];
    }

    public function storePlanilla(Request $request)
    {
        $planilla=new Rrh_Planilla();
        $planilla->periodo=$request->periodo;
        $planilla->save();
        
        $sql="select idplanilla from rrh__planillas order by idplanilla desc";
        $planilla=DB::select($sql)[0];
        

        //DB::table('rrh__sueldos')->truncate();
        $sql="select rrh__empleados.idempleado,idcargo,floor(datediff('2019-09-30',fechaingreso)/365) as antig,
        salario as basico,30 as diastrab,minutos as minatraso, 
        (select sum(cantidad) from rrh__permisos where rrh__permisos.idempleado=rrh__empleados.idempleado and gocehaberes=0) as singoce
        from rrh__empleados
        join rrh__contratos on rrh__contratos.idempleado=rrh__empleados.idempleado
        join rrh__atrasos on rrh__atrasos.idempleado=rrh__empleados.idempleado
        where rrh__contratos.activo=1 and rrh__atrasos.periodo='2019-09'
        group by rrh__empleados.idempleado";
        $empleado=DB::select($sql);
        for($i=0;$i<count($empleado);$i++)
        {
            $sueldo=new Rrh_Sueldo();
            $sueldo->idplanilla=$planilla->idplanilla;
            $sueldo->idempleado=$empleado[$i]->idempleado;
            $sueldo->basico=$empleado[$i]->basico;
            $sueldo->antig=$empleado[$i]->antig;
            $sueldo->bonoantig=$this->bonoantig($empleado[$i]->antig);
            $sueldo->diastrab=$empleado[$i]->diastrab;
            $sueldo->tganado=$sueldo->basico+$sueldo->bonoantig;
            $sueldo->afp=$sueldo->tganado*0.1271;
            $sueldo->atrasos=$this->atrasos($empleado[$i]->minatraso,$empleado[$i]->basico);
            $sueldo->singoce=round($empleado[$i]->singoce*($sueldo->basico/240),2);
            $sueldo->tdescuento=$sueldo->afp+$sueldo->atrasos+$sueldo->singoce;
            $sueldo->liquido=$sueldo->tganado-$sueldo->tdescuento;
            $sueldo->save();
        }

    }

    function bonoantig($antig)
    {
        if($antig<2)  $porcen=0;
        else if($antig<=5)  $porcen=0.05;
        else if($antig<=8)  $porcen=0.11;
        else if($antig<=11) $porcen=0.18;
        else if($antig<=15) $porcen=0.26;
        else if($antig<=20) $porcen=0.34;
        else if($antig<=25) $porcen=0.42;
        else $porcen=0.5;
        return round(2122*3*$porcen,2);
    }

    function atrasos($minatraso,$basico)
    {
        $horatrabajo=$basico/240;
        if($minatraso<30) $t=0;
        else if($minatraso<45) $t=$horatrabajo*4;
        else if($minatraso<60) $t=$horatrabajo*8;
        else if($minatraso<90) $t=$horatrabajo*16;
        else $t=$horatrabajo*24;
        return round($t,2);
    }

    public function storeSueldo()
    {

        //print_r($empleado) ;

/*
        $sueldo=new Rrh_Sueldo();
        $sueldo->idplanilla=$empleado->idplanilla;
        $sueldo->idempleado=$empleado->idempleado;
        $sueldo->idcargo=$empleado->idcargo;
        $sueldo->basico=$empleado->basico;
        */
    }


}
