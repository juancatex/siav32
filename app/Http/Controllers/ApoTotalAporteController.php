<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apo_TotalAporte;
use App\Apo_Aporte;
use Illuminate\Support\Facades\DB;
use Datetime;
use App\Apo_Observados;


class ApoTotalAporteController extends Controller
{
    public function index(Request $request) // para agregar la fecha de inicio de aporte y ultimo aporte en obligatorios =0
    {
        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);
        $raw=DB::raw('min(fechaaporte) as fechamin');
        $raw2=DB::raw('max(fechaaporte) as fechamax');
        $fechas_aportes=Apo_Aporte::join('apo__idsaportes','apo__idsaportes.idaporte','=','apo__aportes.idaporte')
                                    ->join('apo__total_aportes','apo__total_aportes.numpapeleta','=','apo__idsaportes.numpapeleta')
                                   ->join('socios','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
                                   ->select('apo__total_aportes.numpapeleta',
                                    $raw,
                                    $raw2,
                                    'socios.fechaegreso')
                                    ->where('tipodevolucion',0)
                                    ->where('obligatorios',0)
                                    ->where('apo__aportes.activo',1)
                                    ->groupBy('apo__aportes.numpapeleta')
                                    ->get()->toArray();

        //$total_aporte=Apo_TotalAporte::limit(10)->get()->toArray();

        //$asignacion=Act_Asignacion::where('idactivo',$request->idactivo)->update(['vigente'=>0]);
        //dd($fechas_aportes);
        foreach ($fechas_aportes as $indice => $valor) {
            $anio_min=date("Y",strtotime($valor['fechamin']));
            $anio_egreso=date("Y",strtotime($valor['fechaegreso']));
            //dd($anio_min."-".$anio_egreso);
            //$fecha=date("d-m-Y",strtotime($valor['fechamin']."+ 1 year"));

            if($anio_min-1<$anio_egreso)
            {
                $actualizacion=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['fecha_primeraporte_obligados'=>$valor['fechaegreso'],
                                                                                                'fecha_ultimoaporte_obligados'=>$valor['fechamax']]);
            }
            else {
                $actualizacion=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['fecha_primeraporte_obligados'=>$valor['fechamin'],
                                                                                                'fecha_ultimoaporte_obligados'=>$valor['fechamax']]);
            }
        }
        echo "finalizado  aporte obligados</br>";
        //return $fechas_aportes;
    }
    public function index2(Request $request) // para agregar la fecha de inicio de aporte y ultimo aporte en obligatorios =1
    {
        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);
        $raw=DB::raw('min(fechaaporte) as fechamin');
        $raw2=DB::raw('max(fechaaporte) as fechamax');
        $fechas_aportes=Apo_Aporte::join('apo__idsaportes','apo__idsaportes.idaporte','=','apo__aportes.idaporte')
                                    ->join('apo__total_aportes','apo__total_aportes.numpapeleta','=','apo__idsaportes.numpapeleta')
                                    ->join('socios','socios.numpapeleta','=','apo__total_aportes.numpapeleta')
                                    ->select('apo__total_aportes.numpapeleta',
                                    $raw,
                                    $raw2,
                                    'socios.fechaegreso')
                                    ->where('tipodevolucion',1)
                                    ->where('obligatorios',1)
                                    ->where('apo__aportes.activo',1)
                                    ->groupBy('apo__aportes.numpapeleta')
                                    ->get()->toArray();

        foreach ($fechas_aportes as $indice => $valor) {
            $anio_min=date("Y",strtotime($valor['fechamin']));
            $anio_egreso=date("Y",strtotime($valor['fechaegreso']));
            if($anio_min-1<$anio_egreso)
            {
                $actualizacion=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['fecha_primeraporte_obligados'=>$valor['fechaegreso'],
                                                                                                'fecha_ultimoaporte_obligados'=>$valor['fechamax']]);
            }
            else {
                $actualizacion=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['fecha_primeraporte_obligados'=>$valor['fechamin'],
                                                                                                'fecha_ultimoaporte_obligados'=>$valor['fechamax']]);
            }
        }
        echo "finalizado  aporte obligados =1</br>";
        //return $fechas_aportes;
    }
    public function index3(Request $request) // metodo para actualizar la fecha de primer y ultimo aporte con total obligados =1 y total obligatorios=0
    {
        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);
        $raw=DB::raw('min(fechaaporte) as fechamin');
        $raw2=DB::raw('max(fechaaporte) as fechamax');
        $fechas_aportes=Apo_Aporte::join('apo__idsaportes','apo__idsaportes.idaporte','=','apo__aportes.idaporte')
                                    ->join('apo__total_aportes','apo__total_aportes.numpapeleta','=','apo__idsaportes.numpapeleta')
                                    /* ->select('apo__aportes.numpapeleta',
                                            $raw) */
                                    ->select('apo__total_aportes.numpapeleta',
                                    $raw,
                                    $raw2)
                                    ->where('tipodevolucion',0)
                                    ->where('obligatorios',1)
                                    ->where('jubilados',0)
                                    ->where('apo__aportes.activo',1)
                                    ->groupBy('apo__aportes.numpapeleta')
                                    ->get()->toArray();

        //$total_aporte=Apo_TotalAporte::limit(10)->get()->toArray();

        //$asignacion=Act_Asignacion::where('idactivo',$request->idactivo)->update(['vigente'=>0]);
        //dd($fechas_aportes);
        foreach ($fechas_aportes as $indice => $valor) {
            
            
           /*  echo $valor['numpapeleta']."</br>";
            echo $valor['fecha']."</br>";
            echo "</br>"; */
            $actualizacion=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['fecha_primeraporte_jubilacion'=>$valor['fechamin'],
                                                                                                'fecha_ultimoaporte_jubilacion'=>$valor['fechamax']]);
        }
        echo "finalizado  aporte jubilados =0</br>";
        //return $fechas_aportes;
    }
    public function index4(Request $request) // metodo para actualizar la fecha de primer y ultimo aporte con total obligados =1 y total obligatorios=1
    {
        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);
        $raw=DB::raw('min(fechaaporte) as fechamin');
        $raw2=DB::raw('max(fechaaporte) as fechamax');
        $fechas_aportes=Apo_Aporte::join('apo__idsaportes','apo__idsaportes.idaporte','=','apo__aportes.idaporte')
                                    ->join('apo__total_aportes','apo__total_aportes.numpapeleta','=','apo__idsaportes.numpapeleta')
                                    /* ->select('apo__aportes.numpapeleta',
                                            $raw) */
                                    ->select('apo__total_aportes.numpapeleta',
                                    $raw,
                                    $raw2)
                                    ->where('tipodevolucion',2)
                                    ->where('obligatorios',1)
                                    ->where('jubilados',1)
                                    ->where('apo__aportes.activo',1)
                                    ->groupBy('apo__aportes.numpapeleta')
                                    ->get()->toArray();

        //$total_aporte=Apo_TotalAporte::limit(10)->get()->toArray();

        //$asignacion=Act_Asignacion::where('idactivo',$request->idactivo)->update(['vigente'=>0]);
        //dd($fechas_aportes);
        foreach ($fechas_aportes as $indice => $valor) {
            
            
           /*  echo $valor['numpapeleta']."</br>";
            echo $valor['fecha']."</br>";
            echo "</br>"; */
            $actualizacion=Apo_TotalAporte::where('numpapeleta',$valor['numpapeleta'])->update(['fecha_primeraporte_jubilacion'=>$valor['fechamin'],
                                                                                                'fecha_ultimoaporte_jubilacion'=>$valor['fechamax']]);
        }
        echo "finalizado  aporte jubilados =1</br>";
        //return $fechas_aportes;
    }
    public function faltantes_obligados(Request $request) // metodo para insertar aportes faltantes en la tabla apo_observados
    {
        ini_set('memory_limit', '4096M');
        ini_set ('max_execution_time', 10200);
        $raw=DB::raw('month(fecha_primeraporte_obligados) as mesmin_obligados');
        $raw2=DB::raw('year(fecha_primeraporte_obligados) as aniomin_obligados');
        $raw3=DB::raw('month(fecha_ultimoaporte_obligados) as mesmax_obligados');
        $raw4=DB::raw('year(fecha_ultimoaporte_obligados) as aniomax_obligados');

        /* $raw3=DB::raw('month(fecha_primeraporte_jubilacion) as mesmin_jubilacion');
        $raw4=DB::raw('year(fecha_ultimoaporte_jubilacion) as aniomin_jubilacion'); */
     /*   
        $idtotalaporte_inicio=1;
        $idtotalaporte_fin=4000;

         $idtotalaporte_inicio=4001;
        $idtotalaporte_fin=8000;

        $idtotalaporte_inicio=8001;
        $idtotalaporte_fin=12000;
*/
        $idtotalaporte_inicio=12001;
        $idtotalaporte_fin=16000;

        $fechas_obligados=Apo_TotalAporte::select('numpapeleta',$raw,$raw2,$raw3,$raw4)
                                            ->where('idtotalaporte','>=',$idtotalaporte_inicio)
                                            ->where('idtotalaporte','<=',$idtotalaporte_fin)
                                            ->whereNotNull('fecha_primeraporte_obligados')
                                            ->get()->toArray();
        
        
        foreach ($fechas_obligados as $indice => $valor) {

            /* echo $valor['aniomin_obligados']."</br>";
            echo $valor['mesmin_obligados']."</br>";
            echo $valor['mesmax_obligados']."</br>";
            echo $valor['aniomax_obligados']."</br>";

 */
            if($valor['aniomin_obligados']==$valor['aniomax_obligados'])
            {
                for ($j=$valor['mesmin_obligados']; $j <= $valor['mesmax_obligados'] ; $j++) { 
                    //echo $j."</br>";
                    $ultimodia= date("d",(mktime(0,0,0,$j+1,1,$valor['mesmax_obligados'])-1));
                    $fecha_noaporte=$valor['aniomax_obligados']."-".$j."-".$ultimodia;
                    $verificar_aporte=Apo_Aporte::select('numpapeleta')
                                                    ->whereMonth('fechaaporte',$j)
                                                    ->whereYear('fechaaporte',$valor['aniomax_obligados'])
                                                    ->where('numpapeleta',$valor['numpapeleta'])->get()->toArray();
                    
                    //dd($verificar_aporte);
                    if(!$verificar_aporte)
                    { 
                        $observado = new Apo_Observados();
                        $observado->numpapeleta = $valor['numpapeleta'];
                        $observado->fecha_noaporte= $fecha_noaporte;
                        $observado->save();  
                    } 
                    echo "$j</br>"; 
                }
            }
            else 
            {
                for ($i=$valor['aniomin_obligados']; $i <= $valor['aniomax_obligados'] ; $i++) { 
                    if($i<>$valor['aniomax_obligados'])
                    {
                        for ($j=$valor['mesmin_obligados']; $j <=12 ; $j++) { 
                            $ultimodia= date("d",(mktime(0,0,0,$j+1,1,$i)-1));
                            $fecha_noaporte=$i."-".$j."-".$ultimodia;
                            $verificar_aporte=Apo_Aporte::select('numpapeleta')
                                                            ->whereMonth('fechaaporte',$j)
                                                            ->whereYear('fechaaporte',$i)
                                                            ->where('numpapeleta',$valor['numpapeleta'])->get()->toArray();
                            
                            //dd($verificar_aporte);
                            if(!$verificar_aporte)
                            { 
                                $observado = new Apo_Observados();
                                $observado->numpapeleta = $valor['numpapeleta'];
                                $observado->fecha_noaporte= $fecha_noaporte;
                                $observado->save();  
                            }  
                        }
                    }
                    else {
                        for ($j=1; $j <= $valor['mesmax_obligados'] ; $j++) { 
                            $ultimodia= date("d",(mktime(0,0,0,$j+1,1,$i)-1));
                            $fecha_noaporte=$valor['aniomax_obligados']."-".$j."-".$ultimodia;
                            $verificar_aporte=Apo_Aporte::select('numpapeleta')
                                                            ->whereMonth('fechaaporte',$j)
                                                            ->whereYear('fechaaporte',$valor['aniomax_obligados'])
                                                            ->where('numpapeleta',$valor['numpapeleta'])->get()->toArray();
                            
                            //dd($verificar_aporte);
                            if(!$verificar_aporte)
                            { 
                                $observado = new Apo_Observados();
                                $observado->numpapeleta = $valor['numpapeleta'];
                                $observado->fecha_noaporte= $fecha_noaporte;
                                $observado->save();  
                            }  
                        }
                    }
                    echo "$i</br>";
                }
            
            }
            
        }
        echo "finalizado busqueda de aportes faltantes";

    }public function faltantes_jubilacion(Request $request) // metodo para insertar aportes faltantes jubilacion en la tabla apo_observados
    {
        ini_set('memory_limit', '4096M');
        ini_set ('max_execution_time', 10200);
        $raw=DB::raw('month(fecha_primeraporte_jubilacion) as mesmin_jubilacion');
        $raw2=DB::raw('year(fecha_primeraporte_jubilacion) as aniomin_jubilacion');
        $raw3=DB::raw('month(fecha_ultimoaporte_jubilacion) as mesmax_jubilacion');
        $raw4=DB::raw('year(fecha_ultimoaporte_jubilacion) as aniomax_jubilacion');

        /* $raw3=DB::raw('month(fecha_primeraporte_jubilacion) as mesmin_jubilacion');
        $raw4=DB::raw('year(fecha_ultimoaporte_jubilacion) as aniomin_jubilacion'); */
        $idtotalaporte_inicio=1;
        $idtotalaporte_fin=8000;

        /*$idtotalaporte_inicio=8001;
        $idtotalaporte_fin=16000;

        /*$idtotalaporte_inicio=4001;
        $idtotalaporte_fin=6000;

        $idtotalaporte_inicio=6001;
        $idtotalaporte_fin=8000;

        $idtotalaporte_inicio=8001;
        $idtotalaporte_fin=10000;

        $idtotalaporte_inicio=10001;
        $idtotalaporte_fin=12000;

        $idtotalaporte_inicio=12001;
        $idtotalaporte_fin=14000;

        $idtotalaporte_inicio=14001;
        $idtotalaporte_fin=16000; */

        $fechas_jubilacion=Apo_TotalAporte::select('numpapeleta',$raw,$raw2,$raw3,$raw4)
                                            ->whereNotNull('apo__total_aportes.fecha_primeraporte_jubilacion')
                                            ->get()->toArray();
        
            //dd($fechas_jubilacion);

        foreach ($fechas_jubilacion as $indice => $valor) {

            /* echo $valor['aniomin_jubilacion']."</br>";
            echo $valor['mesmin_jubilacion']."</br>";
            echo $valor['mesmax_jubilacion']."</br>";
            echo $valor['aniomax_jubilacion']."</br>";


 */
           

            //dd($fechas_jubilacion);
            if($valor['aniomin_jubilacion']==$valor['aniomax_jubilacion'] && $valor['aniomin_jubilacion'] && $valor['aniomax_jubilacion'])
            {
                for ($j=$valor['mesmin_jubilacion']; $j <= $valor['mesmax_jubilacion'] ; $j++) { 
                    //echo $j."</br>";
                    $ultimodia= date("d",(mktime(0,0,0,$j+1,1,$valor['mesmax_jubilacion'])-1));
                    $fecha_noaporte=$valor['aniomax_jubilacion']."-".$j."-".$ultimodia;
                    $verificar_aporte=Apo_Aporte::select('apo__aportes.numpapeleta')
                                                    ->whereMonth('fechaaporte',$j)
                                                    ->whereYear('fechaaporte',$valor['aniomax_jubilacion'])
                                                    ->where('apo__aportes.numpapeleta',$valor['numpapeleta'])
                                                    ->get()->toArray();
                    
                    //dd($verificar_aporte);
                    if(!$verificar_aporte)
                    { 
                        $observado = new Apo_Observados();
                        $observado->numpapeleta = $valor['numpapeleta'];
                        $observado->fecha_noaporte= $fecha_noaporte;
                        $observado->save();  
                    }  
                }
            }
            else 
            {
                if($valor['aniomin_jubilacion'] && $valor['aniomax_jubilacion'])
                {    
                    for ($i=$valor['aniomin_jubilacion']; $i <= $valor['aniomax_jubilacion'] ; $i++) { 
                        if($i<>$valor['aniomax_jubilacion'])
                        {
                            for ($j=$valor['mesmin_jubilacion']; $j <=12 ; $j++) { 
                                $ultimodia= date("d",(mktime(0,0,0,$j+1,1,$i)-1));
                                $fecha_noaporte=$i."-".$j."-".$ultimodia;
                                $verificar_aporte=Apo_Aporte::select('numpapeleta')
                                                                ->whereMonth('fechaaporte',$j)
                                                                ->whereYear('fechaaporte',$i)
                                                                ->where('numpapeleta',$valor['numpapeleta'])
                                                                ->get()->toArray();
                                
                                //dd($verificar_aporte);
                                if(!$verificar_aporte)
                                { 
                                    $observado = new Apo_Observados();
                                    $observado->numpapeleta = $valor['numpapeleta'];
                                    $observado->fecha_noaporte= $fecha_noaporte;
                                    $observado->save();  
                                }  
                            }
                        }
                        else {
                            for ($j=1; $j <= $valor['mesmax_jubilacion'] ; $j++) { 
                                $ultimodia= date("d",(mktime(0,0,0,$j+1,1,$i)-1));
                                $fecha_noaporte=$valor['aniomax_jubilacion']."-".$j."-".$ultimodia;
                                $verificar_aporte=Apo_Aporte::select('numpapeleta')
                                                                ->whereMonth('fechaaporte',$j)
                                                                ->whereYear('fechaaporte',$valor['aniomax_jubilacion'])
                                                                ->where('numpapeleta',$valor['numpapeleta'])
                                                                ->get()->toArray();
                                
                                //dd($verificar_aporte);
                                if(!$verificar_aporte)
                                { 
                                    $observado = new Apo_Observados();
                                    $observado->numpapeleta = $valor['numpapeleta'];
                                    $observado->fecha_noaporte= $fecha_noaporte;
                                    $observado->save();  
                                }  
                            }
                        }
                    }
                }
                echo"no existen datos para actualizar";
            
            }
            
        }
        echo "finalizado busqueda de aportes faltantes idtotalaportes";

    }
    
}
