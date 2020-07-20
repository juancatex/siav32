<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Maatwebsite\Excel\Facades\Excel; //laravel-excel.com 2.1
use App\Http\Controllers\Controller;
use App\Act_Ufv;

class ActUfvController extends Controller
{
    public function verUfv(Request $request)
    {
        if($request->fecha) $fecha=$request->fecha; 
        else $fecha=date("Y-m-d");
        $ufvfecha=Act_Ufv::select('valor')->where('fecha',$fecha)->get(); 
        

        if (count($ufvfecha)==0)    
            return ['ufvfecha'=>0,'fecha'=>$fecha];
        else             
            return ['ufvfecha'=>$ufvfecha[0],'fecha'=>$fecha];
    }

    public function maxverUfv(Request $request)
    {
        $maxufvfecha=Act_Ufv::max('fecha');                 
        return ['maxufvfecha'=>$maxufvfecha];
    }


    public function ufvGestion(Request $request)
    {
        $ip=config('app.ip'); 
        $ufvgestion=Act_Ufv::select('valor');
        if($request->criterio=="ini") $ufvgestion->where('fecha','like','%-01-01');
        if($request->criterio=="fin") $ufvgestion->where('fecha','like','%-12-31');
        return ['ufvgestion'=>$ufvgestion->get(),'ipbirt'=>$ip];
    }

    public function cargarExcel(Request $request)
    {
        /* //subir archivo
        $file=$request->file('elexcel');
        $nombre = $file->getClientOriginalName();
        $file->move(public_path(),$nombre);
        */

        /* //leer excel
        $lector=Excel::load($request->archivo)->skipRows(5)->takeColumns(13);
        $hoja=$lector->get();
        for($j=1; $j<13; $j++)
        {   
            for($i=1; $i<=31; $i++)
            {
                $mm=$j<10?"0".$j:$j;  $ff=$i<10?"0".$i:$i;
                if($hoja[$i][$j]) 
                {   
                    $ufv=new Act_Ufv();
                    $ufv->fecha="2019-$mm-$ff";
                    $ufv->valor=str_replace(",",".",$hoja[$i][$j]);
                    $ufv->save();
                }
           }                
        } */

        $tabla=$request->tabla;
        $aa=substr($tabla[0]['__EMPTY'],5); 
        for($j=1;$j<14;$j++)
        {
            for($i=3;$i<34;$i++)
            {
                $mm=$j<10?"0".$j:$j;  $ff=($i-2)<10?"0".($i-2):($i-2);
                if(isset($tabla[$i]["__EMPTY_$j"]))
                {
                    //print "<br>$aa-$mm-$ff: ".str_replace(",",".",$tabla[$i]["__EMPTY_$j"]);
                    $ufv=new Act_Ufv();
                    $ufv->fecha = $aa."-".$mm."-".$ff;
                    $ufv->valor = str_replace(",",".",$tabla[$i]["__EMPTY_$j"]);
                    $ufv->save();
                }
            }
        }

    }

}
