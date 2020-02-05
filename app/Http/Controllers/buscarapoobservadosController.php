<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apo_Aporte;
use App\Par_Departamento;
use App\Apo_Observados;
use App\Socio;

class buscarapoobservadosController extends Controller
{
    public function VerificaAportes()
    {
        //ini_set ("memory_limit", " 4096M ");
        //$depa=Par_Departamento::get();
        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 8200);
        $papeletaaportes=Apo_Aporte::select('numpapeleta')->distinct('numpapeleta')->get()->toArray();//->//take(5);
        
        $c=0;
        foreach ($papeletaaportes as $indice => $valor) 
        {
            $c++;
            foreach ($valor as $in=>$v)
            {   $fechamin=Apo_Aporte::where('numpapeleta','=',$v)->select()->min('fechaaporte');
                $fechamax=Apo_Aporte::where('numpapeleta','=',$v)->select()->max('fechaaporte');
                
                //$fecha = "2018-07-30T20:55:20";
                $fechaEnteramin = strtotime($fechamin);
                $aniomin = date("Y", $fechaEnteramin);
                $mesmin = date("m", $fechaEnteramin);
                $fechaEnteramax = strtotime($fechamax);
                $aniomax = date("Y", $fechaEnteramax);
                $mesmax = date("m", $fechaEnteramax);
                //$diamin = date("d", $fechaEnteramin);
                $socio=Socio::where('numpapeleta','=',$v)
                                ->select('activo')->get()->toArray();
                
                if(!empty($socio))
                {
                    if($socio[0]['activo']==1)
                    {
                        $aniomax=2019;
                        $mesmax=4;
                    }
                }
                for($i=$aniomin;$i<=$aniomax;$i++)
                {
                    $j=1;
                    
                    while($j<=12 && ($i<$aniomax || $j<=$mesmax))
                    {
                        
                        
                        $aporte=Apo_Aporte::select('fechaaporte')->where('numpapeleta','=',$v)
                                                                ->whereMonth('fechaaporte','=',$j)
                                                                ->whereYear('fechaaporte','=',$i)
                                                                ->get()->toArray();
                
                        //dd($aporte);
                        //echo"$c -> anio->$i,mes->$j</br>";
                        if(empty($aporte))
                        {
                            $fecha_no=$i.'-'.$j.'-10';
                            //echo "$fecha_no";
                            $observado = new Apo_Observados();
                            $observado->numpapeleta = $v;
                            $observado->fecha_noaporte = $fecha_no;
                            $observado->save();
                            
                            //echo"entra !aporte</br>";
                        }
                        $j=$j+1;
                    }
                }
                
               
                //$diamax = date("d", $fechaEnteramax);
                //echo"v->$v,fechamin->$fechamin,fechamax->$fechamax,aniomin->$aniomin,mesmin->$mesmax,aniomax->$aniomax,mesmax->$mesmax</br>";
                
            }
            echo"$c</br>";
        }
        
        
        /*return[
                'papeletaaporte'=>$papeletaaportes];*/


         
    }
}
