<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Par_productos_perfilcuenta;
use App\Par_prestamos_plan;
class File_Ascii extends Controller
{
     
 
    public function createFile(Request $request)
    {
        //detalle de las columnas del archivo asiprb+mes+año+al
        //cod. de fuerza*numero de boleta *codigo de prestamos para min def.*00*estado*bolivianos *monto sin separador de miles* afirmacion

        //detalle de las columnas del archivo asiprb+mes+año+al.bak
        //cod. de fuerza*numero de boleta *codigo de prestamos para min def.*00*estado*bolivianos *monto sin separador de miles* afirmacion
        //* numero prestamo * id_prestamo*id_prestamo_plan
        DB::statement("SET lc_time_names = 'es_ES'");
        $fechas=(DB::select('SELECT LAST_DAY(getfecha()) as fecha_plan, year(getfecha()) as anio,DATE_FORMAT(LAST_DAY(getfecha()), "%y") as anio2,DATE_FORMAT(LAST_DAY(getfecha()), "%m") as mes,upper(DATE_FORMAT(LAST_DAY(getfecha()), "%M")) as meslit, DATE_FORMAT(LAST_DAY(getfecha()), "%d_%M_%Y") as fecha'))[0];
        $nameFile='asiprb'.$fechas->mes.$fechas->anio2.'al';
        $nameFile2='asiprb'.$fechas->mes.$fechas->anio2.'al.bak';
        $nameFile3=$nameFile.'.json'; 
        $directory='ASCII';
        Storage::makeDirectory($directory);
        $directory= $directory."/".$fechas->anio;
        Storage::makeDirectory($directory);
        $directory= $directory."/".$fechas->meslit;
        Storage::makeDirectory($directory);
        $nameFile3=$directory."/".$nameFile3; 
        $nameFile2=$directory."/".$nameFile2; 
        $nameFile=$directory."/".$nameFile; 
 
        if (Storage::exists($nameFile))
        {  return ['error'=>'Ya se genero el archivo ASCII','status'=>'error'];
        }else{
            try {
                ini_set('memory_limit', '1024M');
                ini_set ('max_execution_time', 3200);
                $prestamos= DB::select("select round(pl.cut*mo.tipocambio,2) as cuota,pl.cut,pl.in,pl.indi,pl.cuota as cuota2,pl.am,p.plazo,p.idproducto,pro.cobranza_perfil_ascii,p.no_prestamo,pl.fp,mo.tipocambio,pl.cut,pl.idplan,pl.idprestamo, so.numpapeleta ,so.idfuerza,f.nomfuerza,g.nomgrado,so.apaterno,so.amaterno,so.nombre,concat('1') as estadoascii,
                concat('B') as monedaascii,concat('1') as plazoascii,concat('124') as codmindef,concat('00') as cerosascii,concat('1') as afirmacionascii
                from socios so, par__prestamos p, par__prestamos__plans pl, par__productos pro, par__monedas mo, par_grados g, par_fuerzas f
                where pro.moneda=mo.idmoneda
                and so.idfuerza=f.idfuerza
                and so.idgrado=g.idgrado
                and p.idsocio=so.idsocio
                and p.idproducto=pro.idproducto 
                and p.idprestamo=pl.idprestamo 
                and (p.idestado=2 or p.idestado=3) 
                and p.apro_conta=1 
                and pl.fp=? 
                and pl.idestado=2
                order by so.idfuerza,so.apaterno ", array($fechas->fecha_plan));

                if(count($prestamos)>0){ 
                    $max_saldo_menor=(DB::select("SELECT valor FROM configs WHERE codigo='SM'"))[0]->valor; 
                    $file = fopen(Storage::disk('local')->path($nameFile), "a");
                    $file2 = fopen(Storage::disk('local')->path($nameFile2), "a");
                    $arrayPdf=[];
                        foreach($prestamos as $dataprestamo){
                                /*
                                    echo 
                                $dataprestamo->cuota .' '. 
                                $dataprestamo->fp .' '. 
                                $dataprestamo->tipocambio.' '. 
                                $dataprestamo->cut.' '. 
                                $dataprestamo->idplan.' '. 
                                $dataprestamo->idprestamo.' '. 
                                $dataprestamo->no_prestamo.' '. 
                                $dataprestamo->numpapeleta.' '. 
                                $dataprestamo->idfuerza.' '. 
                                $dataprestamo->nomfuerza.' '. 
                                $dataprestamo->nomgrado.' '. 
                                $dataprestamo->apaterno.' '. 
                                $dataprestamo->amaterno.' '. 
                                $dataprestamo->nombre.' '.  
                                $dataprestamo->estadoascii.' '.  
                                $dataprestamo->monedaascii.' '.  
                                $dataprestamo->plazoascii.' '.  
                                $dataprestamo->codmindef.' '.  
                                $dataprestamo->cerosascii.' '.  
                                $dataprestamo->afirmacionascii
                                .'<br>';*/

                                $perfilcobranza =$this->getperfil($dataprestamo->idproducto,$dataprestamo->cobranza_perfil_ascii); 
                                for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                                    $total_cuotas = $dataprestamo->plazo;   
                                    $cuota =$dataprestamo->cuota2;  
                                    $cuotaf =$dataprestamo->cut;  
                                    $capital = $dataprestamo->am;  
                                    $intereses = $dataprestamo->in; 
                                    $interes_diferido =$dataprestamo->indi;
                                $sumacargos=0;
                                foreach($perfilcobranza as $perfil){ 
                                        $abc="$".$perfil['valor_abc'];
                                        eval($abc."=".$perfil['formulaphp'].";");
                                        if($perfil['iscargo']==1){ 
                                            $cargoin=eval("return round(".$abc.",2);");
                                            $sumacargos+=$cargoin;  
                                                    $cargosadicionales = new Par_prestamos_plan_cargo();  
                                                    $cargosadicionales->idplan=$dataprestamo->idplan;
                                                    $cargosadicionales->idperfilcuentadetalle=$perfil['idperfilcuentadetalle'];
                                                    $cargosadicionales->cargo=$cargoin;    
                                                    $cargosadicionales->rev=(strpos($perfil['formulaphp'],'$total_cuotas')>0)?1:0;
                                                    $cargosadicionales->save(); 
                                        }
                                }
                                
                                $cuota_final_ok=round($dataprestamo->cut*$dataprestamo->tipocambio,2);

                                $plan = Par_prestamos_plan::findOrFail($dataprestamo->idplan); 
                                $plan->file=$nameFile;
                                $plan->idestado=10;
                                if($plan->pe==$dataprestamo->plazo){
                                    $plan->cuota=$plan->ca_an+ $plan->in;
                                    $plan->am=$plan->ca_an;
                                    $cuotaaux=round($plan->ca_an+ $plan->in+$plan->indi+$sumacargos,2);
                                    $plan->cut=$cuotaaux;
                                    $plan->ca=0;
                                    $cuota_final_ok=round($cuotaaux*$dataprestamo->tipocambio,2);
                                } 
                                $plan->send_ascii=$cuota_final_ok; 
                                $plan->car=$sumacargos;
                                $plan->tipocambio=$dataprestamo->tipocambio;
                                $plan->save();

                                

                                $valuelinea=[];
                                $valuelinea["idfuerza"]=$dataprestamo->idfuerza; 
                                $valuelinea["fuerza"]=$dataprestamo->nomfuerza; 
                                $valuelinea["numpapeleta"] = $dataprestamo->numpapeleta;
                                $valuelinea["grado"] = $dataprestamo->nomgrado;
                                $valuelinea["nombre"] =( 
                                    $dataprestamo->apaterno.' '. 
                                    $dataprestamo->amaterno.' '. 
                                    $dataprestamo->nombre );
                                    
                                $valuelinea["fuerza"]=$dataprestamo->nomfuerza;  
                                $valuelinea["monedatipocambio"]=$dataprestamo->tipocambio;  
                                $valuelinea["estado"]=$dataprestamo->estadoascii;
                                $valuelinea["moneda"]=$dataprestamo->monedaascii;
                                $valuelinea["cuota"]=$cuota_final_ok;
                                $valuelinea["cut"]=$dataprestamo->cut;
                                $valuelinea["plazo"]=$dataprestamo->plazoascii;
                                $valuelinea["codmindef"]=$dataprestamo->codmindef;

                                $valuelinea["no_prestamo"]=$dataprestamo->no_prestamo;
                                $valuelinea["idprestamo"]=$dataprestamo->idprestamo;
                                $valuelinea["idproducto"]=$dataprestamo->idproducto; 
                                array_push($arrayPdf,$valuelinea);
                             
                                    fwrite($file,$dataprestamo->idfuerza.'*'.$dataprestamo->numpapeleta.'*'.$dataprestamo->codmindef.'*'.$dataprestamo->cerosascii.'*'
                                    .$dataprestamo->estadoascii.'*'.$dataprestamo->monedaascii.'*'.$cuota_final_ok.'*'.$dataprestamo->afirmacionascii."\r\n");
                                    fwrite($file2,$dataprestamo->idfuerza.'*'.$dataprestamo->numpapeleta.'*'.$dataprestamo->codmindef.'*'.$dataprestamo->cerosascii.'*'
                                    .$dataprestamo->estadoascii.'*'.$dataprestamo->monedaascii.'*'.$cuota_final_ok.'*'.$dataprestamo->afirmacionascii.'*'
                                    .$dataprestamo->no_prestamo.'*'.$dataprestamo->idprestamo.'*'.$dataprestamo->idplan."\r\n"); 
                       
                                    $this->recalcularPrestamos_sinmora($dataprestamo->idprestamo);
                         }  
                        fclose($file);
                        fclose($file2); 
                        $pdfout=[];
                        $catidadtotal=sizeof($arrayPdf);
                        $montototal=0;
                        foreach($arrayPdf as $valor){ 
                            $montototal=round( $montototal+$valor['cuota'],2);
                            if (array_key_exists($valor['idfuerza'],$pdfout)){
                                $temporal=$pdfout[$valor['idfuerza']];
                                $aux=$temporal['value'];
                                $auxcantidad=$temporal['cantidad']+1;
                                $auxtotal=round($temporal['total']+$valor['cuota'],2); 
                                array_push($aux,$valor); 
                               $pdfout[$valor['idfuerza']]=array('cod'=>124,'nombre'=>$valor['fuerza'],'value'=>$aux,'cantidad'=>$auxcantidad,'total'=>$auxtotal);
                            }else{
                               $pdfout[$valor['idfuerza']]=array('cod'=>124,'nombre'=>$valor['fuerza'],'value'=>array($valor),'cantidad'=>1,'total'=>$valor['cuota']);
                            }
                        }
                         
                        Storage::append($nameFile3,json_encode(array('anio'=>$fechas->anio,'mes'=>$fechas->meslit,'cantidad'=>$catidadtotal,'monto'=>$montototal,'data'=>$pdfout)));
                        
                        DB::update('update par__fecha__sistemas set fechaCorte = 1 where activo = 1');

                    return ['status'=>'ok','filename'=>$nameFile,'bak'=>$nameFile2 ];
                }else{
                    return ['error'=>'No existen datos en DB para la generación ASCII','status'=>'error'];
                }
           } catch (\Exception $e) {
                return ['error'=>$e,'status'=>'error'];
           } 
        }   
    }
    public function download(Request $request){
        return Storage::download($request->file);
    }
    public function get(Request $request){
        $fecha=(DB::select("select getfecha() as total"))[0]->total;
        return ['file'=>Storage::get($request->file),'fecha'=>$fecha,'hora'=>date('h:i:s a')];
    }
    public function view(Request $request){
        $files = array();  
        $direcc=Storage::disk('local')->Directories('ASCII');
        foreach($direcc as $anio){ 
            $items=[];
            $meses=Storage::disk('local')->Directories($anio);  
            $months_list = array(
                strtolower($anio).'/enero', strtolower($anio).'/febrero',strtolower($anio).'/marzo', strtolower($anio).'/abril',
                strtolower($anio).'/mayo', strtolower($anio).'/junio', strtolower($anio).'/julio', strtolower($anio).'/agosto',
                strtolower($anio).'/septiembre', strtolower($anio).'/octubre', strtolower($anio).'/noviembre', strtolower($anio).'/diciembre'
            );

            uasort($meses, function($a, $b) use($months_list)
            { 
                $pos_a = array_search(strtolower($a), $months_list);
                $pos_b = array_search(strtolower($b), $months_list); 
                if($pos_a == $pos_b)
                    return 0; 
                return $pos_a > $pos_b ? 1 : -1;
            });
 
            $arrays_file1=explode("/",$anio); 
            foreach($meses as $valor){ 
                    $documentos=[];
                    $archivos=Storage::disk('local')->files($valor); 
                    $arrays_file=explode("/",$valor); 
                    foreach($archivos as $itemar){ 
                          $arrays=explode("/",$itemar); 
                          $documentos[] = array(
                            "name" => end($arrays),
                            "type" => "file",
                            "path" => $itemar,
                            "size" => Storage::size($itemar)
                          );

                    } 
                    $items[] = array(
                        "name" => end($arrays_file),
                        "type" => "folder",
                        "mes" => true,
                        "path" => $valor,
                        "items" =>$documentos 
                    );
            }
            $files[] = array(
                "name" => end($arrays_file1),
                "type" => "folder",
                "path" => $anio,
              "items" =>$items 
            );
        }
        return (array(
            "name" => "ASCII",
            "type" => "folder",
            "path" => 'ASCII',
            "items" => $files
        ));
    }
     
    function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB');  
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1);  
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 
    function getperfil($producto,$idperfil){ 
        return Par_productos_perfilcuenta::join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
         ->select('par__productos__perfilcuentas.idperfilcuentamaestro',
         'par__productos__perfilcuentas.idperfilcuentadetalle',
         'par__productos__perfilcuentas.valor_abc',
         'par__productos__perfilcuentas.valor_abc_php',
         'par__productos__perfilcuentas.formula',
         'par__productos__perfilcuentas.formulaphp',
         'con__perfilcuentadetalles.tipocargo',
         'con__perfilcuentadetalles.idcuenta',
         'par__productos__perfilcuentas.iscargo')
         ->where('par__productos__perfilcuentas.activo','=','1')  
         ->where('par__productos__perfilcuentas.idproducto','=',$producto)
         ->where('par__productos__perfilcuentas.idperfilcuentamaestro','=',$idperfil)
         ->orderBy('par__productos__perfilcuentas.valor_abc', 'asc')
         ->get()->toArray();
     }
     public function recalcularPrestamos_sinmora($prestamo)
     {  
        $total=DB::select("select * from par__prestamos__plans where idprestamo=? and (idestado=2 or idestado=10) ORDER by pe asc", array($prestamo));
        $tasain=DB::select("select p.tasa,pp.plazo,p.idproducto,p.cobranza_perfil_ascii from par__productos p, par__prestamos pp where p.idproducto=pp.idproducto and pp.idprestamo=?", array($prestamo));
        $tasa=$tasain[0]->tasa;
        $plazo_prestamo=$tasain[0]->plazo;
        $idproducto=$tasain[0]->idproducto;
        $idperfilcobranzaascii=$tasain[0]->cobranza_perfil_ascii;
        $tem =($tasa/12)/100;  
        $sumatotal=0; 
        $pruebaarray=array(); 
        foreach($total as $valor)
               {      
                    if($valor->idestado==10){
                       if($valor->pe!=$plazo_prestamo){   
                           $auz_am=round((($valor->cut-$valor->car)-$valor->indi)-$valor->in,2);
                           $amortiza=($auz_am>0)?$auz_am:0;
                           $sumatotal=round(($valor->ca_an-$amortiza),2); 
                           array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>
                           array( 'am'=>$amortiza,'ca'=>$sumatotal)));
                       }
                    }elseif($valor->pe==$plazo_prestamo){
                         $aux=$sumatotal;
                        $interes=round(($aux*$tem*$valor->di)/30,2); 
                        $cuota=round($aux+$interes,2);
                       
                        $total_cuotas = $plazo_prestamo;    
                        $cuotaf =round($cuota+$valor->indi+$valor->car,2);  
                        $capital = $aux;  
                        $intereses = $interes;
    //////////////////////////////////////////////////////////////////////////// 
                           $perfilcobranza =$this->getperfil($idproducto,$idperfilcobranzaascii); 
                           for($i=65; $i<=90; $i++) { $abc="$".chr($i); eval($abc."=0;"); }
                            $cargoFinal=0;
                                   foreach($perfilcobranza as $perfil){ 
                                           $abc="$".$perfil['valor_abc'];
                                           eval($abc."=".$perfil['formulaphp'].";");
                                           if($perfil['iscargo']==1){ 
                                               $cargoin=eval("return round(".$abc.",2);");
                                               $cargoFinal+=$cargoin;  
                                           }
                                   } 
    ////////////////////////////////////////////////////////////////////////////
    
                        array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array( 'am'=>$aux,'in'=>$interes,
                        'car'=>$cargoFinal,'ca'=>0,'cut'=>round($cuota+$valor->indi+$cargoFinal,2),'ca_an'=>$aux,'cuota'=>$cuota)));
                          
                    }else{
                       $aux=$sumatotal; 
                       $auz_am=round((($valor->cut-$valor->car)-$valor->indi)-$valor->in,2);
                       $amortiza=($auz_am>0)?$auz_am:0;
                       $sumatotal=round(($aux-$amortiza),2);
                       array_push($pruebaarray,array('key'=>$valor->idplan,'value'=>array('am'=>$amortiza,
                       'ca'=>$sumatotal,'ca_an'=>$aux))); 
                    } 
    
    
               } 
              
             foreach($pruebaarray as $valor){  
                    $planpago = Par_prestamos_plan::findOrFail($valor["key"]); 
                    foreach($valor["value"] as $akey=>$avalue) { $planpago->$akey=$avalue; }
                    $planpago->save(); 
               }  
         
     } 
}
